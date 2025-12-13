<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

/**
 * Seeder untuk Landing Content
 * 
 * Seeder ini akan mengisi database dengan data default untuk:
 * - Hero Section
 * - Visi & Misi
 * - Program Pendidikan
 * - Program Unggulan
 * - Kegiatan Pesantren
 * - FAQ
 * - Alur Pendaftaran
 * - Biaya
 * - Persyaratan Dokumen
 * - Brosur
 * - General Settings (Navbar, Footer, Button, Contact)
 * 
 * Untuk menjalankan seeder ini:
 * php artisan db:seed --class=LandingContentSeeder
 * atau
 * php artisan migrate:fresh --seed
 */
class LandingContentSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Memulai seeding landing content...');
        // 1. Hero Section
        LandingContent::updateOrCreate(['key' => 'hero'], [
            'payload' => [
                'title' => 'PPDB Pondok Pesantren Bani Syahid 2025',
                'tagline' => 'Membentuk Generasi Qurani yang Berakhlak Mulia',
                'description' => 'Sistem Penerimaan Peserta Didik Baru yang modern, mudah, dan terpercaya. Untuk Masa Depan Yang Lebih Baik',
                'whatsapp' => '6287748115931',
                'image' => null // Nanti diisi path gambar jika ada
            ]
        ]);

        // 2. Visi & Misi Section
        LandingContent::updateOrCreate(['key' => 'visi_misi'], [
            'payload' => [
                'visi' => 'Menjadi pusat pendidikan Al-Qur\'an yang unggul dalam pembacaan, penghafalan, dan pemahaman Al-Qur\'an, dengan tetap istiqamah di atas manhaj dan warisan kelimuan para guru kami.',
                'misi' => [
                    'Menanamkan kecintaan terhadap Al-Qur\'an sejak dini melalui pembelajaran yang menyeluruh: tilawah, tahfidz, dan tafsir.',
                    'Membina santri menjadi hafidz dan hafidzah yang mutqin (kokoh hafalannya) dan berakhlak Qur\'ani.',
                    'Mengajarkan metode pembacaan Al-Qur\'an yang sesuai dengan tajwid dan qira\'at yang mu\'tabarah.',
                    'Mengembangkan sistem pendidikan yang berlandaskan pada nilai-nilai warisan guru dan ulama terdahulu.',
                    'Menumbuhkan semangat dakwah dan pengabdian di tengah masyarakat melalui nilai-nilai Al-Qur\'an.',
                    'Menjaga dan melestarikan sanad kelimuan dalam pembelajaran Al-Qur\'an dan ilmu-ilmu keislaman lainnya.'
                ]
            ]
        ]);

        // 3. Programs Section (Program Pendidikan)
        LandingContent::updateOrCreate(['key' => 'programs'], [
            'payload' => [
                [
                    'title' => 'MTS Bani Syahid',
                    'description' => 'Madrasah Tsanawiyah untuk pendidikan menengah pertama dengan kurikulum terpadu',
                    'advantages' => ['Kurikulum Diniyah Terpadu', 'Tahfizh Juz 30', 'Penguatan Bahasa Arab', 'Pendidikan Karakter Islami'],
                    'image' => null
                ],
                [
                    'title' => 'MA Bani Syahid',
                    'description' => 'Madrasah Aliyah untuk pendidikan menengah atas dengan program unggulan',
                    'advantages' => ['Program IPA/IPS', 'Tahfizh Minimal 5 Juz', 'Bahasa Arab & Inggris', 'Persiapan Kuliah'],
                    'image' => null
                ],
                [
                    'title' => 'Takhassus Al-Quran',
                    'description' => 'Program khusus tahfizh dan pendalaman Al-Quran dengan target hafal 30 juz',
                    'advantages' => ['Target Hafal 30 Juz', 'Qiraat Sab\'ah', 'Ilmu Tajwid Mendalam', 'Sanad Keilmuan'],
                    'image' => null
                ]
            ]
        ]);

        // 4. Program Unggulan Section
        LandingContent::updateOrCreate(['key' => 'program_unggulan'], [
            'payload' => [
                [
                    'nama' => 'Tahfidzul Qur\'an',
                    'deskripsi' => 'Program unggulan untuk menghafal Al-Qur\'an dengan metode yang teruji',
                    'target' => 'Hafal 30 Juz dalam waktu 3-5 tahun',
                    'metode' => 'Talaqqi dan muraja\'ah harian bersama mu\'allim/ah',
                    'evaluasi' => 'Setoran harian, tasmî\' mingguan, dan ujian tahunan'
                ],
                [
                    'nama' => 'Qiraat Sab\'ah',
                    'deskripsi' => 'Menguasai tujuh qira\'at mutawatir sesuai riwayat yang sahih',
                    'target' => 'Menguasai tujuh qira\'at mutawatir sesuai riwayat yang sahih',
                    'metode' => 'Teori dan praktik qira\'at berdasarkan matan "Al-Syatibiyyah"',
                    'evaluasi' => 'Santri memahami perbedaan qiraat dan mampu membacanya dengan tepat'
                ],
                [
                    'nama' => 'Nagham',
                    'deskripsi' => 'Meningkatkan kualitas bacaan santri dengan irama yang sesuai kaidah tajwid',
                    'target' => 'Meningkatkan kualitas bacaan santri dengan irama yang sesuai kaidah tajwid dan nagham',
                    'metode' => 'Latihan rutin, lomba internal, dan pembinaan untuk Musabaqah Tilawatil Qur\'an (MTQ)',
                    'evaluasi' => 'Penguasaan berbagai jenis nagham: Bayati, Shoba, Hijaz, Mahawan, Bast, Sika, Jiranka'
                ],
                [
                    'nama' => 'Kajian Kitab Ulama Klasik (Turats)',
                    'deskripsi' => 'Santri memahami dasar-dasar ilmu Islam dari sumber klasik',
                    'target' => 'Santri memahami dasar-dasar ilmu Islam dari sumber klasik',
                    'metode' => 'Talaqqi (pengajian langsung) dan diskusi kitab kuning',
                    'evaluasi' => 'Pemahaman kitab turats dan aplikasi dalam kehidupan sehari-hari'
                ]
            ]
        ]);

        // 5. Kegiatan Pesantren Section
        LandingContent::updateOrCreate(['key' => 'kegiatan_pesantren'], [
            'payload' => [
                [
                    'waktu' => '04:00 - 05:30',
                    'kegiatan' => ['Qiyamul Lail', 'Tahajud', 'Tadarus Al-Qur\'an']
                ],
                [
                    'waktu' => '05:30 - 07:00',
                    'kegiatan' => ['Shalat Subuh Berjamaah', 'Kajian Subuh', 'Sarapan']
                ],
                [
                    'waktu' => '07:00 - 12:00',
                    'kegiatan' => ['Pembelajaran Diniyah', 'Tahfizh Al-Qur\'an', 'Bahasa Arab']
                ],
                [
                    'waktu' => '12:00 - 13:00',
                    'kegiatan' => ['Shalat Dzuhur Berjamaah', 'Makan Siang', 'Istirahat']
                ],
                [
                    'waktu' => '13:00 - 17:00',
                    'kegiatan' => ['Pembelajaran Formal', 'Ekstrakurikuler', 'Olahraga']
                ],
                [
                    'waktu' => '17:00 - 19:00',
                    'kegiatan' => ['Shalat Maghrib Berjamaah', 'Tadarus Al-Qur\'an', 'Makan Malam']
                ],
                [
                    'waktu' => '19:00 - 21:00',
                    'kegiatan' => ['Shalat Isya Berjamaah', 'Kajian Malam', 'Muraja\'ah Hafalan']
                ],
                [
                    'waktu' => '21:00 - 04:00',
                    'kegiatan' => ['Istirahat Malam']
                ]
            ]
        ]);

        // 6. FAQ Section
        LandingContent::updateOrCreate(['key' => 'faq'], [
            'payload' => [
                [
                    'pertanyaan' => 'Apa saja persyaratan untuk mendaftar?',
                    'jawaban' => 'Persyaratan pendaftaran meliputi: Fotokopi KTP/KK, Pas Foto 3x4, Ijazah/SKL terakhir, Akte Kelahiran, dan dokumen pendukung lainnya sesuai program yang dipilih.'
                ],
                [
                    'pertanyaan' => 'Berapa biaya pendaftaran dan pendidikan?',
                    'jawaban' => 'Biaya pendaftaran dan pendidikan bervariasi sesuai program yang dipilih. Silakan hubungi admin untuk informasi lengkap mengenai biaya dan paket yang tersedia.'
                ],
                [
                    'pertanyaan' => 'Apakah ada program beasiswa?',
                    'jawaban' => 'Ya, kami menyediakan program beasiswa untuk santri berprestasi dan yang kurang mampu. Informasi lebih lanjut dapat dilihat di halaman info beasiswa atau hubungi admin.'
                ],
                [
                    'pertanyaan' => 'Bagaimana sistem pembelajaran di pesantren?',
                    'jawaban' => 'Sistem pembelajaran menggunakan kurikulum terpadu antara pendidikan formal (MTS/MA) dan pendidikan diniyah (Tahfizh, Bahasa Arab, Fiqh, dll) dengan metode talaqqi dan klasikal.'
                ],
                [
                    'pertanyaan' => 'Apakah santri boleh pulang setiap hari?',
                    'jawaban' => 'Tidak, sistem pendidikan kami menggunakan sistem asrama (boarding school) untuk membentuk karakter dan kedisiplinan santri. Santri diperbolehkan pulang sesuai jadwal yang telah ditentukan.'
                ],
                [
                    'pertanyaan' => 'Apa keunggulan pesantren ini?',
                    'jawaban' => 'Keunggulan pesantren kami meliputi: Program tahfizh yang terstruktur, pengajaran qira\'at sab\'ah, kajian kitab turats, sanad keilmuan yang jelas, dan pembinaan akhlak Qur\'ani.'
                ]
            ]
        ]);

        // 7. Alur Pendaftaran Section
        LandingContent::updateOrCreate(['key' => 'alur_pendaftaran'], [
            'payload' => [
                [
                    'judul' => 'Registrasi Online',
                    'deskripsi' => 'Daftar melalui website dengan mengisi formulir pendaftaran secara online. Pastikan semua data yang diisi sudah benar dan lengkap.'
                ],
                [
                    'judul' => 'Upload Dokumen',
                    'deskripsi' => 'Upload semua dokumen persyaratan yang diperlukan melalui dashboard pendaftaran. Pastikan dokumen jelas dan dapat dibaca.'
                ],
                [
                    'judul' => 'Verifikasi Dokumen',
                    'deskripsi' => 'Tim admin akan melakukan verifikasi dokumen yang telah diupload. Proses ini membutuhkan waktu 1-3 hari kerja.'
                ],
                [
                    'judul' => 'Pembayaran',
                    'deskripsi' => 'Setelah dokumen diverifikasi, lakukan pembayaran biaya pendaftaran sesuai dengan paket yang dipilih melalui metode pembayaran yang tersedia.'
                ],
                [
                    'judul' => 'Konfirmasi Pembayaran',
                    'deskripsi' => 'Upload bukti pembayaran dan tunggu konfirmasi dari admin. Setelah pembayaran dikonfirmasi, status pendaftaran akan berubah.'
                ],
                [
                    'judul' => 'Seleksi & Pengumuman',
                    'deskripsi' => 'Ikuti proses seleksi yang telah ditentukan. Pengumuman hasil seleksi akan diumumkan melalui website dan media sosial resmi pesantren.'
                ]
            ]
        ]);

        // 8. Biaya Section
        LandingContent::updateOrCreate(['key' => 'biaya'], [
            'payload' => [
                'text' => 'Biaya pendidikan di Pondok Pesantren Bani Syahid disesuaikan dengan program yang dipilih. Kami menyediakan berbagai paket dengan fasilitas lengkap. Untuk informasi detail mengenai biaya pendaftaran, SPP bulanan, dan biaya lainnya, silakan hubungi admin atau lihat halaman paket dan harga. Kami juga menyediakan program beasiswa untuk santri berprestasi dan yang kurang mampu.'
            ]
        ]);

        // 9. Persyaratan Dokumen Section
        LandingContent::updateOrCreate(['key' => 'persyaratan_dokumen'], [
            'payload' => [
                [
                    'title' => 'Formulir Pendaftaran',
                    'note' => 'Formulir dapat diunduh dari website atau diisi secara online',
                    'img' => null
                ],
                [
                    'title' => 'Pas Foto 3x4',
                    'note' => 'Pas foto terbaru dengan latar belakang merah, 2 lembar',
                    'img' => null
                ],
                [
                    'title' => 'Akte Kelahiran',
                    'note' => 'Fotokopi akte kelahiran yang telah dilegalisir',
                    'img' => null
                ],
                [
                    'title' => 'Kartu Keluarga (KK)',
                    'note' => 'Fotokopi kartu keluarga yang masih berlaku',
                    'img' => null
                ],
                [
                    'title' => 'Ijazah/SKL',
                    'note' => 'Fotokopi ijazah atau SKL terakhir yang telah dilegalisir',
                    'img' => null
                ]
            ]
        ]);

        // 10. Brosur Section
        LandingContent::updateOrCreate(['key' => 'brosur'], [
            'payload' => [
                'file' => null,
                'filename' => 'brosur.pdf'
            ]
        ]);

        // 11. General Settings Section
        LandingContent::updateOrCreate(['key' => 'general'], [
            'payload' => [
                // Navbar Settings
                'navbar_logo' => '|| Ponpes Bani Sahid',
                'navbar_menu' => [
                    'Beranda',
                    'Visi & Misi',
                    'Program',
                    'Kegiatan',
                    'Alur Pendaftaran',
                    'Biaya',
                    'Persyaratan',
                    'FAQ',
                    'Kontak'
                ],
                
                // Button Settings
                'btn_download_brosur' => 'Download Brosur',
                'btn_whatsapp' => 'Tanya via WhatsApp',
                'btn_dashboard_admin' => 'Dashboard Admin',
                'btn_dashboard_santri' => 'Dashboard Santri',
                'btn_login' => 'Login',
                'btn_logout' => 'Logout',
                
                // Footer Settings
                'footer_menu1_title' => 'Tentang Kami',
                'footer_menu1_links' => [
                    'Visi & Misi',
                    'info Beasiswa'
                ],
                
                'footer_menu2_title' => 'Program',
                'footer_menu2_links' => [
                    'Tahfidz Al-Qur\'an'
                ],
                
                'footer_menu3_title' => 'Informasi',
                'footer_menu3_links' => [
                    'Alur Pendaftaran',
                    'Biaya Pendidikan',
                    'Persyaratan',
                    'FAQ'
                ],
                
                'footer_menu4_title' => 'Kontak',
                'footer_menu4_links' => [
                    'Alamat',
                    'WhatsApp Developers',
                    'WhatsApp Admin PPDB Putra',
                    'WhatsApp Admin PPDB Putri',
                    'Sosial Media'
                ],
                
                // Contact Info Settings
                'wa_developer' => '6287748115931',
                'wa_admin_putra' => '6289510279293',
                'wa_admin_putri' => '6282183953533',
                'google_maps_url' => 'https://www.google.com/maps/place/Pondok+Pesantren+Al-Qur\'an+Bani+Syahid/@-6.3676771,106.8696904,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ed654ce6786b:0x1019880ca4f9403b!8m2!3d-6.3676824!4d106.8722707!16s%2Fg%2F11f6m9qmmr?hl=id',
                'social_media_url' => 'https://banisyahid.bio.link/',
                'instagram_url' => 'https://www.instagram.com/ponpesalquranbanisyahid_/'
            ]
        ]);

        $this->command->info('✓ Hero Section');
        $this->command->info('✓ Visi & Misi');
        $this->command->info('✓ Program Pendidikan');
        $this->command->info('✓ Program Unggulan');
        $this->command->info('✓ Kegiatan Pesantren');
        $this->command->info('✓ FAQ');
        $this->command->info('✓ Alur Pendaftaran');
        $this->command->info('✓ Biaya');
        $this->command->info('✓ Persyaratan Dokumen');
        $this->command->info('✓ Brosur');
        $this->command->info('✓ General Settings');
        $this->command->info('');
        $this->command->info('✅ Landing content seeder berhasil dijalankan!');
        $this->command->info('📝 Semua data default telah diisi ke database.');
    }
}
