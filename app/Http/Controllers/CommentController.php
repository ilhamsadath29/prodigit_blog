<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Mail\CommentNotification;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $id)
    {
        $validated = $request->validated();
        $post = Post::withoutTrashed()->with(['user'])->findOrFail($id);

        $validated['post_id'] = $post->id;
        Comment::created($validated);

        Mail::to($post->user->email)->send(new CommentNotification($post, $validated));

        return redirect()->route('home')->with('success', 'Comment created successfully.');
    }
}
