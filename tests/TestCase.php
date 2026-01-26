<?php

namespace Tests;

use Tests\CreatesApplication;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // If you use RefreshDatabase, migrations/transactions are handled elsewhere.
        // This just ensures Spatie's cached permissions don't bite you.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->setUserPermissions();
    }

    private function setUserPermissions()
    {
        if (\Spatie\Permission\Models\Role::count() === 0 && \Spatie\Permission\Models\Permission::count() === 0) {
            $arrayOfModels = ['pages', 'articles', 'books', 'products', 'news'];
            foreach ($arrayOfModels as $modelName) {
                $resources = [
                    "$modelName tags",
                    "$modelName categories",
                    "$modelName authors",
                    "$modelName",
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
                        Permission::firstOrCreate(['name' => "$action $resource"]);
                    }
                }

                $adminRole = Role::firstOrCreate(['name' => 'admin']);
                $editorRole = Role::firstOrCreate(['name' => 'editor']);
                $authorRole = Role::firstOrCreate(['name' => 'author']);

                $adminRole->givePermissionTo(Permission::all());

                $editorResources = [
                    "$modelName tags",
                    "$modelName categories",
                    "$modelName authors",
                    "$modelName",
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
                    "$modelName tags",
                    "$modelName categories",
                    "$modelName authors",
                    "$modelName",
                ];

                foreach ($authorResources as $resource) {
                    $authorRole->givePermissionTo([
                        "view own $resource",
                        "create $resource",
                        "edit own $resource",
                        "delete own $resource",
                    ]);
                }
            }
        }
    }
}
