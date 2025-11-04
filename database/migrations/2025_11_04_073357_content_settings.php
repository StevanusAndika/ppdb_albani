<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_settings', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tagline')->nullable();

            // Visi Misi
            $table->string('visi_judul')->nullable();
            $table->text('visi_deskripsi')->nullable();
            $table->string('misi_judul')->nullable();
            $table->text('misi_deskripsi')->nullable();

            // Program Unggulan - dalam format JSON untuk multiple program
            $table->json('program_unggulan_data')->nullable();

            // Alur Pendaftaran
            $table->string('alur_pendaftaran_judul')->nullable();
            $table->text('alur_pendaftaran_deskripsi')->nullable();

            // Persyaratan Dokumen
            $table->string('persyaratan_dokumen_judul')->nullable();
            $table->text('persyaratan_dokumen_deskripsi')->nullable();

            // File paths untuk gambar dokumen
            $table->string('akte_path')->nullable();
            $table->string('formulir_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('kk_path')->nullable();
            $table->string('pasfoto_path')->nullable();

            $table->timestamps();
        });

        // Insert default data dengan program unggulan
        DB::table('content_settings')->insert([
            'judul' => 'PPDB Pondok Pesantren Bani Syahid',
            'tagline' => 'Membentuk Generasi Qurani yang Berakhlak Mulia',
            'visi_judul' => 'Visi Pesantren',
            'misi_judul' => 'Misi Pesantren',
            'program_unggulan_data' => json_encode([
                [
                    'nama' => 'Tahfıdzul Qur\'an',
                    'target' => 'Hafal 30 Juz dalam waktu 3-5 tahun',
                    'metode' => 'Talaqqi dan murajaah harian bersama musyrif/musyrifah',
                    'evaluasi' => 'Setoran harian, tasmi mingguan, dan ujian tahunan'
                ],
                [
                    'nama' => 'Qira\'at Sab\'ah',
                    'target' => 'Menguasai tujuh qira\'at mutawatir sesuai riwayat yang sahih',
                    'metode' => 'Pembelajaran teori dan praktik qira\'at berdasarkan matan "Asy-Syatibiyyah"',
                    'evaluasi' => 'Santri memahami perbedaan qira\'at dan mampu membacanya dengan tepat'
                ],
                [
                    'nama' => 'Nagham',
                    'target' => 'Meningkatkan kualitas bacaan santri dengan irama yang sesuai kaidah tajwid dan nagham',
                    'metode' => 'Jenis Nagham: Bayati, Shoba, Hijaz, Nahawand, Rast, Sika, Jiharka',
                    'evaluasi' => 'Latihan rutin, lomba internal, dan pembinaan untuk Musabaqah Tilawatil Qur\'an (MTQ)'
                ],
                [
                    'nama' => 'Kajian Kitab Ulama Klasik (Turats)',
                    'target' => 'Santri memahami dasar-dasar ilmu Islam dari sumber klasik',
                    'metode' => 'Talaqqi (pengajian langsung) dan diskusi kitab kuning',
                    'evaluasi' => 'Pemahaman dan penguasaan materi kitab turats'
                ]
            ]),
            'alur_pendaftaran_judul' => 'Alur Pendaftaran',
            'persyaratan_dokumen_judul' => 'Persyaratan Dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('content_settings');
    }
};
