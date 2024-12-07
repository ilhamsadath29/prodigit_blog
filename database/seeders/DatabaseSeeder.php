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
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("admin123"),

        ]);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make("user123"),
        ]);

        $user1 = User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make("user123"),
        ]);


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'list posts']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'restore posts']);

        Permission::create(['name' => 'list categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);

        Permission::create(['name' => 'list tags']);
        Permission::create(['name' => 'create tags']);
        Permission::create(['name' => 'edit tags']);
        Permission::create(['name' => 'delete tags']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        $admin->assignRole('admin');
        $admin->givePermissionTo('list users');
        $admin->givePermissionTo('create users');
        $admin->givePermissionTo('edit users');
        $admin->givePermissionTo('delete users');

        $admin->givePermissionTo('list tags');
        $admin->givePermissionTo('create tags');
        $admin->givePermissionTo('edit tags');
        $admin->givePermissionTo('delete tags');

        $admin->givePermissionTo('list categories');
        $admin->givePermissionTo('create categories');
        $admin->givePermissionTo('edit categories');
        $admin->givePermissionTo('delete categories');
        
        $admin->givePermissionTo('list posts');
        $admin->givePermissionTo('create posts');
        $admin->givePermissionTo('edit posts');
        $admin->givePermissionTo('delete posts');
        $admin->givePermissionTo('restore posts');
        
        $user->assignRole('user');
        $user->givePermissionTo('list posts');
        $user->givePermissionTo('create posts');
        $user->givePermissionTo('edit posts');
        $user->givePermissionTo('delete posts');
        $user->givePermissionTo('restore posts');

        $user1->assignRole('user');
        $user1->givePermissionTo('list posts');
        $user1->givePermissionTo('create posts');
        $user1->givePermissionTo('edit posts');
        $user1->givePermissionTo('delete posts');
        $user1->givePermissionTo('restore posts');
        
        Category::factory(5)->create();
        Tag::factory(7)->create();

        $posts = Post::factory(3)->create();

        foreach ($posts as $post) {
            $tags = Tag::inRandomOrder()->take(rand(2, 5))->get();
            $post->tags()->attach($tags);

            Comment::factory(rand(2, 5))->create(['post_id' => $post->id]);
        }
    }
}
