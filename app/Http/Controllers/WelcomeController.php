<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ContentSetting;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\LandingContent;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil paket yang aktif beserta biaya yang aktif
        $packages = Package::with(['prices' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('is_active', true)
        ->get();

        // Ambil pengaturan konten
        $contentSettings = ContentSetting::getSettings();

        // Cek apakah user sudah login
        $user = Auth::user();
        $isLoggedIn = Auth::check();
        $userRole = $isLoggedIn ? $user->role : null;

        // Data statistik pendidikan untuk ditampilkan
        $educationStats = $this->getEducationStatistics();

        // Data program pendidikan untuk section pendidikan
        $programPendidikan = [
            'MTS Bani Syahid' => [
                'icon' => 'fas fa-school',
                'color' => 'bg-blue-500',
                'description' => 'Madrasah Tsanawiyah untuk pendidikan menengah pertama',
                'usia' => '12-15 tahun',
                'duration' => '3 Tahun',
                'features' => ['Kurikulum Diniyah Terpadu', 'Tahfizh Juz 30', 'Penguatan Bahasa Arab', 'Pendidikan Karakter Islami']
            ],
            'MA Bani Syahid' => [
                'icon' => 'fas fa-graduation-cap',
                'color' => 'bg-purple-500',
                'description' => 'Madrasah Aliyah untuk pendidikan menengah atas',
                'usia' => '15-18 tahun',
                'duration' => '3 Tahun',
                'features' => ['Program IPA/IPS', 'Tahfizh Minimal 5 Juz', 'Bahasa Arab & Inggris', 'Persiapan Kuliah']
            ],
            'Takhassus Al-Quran' => [
                'icon' => 'fas fa-book-quran',
                'color' => 'bg-amber-500',
                'description' => 'Program khusus tahfizh dan pendalaman Al-Quran',
                'usia' => 'Minimal 17 tahun',
                'duration' => '3-5 Tahun',
                'features' => ['Target Hafal 30 Juz', 'Qiraat Sab\'ah', 'Ilmu Tajwid Mendalam', 'Sanad Keilmuan']
            ]
        ];

        return view('welcome', compact(
            'packages',
            'contentSettings',
            'isLoggedIn',
            'userRole',
            'educationStats',
            'programPendidikan'
        ));
    }

    /**
     * Get education statistics for display
     */
    private function getEducationStatistics()
    {
        try {
            // Hitung statistik berdasarkan program pendidikan
            $totalRegistrations = Registration::count();

            $programStats = [
                'mts' => Registration::where('program_pendidikan', 'MTS Bani Syahid')->count(),
                'ma' => Registration::where('program_pendidikan', 'MA Bani Syahid')->count(),
                'takhassus' => Registration::where('program_pendidikan', 'Takhassus Al-Quran')->count(),
                'total' => $totalRegistrations
            ];

            // Hitung persentase
            if ($totalRegistrations > 0) {
                $programStats['mts_percentage'] = round(($programStats['mts'] / $totalRegistrations) * 100);
                $programStats['ma_percentage'] = round(($programStats['ma'] / $totalRegistrations) * 100);
                $programStats['takhassus_percentage'] = round(($programStats['takhassus'] / $totalRegistrations) * 100);
            } else {
                $programStats['mts_percentage'] = 0;
                $programStats['ma_percentage'] = 0;
                $programStats['takhassus_percentage'] = 0;
            }

            // Data jenjang pendidikan terakhir
            $jenjangStats = [
                'tk_ra' => Registration::where('jenjang_pendidikan_terakhir', 'TK/RA')->count(),
                'sd_mi' => Registration::where('jenjang_pendidikan_terakhir', 'SD/MI')->count(),
                'smp_mts' => Registration::where('jenjang_pendidikan_terakhir', 'SMP/MTs')->count(),
                'sma_ma' => Registration::where('jenjang_pendidikan_terakhir', 'SMA/MA')->count()
            ];

            return [
                'program_stats' => $programStats,
                'jenjang_stats' => $jenjangStats,
                'total_santri' => $totalRegistrations
            ];
        } catch (\Exception $e) {
            return [
                'program_stats' => ['total' => 0, 'mts' => 0, 'ma' => 0, 'takhassus' => 0],
                'jenjang_stats' => ['tk_ra' => 0, 'sd_mi' => 0, 'smp_mts' => 0, 'sma_ma' => 0],
                'total_santri' => 0
            ];
        }
    }
}
