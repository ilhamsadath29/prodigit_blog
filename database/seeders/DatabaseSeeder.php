<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("admin123"),

        ]);

        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'user@gmail.com',
            'password' => Hash::make("user123"),
        ]);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'manage posts']);
        Permission::create(['name' => 'edit posts']);

        $admin->assignRole('admin');
        $admin->givePermissionTo('manage posts');
        
        $user->assignRole('user');

        $categories = Category::factory(5)->create();

        foreach ($categories as $category) {
            $posts = Post::factory(3)->create(['category_id' => $category->id]);

            foreach ($posts as $post) {
                $tags = Tag::inRandomOrder()->take(rand(2, 5))->get();
                $post->tags()->attach($tags);

                Comment::factory(rand(2, 5))->create(['post_id' => $post->id, 'user_id' => $user->id]);
            }
        }
    }
}
