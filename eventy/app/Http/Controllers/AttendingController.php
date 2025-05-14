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
        // Return view
        return view('saved-events.index', [
            'events' => Event::whereHas('attendees', function ($query) {
                $query->where('user_id', Auth::id());
            })->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Store logic
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        // Delete logic
    }

    public function attend(Event $event)
    {
        $event->attendees()->attach(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'You are now attending this event.',
        ]);
        
return redirect()->route('events.show', $event->id)
    ->with('success', 'Event updated successfully.');

    }

    public function cancelAttendance(Event $event)
    {
        $event->attendees()->detach(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'You have canceled your attendance.',
        ]);
    }
}