<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alex',
            'email' => 'alex@alex.com',
            'password' => '$2y$12$b7.b/4iC0lDirTRjah2yZOv9apyXGbnfwyPI6sNlTafyQvR.S5KfW', // ...x@...x.com@keys294
            'role' => 'admin',
            'show_edit_button_on_texts' => false,
        ]);
    }
}
