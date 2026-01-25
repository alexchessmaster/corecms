<?php

namespace App\Modules\News\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NewsPolicySeeder extends Seeder
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
                'news tags',
                'news categories',
                'news authors',
                'news',
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
                'news tags',
                'news categories',
                'news authors',
                'news',
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
                'news tags',
                'news categories',
                'news authors',
                'news',
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
