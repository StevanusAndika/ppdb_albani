<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Seeder yang dijalankan:
     * - UserSeeder: Data user default (admin, dll)
     * - LandingContentSeeder: Data konten landing page (hero, visi-misi, program, dll)
     * 
     * Cara menjalankan:
     * php artisan db:seed
     * atau untuk fresh migration + seed:
     * php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        $this->command->info('🌱 Memulai seeding database...');
        $this->command->info('');
        
        $this->call([
            UserSeeder::class,
            LandingContentSeeder::class,
        ]);

        // Anda juga bisa menambahkan seeder lain di sini jika ada
        // $this->call([
        //     OtherSeeder::class,
        // ]);

        $this->command->info('');
        $this->command->info('✅ Semua seeder berhasil dijalankan!');
    }
}
