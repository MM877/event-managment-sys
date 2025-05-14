<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with(['user', 'event'])->latest()->paginate(12);
        return view('galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to add gallery images.');
        }
        
        // Get events for dropdown (either all events or just the user's events depending on requirements)
        $events = Event::where('user_id', Auth::id())->orderBy('name')->get();
        
        if ($events->isEmpty()) {
            return redirect()->route('events.create')
                ->with('error', 'You need to create an event first before adding gallery images.');
        }
        
        return view('galleries.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to add gallery images.');
        }
        
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'caption' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Check if the event belongs to the user
        $event = Event::findOrFail($validated['event_id']);
        if ($event->user_id !== Auth::id()) {
            return redirect()->route('galleries.index')
                ->with('error', 'You can only add gallery images to your own events.');
        }
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('galleries', 'public');
        }
        
        // Create gallery item
        Gallery::create([
            'event_id' => $validated['event_id'],
            'caption' => $validated['caption'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('galleries.index')
            ->with('success', 'Gallery image added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return view('galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        // Ensure the user can only edit their own gallery items
        if (!Auth::check() || $gallery->user_id !== Auth::id()) {
            return redirect()->route('galleries.index')
                ->with('error', 'You are not authorized to edit this gallery item.');
        }
        
        $events = Event::where('user_id', Auth::id())->orderBy('name')->get();
        
        return view('galleries.edit', compact('gallery', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        // Ensure the user can only update their own gallery items
        if (!Auth::check() || $gallery->user_id !== Auth::id()) {
            return redirect()->route('galleries.index')
                ->with('error', 'You are not authorized to update this gallery item.');
        }
        
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'caption' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Check if the event belongs to the user
        $event = Event::findOrFail($validated['event_id']);
        if ($event->user_id !== Auth::id()) {
            return redirect()->route('galleries.index')
                ->with('error', 'You can only add gallery images to your own events.');
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            
            $imagePath = $request->file('image')->store('galleries', 'public');
        } else {
            $imagePath = $gallery->image;
        }
        
        // Update gallery item
        $gallery->update([
            'event_id' => $validated['event_id'],
            'caption' => $validated['caption'],
            'image' => $imagePath,
        ]);
        
        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        // Ensure the user can only delete their own gallery items
        if (!Auth::check() || $gallery->user_id !== Auth::id()) {
            return redirect()->route('galleries.index')
                ->with('error', 'You are not authorized to delete this gallery item.');
        }
        
        // Delete gallery image if it exists
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        // Delete the gallery item
        $gallery->delete();
        
        return redirect()->route('galleries.index')
            ->with('success', 'Gallery item deleted successfully.');
    }
}
