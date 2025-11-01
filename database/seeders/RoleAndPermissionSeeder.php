<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if roles and permissions tables are empty
        if (\Spatie\Permission\Models\Role::count() === 0 && \Spatie\Permission\Models\Permission::count() === 0) {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Define all resources
            $resources = [
                'users',
                'categories',
                'articles',
                'pages',
                'tags',
                'product tags',
                'product categories',
                'product authors',
                'products',
                'books',
                'book authors',
                'book genres',
                'uploads',
                'fields',
                'widgets',
                'widget field values',
                'widgetables',
                'field widgets',
                'menus',
                'settings',
                'languages',
                'translation texts',
                'redirects',
                'redirect slug changes',
                'url logs',
                'booking time slots',
                'booking reservations',
                'ai chats',
                'ai messages',
                'ai personas',
                'commentables',
            ];

            // Define permission actions
            $actions = [
                'view',
                'view own',
                'create',
                'edit',
                'edit own',
                'delete',
                'delete own',
                'restore',
                'force delete',
            ];

            // Create permissions for each resource
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    Permission::create(['name' => "$action $resource"]);
                }
            }

            // Create roles
            $adminRole = Role::create(['name' => 'admin']);
            $editorRole = Role::create(['name' => 'editor']);
            $authorRole = Role::create(['name' => 'author']);

            // Admin gets all permissions
            $adminRole->givePermissionTo(Permission::all());

            // Editor permissions - can manage most content but not users or system settings
            $editorResources = [
                'categories',
                'articles',
                'pages',
                'tags',
                'product tags',
                'product categories',
                'product authors',
                'products',
                'books',
                'book authors',
                'book genres',
                'uploads',
                'fields',
                'widgets',
                'widget field values',
                'widgetables',
                'field widgets',
                'menus',
                'languages',
                'translation texts',
                'redirects',
                'redirect slug changes',
                'url logs',
                'booking time slots',
                'booking reservations',
                'ai chats',
                'ai messages',
                'ai personas',
                'commentables',
            ];

            foreach ($editorResources as $resource) {
                $editorRole->givePermissionTo([
                    "view $resource",
                    "create $resource",
                    "edit $resource",
                    "delete $resource",
                    "restore $resource",
                ]);
            }

            // Author permissions - can only manage their own content
            $authorResources = [
                'categories',
                'articles',
                'pages',
                'tags',
                'product tags',
                'product categories',
                'product authors',
                'products',
                'books',
                'book authors',
                'book genres',
                'uploads',
                'ai chats',
                'ai messages',
                'commentables',
            ];

            foreach ($authorResources as $resource) {
                $authorRole->givePermissionTo([
                    "view own $resource",
                    "create $resource",
                    "edit own $resource",
                    "delete own $resource",
                ]);
            }

            // Additional specific permissions for authors
            $authorRole->givePermissionTo([
                'view fields',
                'view widgets',
                'view menus',
                'view languages',
            ]);

            $this->command->info('Roles and permissions created successfully!');
        } else {
            $this->command->info('Roles and permissions already exist. Seeder skipped.');
        }
    }
}
