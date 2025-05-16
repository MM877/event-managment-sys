<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class AttendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return view with events the user is attending
        $events = Event::whereHas('attendees', function ($query) {
            $query->where('user_id', Auth::id());
        })->latest()->paginate(10);
        
        return view('attending.index', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
        
        $event = Event::findOrFail($request->event_id);
        
        // Check if user is already attending
        if (!$event->attendees()->where('user_id', Auth::id())->exists()) {
            $event->attendees()->attach(Auth::id());
            $message = 'You are now attending this event.';
            $success = true;
        } else {
            $message = 'You are already attending this event.';
            $success = false;
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'attending_count' => $event->attendees()->count()
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
        
        $event = Event::findOrFail($request->event_id);
        
        // Check if user is attending and detach
        if ($event->attendees()->where('user_id', Auth::id())->exists()) {
            $event->attendees()->detach(Auth::id());
            $message = 'You have canceled your attendance.';
            $success = true;
        } else {
            $message = 'You were not attending this event.';
            $success = false;
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'attending_count' => $event->attendees()->count()
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
}