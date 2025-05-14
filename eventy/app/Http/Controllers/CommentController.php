<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return view
        return view('comments.index');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to comment on an event.');
        }
        
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'content' => 'required|string|max:500',
        ]);
        
        $event = Event::findOrFail($validated['event_id']);
        
        // Create the comment
        $comment = Comment::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
                'message' => 'Comment added successfully'
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment added successfully');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user is authenticated and is the comment owner
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to edit this comment'
                ], 403);
            }
            
            return redirect()->back()
                ->with('error', 'You are not authorized to edit this comment.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);
        
        // Update the comment
        $comment->update([
            'content' => $validated['content'],
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->fresh()->load('user'),
                'message' => 'Comment updated successfully'
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Check if user is authenticated and is the comment owner or event owner
        $isCommentOwner = Auth::check() && $comment->user_id === Auth::id();
        $isEventOwner = Auth::check() && $comment->event->user_id === Auth::id();
        
        if (!$isCommentOwner && !$isEventOwner) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this comment'
                ], 403);
            }
            
            return redirect()->back()
                ->with('error', 'You are not authorized to delete this comment.');
        }
        
        // Delete the comment
        $comment->delete();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
} 