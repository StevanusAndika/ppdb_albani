<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index()
    {
        // Data beasiswa statis berdasarkan gambar
        $beasiswaData = [
            'judul' => 'PROGRAM BEASISWA',
            'subjudul' => 'PONDOK PESANTREN AL-QUR\'AN BANI SYAHID',

            'programs' => [
                [
                    'nama' => 'BEASISWA CENDEKIA QURANI',
                    'deskripsi' => 'Program ini dikhususkan bagi Calon Saniri berprestasi dalam Bidang Al-Qur\'an, untuk Jenjang MIs & MA',
                    'syarat' => [
                        'Hafal 10 Juz Al-Qur\'an, atau',
                        'Sertifikat Prestasi MTQ atau lainnya',
                        'Saniri & Wali Saniri mengikuti Tes Komitmen dan Integritas'
                    ]
                ],
                [
                    'nama' => 'BEASISWA PEMBERDAYAAN & KEMANDIRIAN',
                    'deskripsi' => 'Program ini dikhususkan untuk Calon Saniri tidak mampu',
                    'syarat' => [
                        'Surat Keterangan Tidak Mampu dari Pemerintah Setempat',
                        'Saniri dan Wali Saniri mengikuti Tes Komitmen dan Integritas'
                    ]
                ]
            ],

            'kontak' => [
                [
                    'label' => 'PPA BANI SYAHID:',
                    'nomor_putra' => '0895-1027-9293 (Pendaftaran Putra)',
                    'nomor_putri' => '0821-8395-3533 (Pendaftaran Putri)'
                ]
            ]
        ];

        return view('beasiswa', compact('beasiswaData'));
    }
}
