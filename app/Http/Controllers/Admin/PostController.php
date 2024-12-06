<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->withTrashed() 
            ->latest()
            ->paginate(10);

        return inertia('Admin/Posts/Index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return inertia('Admin/Posts/Form', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created post in the database.
     */
    public function store(PostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id']
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        }

        if (!empty($validated['image'])) {
            $path = $validated['image']->file('img')->store('posts', 'public');
            $post->img_path = $path;
            $post->save();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing a post.
     */
    public function edit($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return inertia('Admin/Posts/Form', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified post in the database.
     */
    public function update(PostRequest $request, $id)
    {
        $validated = $request->validated();

        $post = Post::withTrashed()->findOrFail($id);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id']
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        }

        if (!empty($validated['image'])) {
            $path = $validated['image']->store('posts', 'public');
            $post->image = $path;
            $post->save();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post (soft delete).
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Restore a soft-deleted post.
     */
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('admin.posts.index')->with('success', 'Post restored successfully.');
    }
}
