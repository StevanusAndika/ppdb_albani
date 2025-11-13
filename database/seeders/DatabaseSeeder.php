<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'is_active' => true
        ]);
        User::factory()->create([
            'name' => 'Username Santri',
            'email' => 'calon_santri@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'calon_santri',
            'is_active' => true
        ]);
    }
}
