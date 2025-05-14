<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     */
    public function index()
    {
        $tags = Tag::withCount('events')->paginate(20);
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified tag.
     */
    public function show(Tag $tag)
    {
        $events = $tag->events()->paginate(10);
        return view('tags.show', compact('tag', 'events'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
} 