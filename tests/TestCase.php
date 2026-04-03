<?php

namespace Tests;

use App\Modules\Languages\Models\Language;
use App\Modules\Settings\Models\Setting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // If you use RefreshDatabase, migrations/transactions are handled elsewhere.
        // This just ensures Spatie's cached permissions don't bite you.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->setLanguages();
        $this->setUserPermissions();

        // Share the $languages variable with all views
        $languages = Language::all();
        \View::share('languages', $languages);
        // Share the $settings variable with all views
        $settings = Setting::all();
        \View::share('settings', $settings);
    }

    private function setLanguages(): void
    {
        if(Language::count() === 0){
            Language::create([
                'name' => 'English',
                'code' => 'en',
                'default' => true,
                'use_separate_domain' => false,
                'domain' => 'example.com',
            ]);
            Language::create([
                'name' => 'Danish',
                'code' => 'da',
                'default' => false,
                'use_separate_domain' => false,
                'domain' => 'example.com',
            ]);
        }
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
