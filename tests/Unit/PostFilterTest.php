<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class PostFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_posts_by_category()
    {
        $category = Category::factory()->create(['name' => 'Tech']);
        Post::factory(3)->create(['category_id' => $category->id]);
        Post::factory(2)->create(); 

        $filteredPosts = Post::where('category_id', $category->id)->get();

        $this->assertCount(3, $filteredPosts);
        foreach ($filteredPosts as $post) {
            $this->assertEquals($category->id, $post->category_id);
        }
    }

    public function test_filter_posts_by_tag()
    {
        $tag = Tag::factory()->create(['name' => 'Laravel']);
        $postsWithTag = Post::factory(3)->create()->each(function ($post) use ($tag) {
            $post->tags()->attach($tag->id);
        });
        Post::factory(2)->create(); // Posts without the tag

        $filteredPosts = Post::whereHas('tags', function ($query) use ($tag) {
            $query->where('id', $tag->id);
        })->get();

        $this->assertCount(3, $filteredPosts);
        foreach ($filteredPosts as $post) {
            $this->assertTrue($post->tags->contains('id', $tag->id));
        }
    }

    public function test_filter_posts_by_date_range()
    {
        $startDate = now()->subDays(7);
        $endDate = now();

        Post::factory()->create(['created_at' => now()->subDays(5)]);
        Post::factory()->create(['created_at' => now()->subDays(10)]); // Outside the range

        $filteredPosts = Post::whereBetween('created_at', [$startDate, $endDate])->get();

        $this->assertCount(1, $filteredPosts);
    }
}
