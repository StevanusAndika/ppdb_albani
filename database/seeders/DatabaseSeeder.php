<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LandingContentSeeder::class,
            ProgramUnggulanSeeder::class,
        ]);

        // Anda juga bisa menambahkan seeder lain di sini jika ada
        // $this->call([
        //     OtherSeeder::class,
        // ]);

    }
}
