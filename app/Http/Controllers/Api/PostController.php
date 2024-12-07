<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'tags', 'comments']);

        // Filtering by category
        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filtering by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('name', explode('|', $request->input('tag')));
            });
        }

        // Filtering by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Date::parse($request->input('start_date'))->startOfDay();
            $endDate = Date::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Paginate results with 10 posts per page
        $posts = $query->paginate(10);

        return response()->json([
            'data' => $posts->getCollection()->map(function ($post) {
                return [
                    'title' => $post->title,
                    'content' => $post->content,
                    'category' => [
                        'id' => $post->category->id,
                        'name' => $post->category->name,
                    ],
                    'tags' => $post->tags->pluck('name'),
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'author' => $comment->author_name,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->toIso8601String(),
                        ];
                    }),
                ];
            }),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }
}
