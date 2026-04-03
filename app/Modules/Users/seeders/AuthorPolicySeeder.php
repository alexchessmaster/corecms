<?php

namespace App\Modules\Users\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthorPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if roles and permissions tables are empty
        // if (\Spatie\Permission\Models\Role::count() === 0 && \Spatie\Permission\Models\Permission::count() === 0) {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modelName = 'product';

        // remove all the old data from permission that contains modelName
        Permission::where('name', 'LIKE', "% $modelName authors%")->delete();

        // Define all resources
        $resources = [
            "$modelName authors",
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
                Permission::firstOrCreate(
                    [
                        'name' => "$action $resource",
                        'guard_name' => 'web',
                    ]
                );
            }
        }

        // Create roles
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $authorRole = Role::where('name', 'author')->first();

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Editor permissions - can manage most content but not users or system settings
        $editorResources = [
            "$modelName authors",
        ];

        foreach ($editorResources as $resource) {
            $editorRole->givePermissionTo([
                "view $resource",
                "create $resource",
                "edit $resource",
                "edit own $resource",
                "delete $resource",
                "delete own $resource",
            ]);
        }

        // Author permissions - can only manage their own content
        $authorResources = [
            "$modelName authors",
        ];

        foreach ($authorResources as $resource) {
            $authorRole->givePermissionTo([
                "view own $resource",
                "create $resource",
                "edit own $resource",
                "delete own $resource",
            ]);
        }

        $this->command->info('Roles and permissions created successfully!');
        // } else {
        //     $this->command->info('Roles and permissions already exist. Seeder skipped.');
        // }
    }
}
