<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada (opsional, hapus baris ini jika ingin tetap ada data lama)
        // User::query()->delete();

        // Data Admin (3 user)
        $admins = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin.utama@example.com',
                'password' => bcrypt('Admin123!'),
                'role' => 'admin',
                'phone_number' => '081234567890',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Super Admin',
                'email' => 'super.admin@example.com',
                'password' => bcrypt('SuperAdmin123!'),
                'role' => 'admin',
                'phone_number' => '081398765432',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Administrator',
                'email' => 'administrator@example.com',
                'password' => bcrypt('AdminPass123!'),
                'role' => 'admin',
                'phone_number' => '085712345678',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
        ];

        // Data Calon Santri (7 user)
        $calonSantri = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567891',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567892',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Muhammad Ali',
                'email' => 'muhammad.ali@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567893',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Fatimah Azzahra',
                'email' => 'fatimah.azzahra@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567894',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Abdul Rahman',
                'email' => 'abdul.rahman@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567895',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Nurul Hikmah',
                'email' => 'nurul.hikmah@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567896',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('Santri123!'),
                'role' => 'calon_santri',
                'phone_number' => '081234567897',
                'is_active' => true,
                'login_attempts' => 0,
                'email_verified_at' => Carbon::now(),
            ],
        ];

        // Insert data admin dengan cek duplikat
        foreach ($admins as $adminData) {
            // Cek apakah email sudah ada
            $existingAdmin = User::where('email', $adminData['email'])->first();

            if (!$existingAdmin) {
                User::create($adminData);
                $this->command->info("Admin created: {$adminData['email']}");
            } else {
                $this->command->warn("Admin already exists: {$adminData['email']}");
            }
        }

        // Insert data calon santri dengan cek duplikat
        foreach ($calonSantri as $santriData) {
            // Cek apakah email sudah ada
            $existingSantri = User::where('email', $santriData['email'])->first();

            if (!$existingSantri) {
                User::create($santriData);
                $this->command->info("Calon Santri created: {$santriData['email']}");
            } else {
                $this->command->warn("Calon Santri already exists: {$santriData['email']}");
            }
        }

        $this->command->info('UserSeeder completed successfully!');
    }
}
