<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles (they should already be created by RoleAndPermissionSeeder)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $authorRole = Role::firstOrCreate(['name' => 'author']);

        // Give super_admin all permissions (if not already done)
        if ($superAdminRole->permissions()->count() === 0) {
            $superAdminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());
        }

        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Alex',
            'email' => 'alex@alex.com',
            'password' => '$2y$12$b7.b/4iC0lDirTRjah2yZOv9apyXGbnfwyPI6sNlTafyQvR.S5KfW', // ...x@...x.com@keys294
            'show_edit_button_on_texts' => false,
        ]);
        $superAdmin->assignRole('super_admin');

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'show_edit_button_on_texts' => false,
        ]);
        $admin->assignRole('admin');

        // Create editor user
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
            'show_edit_button_on_texts' => false,
        ]);
        $editor->assignRole('editor');

        // Create author user
        $author = User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => bcrypt('password'),
            'show_edit_button_on_texts' => false,
        ]);
        $author->assignRole('author');
    }
}
