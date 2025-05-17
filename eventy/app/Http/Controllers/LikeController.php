<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return view
        return view('likes.index');
    }

    /**
     * Store a newly created like in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like an event'
            ], 401);
        }
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
        
        $event = Event::findOrFail($request->event_id);
        
        // Check if user already liked this event
        $existingLike = Like::where('event_id', $event->id)
                           ->where('user_id', Auth::id())
                           ->first();
                           
        if ($existingLike) {
            return response()->json([
                'success' => false,
                'message' => 'You have already liked this event'
            ]);
        }
        
        // Create the like
        $like = Like::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
        ]);
        
        // Return response with updated like count
        $likeCount = $event->likes()->count();
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $likeCount,
                'message' => 'Event liked successfully',
            ]);
        }
        
        return redirect()->back()->with('success', 'Event liked successfully');
    }

    /**
     * Find a like by event ID for the authenticated user.
     */
    public function findByEventId(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to perform this action'
            ], 401);
        }
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);
        
        $like = Like::where('event_id', $request->event_id)
                   ->where('user_id', Auth::id())
                   ->first();
                   
        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'like_id' => $like->id
        ]);
    }

    /**
     * Remove the specified like from storage.
     */
    public function destroy(Like $like)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to unlike an event'
            ], 401);
        }
        
        // Check if the user owns this like
        if ($like->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }
        
        $event = $like->event;
        $like->delete();
        
        // Return response with updated like count
        $likeCount = $event->likes()->count();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $likeCount,
                'message' => 'Event unliked successfully',
            ]);
        }
        
        return redirect()->back()->with('success', 'Event unliked successfully');
    }
}