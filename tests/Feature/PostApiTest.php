<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create(['name' => 'Tech']);
        $tags = Tag::factory(3)->create();

        Post::factory(10)->create(['category_id' => $category->id])->each(function ($post) use ($tags) {
            $post->tags()->attach($tags->pluck('id')->toArray());
            Comment::factory(5)->create(['post_id' => $post->id]);
        });
    }

    public function test_fetch_all_posts()
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'title',
                             'content',
                             'category' => ['id', 'name'],
                             'tags',
                             'comments' => [
                                 '*' => ['author', 'content', 'created_at']
                             ],
                         ]
                     ],
                     'meta' => ['current_page', 'per_page', 'total']
                 ]);
    }

    public function test_filter_posts_by_category()
    {
        $category = Category::first();

        $response = $this->getJson('/api/posts?category=' . $category->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $category->id, 'name' => $category->name]);
    }

    public function test_filter_posts_by_tag()
    {
        $tag = Tag::first();

        $response = $this->getJson('/api/posts?tag=' . $tag->name);

        $response->assertStatus(200);
        $response->assertJsonFragment(['tags' => [$tag->name]]);
    }

    public function test_filter_posts_by_date_range()
    {
        $startDate = now()->subDays(7)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        $response = $this->getJson("/api/posts?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'content',
                    'category',
                    'tags',
                    'comments'
                ]
            ]
        ]);
    }

    public function test_pagination_of_posts()
    {
        $response = $this->getJson('/api/posts?page=2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => ['current_page', 'per_page', 'total']
        ]);
        $this->assertEquals(2, $response->json('meta.current_page'));
    }
}
