<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    // Display a list of tags
    public function index()
    {
        $tags = Tag::all();
        return Inertia::render('Admin/Tags/Index', [
            'tags' => $tags,
        ]);
    }

    // Show the form to create a new tag
    public function create()
    {
        return Inertia::render('Admin/Tags/Create');
    }

    // Store a new tag in the database
    public function store(TagRequest $request)
    {
        $validated = $request->validated();
        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    // Show the form to edit a tag
    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Edit', [
            'tag' => $tag,
        ]);
    }

    // Update a tag in the database
    public function update(TagRequest $request, Tag $tag)
    {
        $validated = $request->validated();

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    // Delete a tag
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
