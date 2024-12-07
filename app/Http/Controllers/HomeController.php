<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index() 
    {
        $categories = Category::all();
        $tags = Tag::all();

        $posts = Post::with(['category', 'tags', 'comments'])
            ->whereNull('deleted_at')
            ->latest()->get();

        return Inertia::render('Home', [
            'tags' => $tags,
            'categories' => $categories,
            'posts' => $posts
        ]);
    }
}
