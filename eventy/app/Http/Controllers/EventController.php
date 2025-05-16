<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Country;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Apply country filter if selected
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        
        // Apply tag filter if selected
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }
        
        $events = $query->latest()->paginate(10)->withQueryString();
        
        $countries = Country::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('events.index', compact('events', 'countries', 'tags'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to create an event.');
        }
        
        $countries = Country::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('events.create', compact('countries', 'tags'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to create an event.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'country_id' => 'required|exists:countries,id',
            'city' => 'required|string|max:255',
            'tags' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'num_tickets' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Create the event with user ID
        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'country_id' => $validated['country_id'],
            'city' => $validated['city'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'num_tickets' => $validated['num_tickets'] ?? null,
        ]);

        // Process tags
        $tagIds = [];
        if (is_array($request->tags)) {
            $tagIds = $request->tags;
        } else {
            // For backward compatibility with comma-separated string
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    // Find or create the tag
                    $tag = Tag::firstOrCreate(['name' => $tagName, 'slug' => Str::slug($tagName)]);
                    $tagIds[] = $tag->id;
                }
            }
        }
        
        // Attach tags to the event
        $event->tags()->attach($tagIds);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        // Fetch the event by its ID
        $event = Event::findOrFail($id);

        // Pass the event to the view
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Ensure the user can only edit their own events
        if (!Auth::check() || $event->user_id !== Auth::id()) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to edit this event.');
        }
        
        $countries = Country::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('events.edit', compact('event', 'countries', 'tags'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Ensure the user can only update their own events
        if (!Auth::check() || $event->user_id !== Auth::id()) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to update this event.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'country_id' => 'required|exists:countries,id',
            'city' => 'required|string|max:255',
            'tags' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'num_tickets' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
        } else {
            $imagePath = $event->image;
        }

        // Update the event
        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'country_id' => $validated['country_id'],
            'city' => $validated['city'],
            'image' => $imagePath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'num_tickets' => $validated['num_tickets'] ?? $event->num_tickets,
        ]);

        // Process tags
        $tagIds = [];
        if (is_array($request->tags)) {
            $tagIds = $request->tags;
        } else {
            // For backward compatibility with comma-separated string
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    // Find or create the tag
                    $tag = Tag::firstOrCreate(['name' => $tagName, 'slug' => Str::slug($tagName)]);
                    $tagIds[] = $tag->id;
                }
            }
        }
        
        // Sync tags to the event
        $event->tags()->sync($tagIds);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Ensure the user can only delete their own events
        if (!Auth::check() || $event->user_id !== Auth::id()) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to delete this event.');
        }
        
        // Delete event image if it exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        // Delete the event
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Display events created by the authenticated user.
     */
    public function myEvents()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view your events.');
        }

        $events = Event::where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);
                      
        return view('events.my-events', compact('events'));
    }

    /**
     * Allow a user to attend an event.
     */
    public function attend(Request $request, Event $event)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to attend an event.');
        }

        // Add the user to the event's attendees
        $event->attendees()->attach(Auth::id());

        return response()->json(['success' => true, 'message' => 'You are now attending this event.']);
    }
}
