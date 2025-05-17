<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets for the authenticated user.
     */
    public function index()
    {
        $tickets = Ticket::with('event')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Book a ticket for an event.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Get the event
        $event = Event::findOrFail($validated['event_id']);

        // Check if the event has tickets available
        if (!$event->num_tickets) {
            return response()->json([
                'message' => 'This event does not have tickets available for booking.'
            ], 422);
        }

        // Check if there are enough tickets available
        if ($event->remainingTickets < $validated['quantity']) {
            return response()->json([
                'message' => 'Not enough tickets available. Only ' . $event->remainingTickets . ' tickets left.'
            ], 422);
        }

        // Book the tickets
        $ticket = Ticket::create([
            'event_id' => $validated['event_id'],
            'user_id' => Auth::id(),
            'quantity' => $validated['quantity'],
            'status' => 'booked',
        ]);

        // Also save the event for the user (mark attendance)
        $alreadySaved = \App\Models\SavedEvent::where('user_id', Auth::id())
            ->where('event_id', $validated['event_id'])
            ->exists();
            
        if (!$alreadySaved) {
            \App\Models\SavedEvent::create([
                'user_id' => Auth::id(),
                'event_id' => $validated['event_id'],
            ]);
        }

        return response()->json([
            'message' => 'Tickets booked successfully!',
            'ticket' => $ticket,
            'remaining_tickets' => $event->remainingTickets - $validated['quantity'],
            'attended' => true
        ]);
    }

    /**
     * Cancel a ticket booking.
     */
    public function cancel(Ticket $ticket)
    {
        // Check if the user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            return redirect()->route('tickets.index')
                ->with('error', 'You are not authorized to cancel this booking.');
        }

        // Check if the ticket can be canceled - allow cancellation up to event start
        $event = $ticket->event;
        if ($event->start_date < now()->subHours(1)) {
            return redirect()->route('tickets.index')
                ->with('error', 'Cannot cancel tickets for an event that has already started.');
        }

        // Update ticket status using a transaction for safety
        try {
            DB::beginTransaction();
            
            $ticket->status = 'cancelled';
            $ticket->save();
            
            DB::commit();
            
            return redirect()->route('tickets.index')
                ->with('success', 'Ticket booking cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('tickets.index')
                ->with('error', 'Failed to cancel ticket. Please try again.');
        }
    }
} 