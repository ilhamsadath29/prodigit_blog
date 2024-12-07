<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class TagController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Tag::class);
        $tags = Tag::all();
        return Inertia::render('Admin/Tags/Index', [
            'tags' => $tags,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Tag::class);
        return Inertia::render('Admin/Tags/Form');
    }

    public function store(TagRequest $request)
    {
        $this->authorize('create', Tag::class);
        $validated = $request->validated();
        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);
        return Inertia::render('Admin/Tags/Form', [
            'tag' => $tag,
        ]);
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $validated = $request->validated();

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
