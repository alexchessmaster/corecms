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
            'password' => "$2y$12$7CIDGYAbk3RRAzbYb2AusuNNf3yGH6ia091iRmR3.8BI5WibYGFV.", //Hash::make('Kasper99Kasper'),
            'is_admin' => true,
            'show_edit_button_on_texts' => false,
        ]);
    }
}
