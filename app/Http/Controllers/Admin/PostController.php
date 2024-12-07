<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->withTrashed() 
            ->get();

        return inertia('Admin/Posts/Index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return inertia('Admin/Posts/Form', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'title'         => $validated['title'],
            'content'       => $validated['content'],
            'category_id'   => $validated['category_id'],
            'user_id'       => Auth::id()
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

    public function update(PostRequest $request, $id)
    {
        $validated = $request->validated();

        $post = Post::withTrashed()->findOrFail($id);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id'       => Auth::id()
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

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('admin.posts.index')->with('success', 'Post restored successfully.');
    }
}
