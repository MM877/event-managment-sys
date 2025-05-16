<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavedEvent;
use Illuminate\Support\Facades\Auth;

class SavedEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $savedEvents = SavedEvent::where('user_id', Auth::id())
            ->with('event', 'event.country')
            ->latest()
            ->get();
            
        return view('saved-events.index', compact('savedEvents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        // Check if already saved
        $existing = SavedEvent::where('user_id', Auth::id())
            ->where('event_id', $validated['event_id'])
            ->first();

        if (!$existing) {
            SavedEvent::create([
                'user_id' => Auth::id(),
                'event_id' => $validated['event_id'],
            ]);
        }

        return redirect()->back()->with('success', 'Event saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $savedEvent = SavedEvent::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $savedEvent->delete();
        
        return redirect()->back()->with('success', 'Event removed from saved list');
    }
}
