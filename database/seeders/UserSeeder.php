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
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'role' => 'admin',
        ]);
        $Manager = User::factory()->create([
            'name' => 'Sami',
            'email' => 'Sami@gmail.com',
            'password' => '12345678',
            'role' => 'manager',
        ]);
        $user = User::factory()->create([
            'name' => 'Tina',
            'email' => 'Tina@gmail.com',
            'password' => '12345678',
            'role' => 'user',
        ]);

        // Optionally, create more users
        User::factory()->count(10)->create();
    }
}
