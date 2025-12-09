<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class LandingContentSeeder extends Seeder
{
    public function run()
    {
        // 1. Section Hero
        LandingContent::updateOrCreate(['key' => 'hero'], [
            'payload' => [
                'title' => 'PPDB Pondok Pesantren Bani Syahid 2025',
                'tagline' => 'Membentuk Generasi Qurani yang Berakhlak Mulia',
                'whatsapp' => '628123456789',
                'image' => null // Nanti diisi path gambar
            ]
        ]);

        // 2. Section Visi Misi
        LandingContent::updateOrCreate(['key' => 'visi_misi'], [
            'payload' => [
                'visi' => 'Menjadi pusat pendidikan Al-Qur\'an yang unggul dalam pembacaan, penghafalan, dan pemahaman Al-Qur\'an, dengan tetap istiqamah di atas manhaj dan warisan kelimuan para guru kami.',
                'misi' => ['Menanamkan keointaan terhadap Al-Qur\'an sejak dini melalui pembelajaran yang menyeturuh: tilawah, tahtida, dan tafsir.',
                            'Membina santri menjadi haftar dan haftarah yang mutqin (kokoh hafalamya) dan berakhlak Qur\'ani.', 
                            'Mengajarkan metode pembacaan Al-Qur\'an yang sesuai dengan tapivid dan qira\'at yang mu\'tabarah.',
                            'Mengembangkan sistem pendidikan yang berlandaskan pada nilai-nilai warisan guru dan ulama terdanulu.',
                            'Menumbuhkan semangat dakwah dan pengabdian di tengah masyarakat melalui nilai-nilai Al-Qur\'an.',
                            'Menjaga dan melestarikan sanad kelimuan dalam pembelajaran Al-Qur\'an dan ilmu-ilmu keislaman lainnya.'] // Array strings
            ]
        ]);

        // 3. Section Program (Array of Objects)
        LandingContent::updateOrCreate(['key' => 'programs'], [
            'payload' => [] // Nanti diisi via CMS
        ]);
        
        // 4. Section Alur & Dokumen (Bisa ditambahkan pola serupa)
    }
}
