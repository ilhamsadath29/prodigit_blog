<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return Inertia::render('Admin/Tags/Index', [
            'tags' => $tags,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Tags/Form');
    }

    public function store(TagRequest $request)
    {
        $validated = $request->validated();
        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Form', [
            'tag' => $tag,
        ]);
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $validated = $request->validated();

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
