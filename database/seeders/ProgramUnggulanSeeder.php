<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramUnggulan;

class ProgramUnggulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'nama_program' => 'Program Regular',
                'potongan' => 0,
                'perlu_verifikasi' => 'no',
                'dokumen_tambahan' => []
            ],
            [
                'nama_program' => 'Program Tahfidz',
                'potongan' => 10,
                'perlu_verifikasi' => 'yes',
                'dokumen_tambahan' => ['sertifikat_hafiz', 'surat_rekomendasi']
            ],
            [
                'nama_program' => 'Program Intensif',
                'potongan' => 5,
                'perlu_verifikasi' => 'no',
                'dokumen_tambahan' => ['sku']
            ],
            [
                'nama_program' => 'Program Beasiswa',
                'potongan' => 50,
                'perlu_verifikasi' => 'yes',
                'dokumen_tambahan' => ['dokumen_kesehatan', 'surat_rekomendasi']
            ],
        ];

        foreach ($programs as $program) {
            ProgramUnggulan::create($program);
        }
    }
}
