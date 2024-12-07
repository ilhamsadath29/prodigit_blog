<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
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
        // Create roles and permissions
        $this->createRolesAndPermissions();

        // Create users
        $admin = $this->createUser('Admin', 'admin@gmail.com', 'admin123', 'admin');
        $user = $this->createUser('User', 'user@gmail.com', 'user123', 'user');

        // Assign permissions to roles
        $this->assignPermissionsToAdmin($admin);
        $this->assignPermissionsToUser($user);

        // Seed categories, tags, posts, and comments
        $this->seedPostsWithTagsAndComments();
    }

    private function createRolesAndPermissions(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Define permissions
        $permissions = [
            'can-list-users', 'can-create-users', 'can-delete-users',
            'can-list-tags', 'can-create-tags', 'can-edit-tags', 'can-delete-tags',
            'can-list-categories', 'can-create-categories', 'can-edit-categories', 'can-delete-categories',
            'can-list-posts', 'can-create-posts', 'can-edit-posts', 'can-delete-posts', 'can-restore-posts',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    private function createUser(string $name, string $email, string $password, string $role): User
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);
        return $user;
    }

    private function assignPermissionsToAdmin(User $admin): void
    {
        $permissions = [
            'can-list-users', 'can-create-users', 'can-delete-users',
            'can-list-tags', 'can-create-tags', 'can-edit-tags', 'can-delete-tags',
            'can-list-categories', 'can-create-categories', 'can-edit-categories', 'can-delete-categories',
            'can-list-posts', 'can-create-posts', 'can-edit-posts', 'can-delete-posts', 'can-restore-posts',
        ];

        $admin->givePermissionTo($permissions);
    }

    private function assignPermissionsToUser(User $user): void
    {
        $permissions = ['can-list-posts', 'can-create-posts', 'can-edit-posts', 'can-delete-posts', 'can-restore-posts'];
        $user->givePermissionTo($permissions);
    }

    private function seedPostsWithTagsAndComments(): void
    {
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
