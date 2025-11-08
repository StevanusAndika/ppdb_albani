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

            // FAQ
            $table->json('faq_data')->nullable();

            // Kegiatan Pesantren
            $table->json('kegiatan_pesantren_data')->nullable();

            $table->timestamps();
        });

        // Insert default data
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
                ]
            ]),
            'faq_data' => json_encode([
                [
                    'pertanyaan' => 'Apa saja persyaratan pendaftaran?',
                    'jawaban' => 'Persyaratan pendaftaran meliputi dokumen-dokumen yang diperlukan.'
                ]
            ]),
            'kegiatan_pesantren_data' => json_encode([
                [
                    'waktu' => 'Bada Shubuh',
                    'kegiatan' => ['Tadarus Bersama', 'Ziyadah hafalan']
                ],
                [
                    'waktu' => 'Bada Shalat Dhuna',
                    'kegiatan' => ['Sarapan', 'Piket sesuai jadwal', 'Persiapan shalat dhuna']
                ],
                [
                    'waktu' => '08:30 - 09:00',
                    'kegiatan' => ['Shalat dhuna bersama']
                ],
                [
                    'waktu' => '09:00 - 12:00',
                    'kegiatan' => ['Pengajian sesuai kelas']
                ],
                [
                    'waktu' => '12:00 - 15:00',
                    'kegiatan' => ['Shalat berjamaan dzuhur', 'Istirahat (bisa digunakan menghafal)']
                ],
                [
                    'waktu' => '15:00 - 17:00',
                    'kegiatan' => ['Shalat ashar berjamaan', 'Kajian tilawah / murajaah', 'Piket sesuai jadwal', 'Persiapan shalat maghrib']
                ],
                [
                    'waktu' => '17:00 - 19:00',
                    'kegiatan' => ['Shalat maghrib berjamaan', 'Kajian tajwid / qira\'ah Sab\'ah / tilawah']
                ],
                [
                    'waktu' => '19:00 - 21:30',
                    'kegiatan' => ['Shalat isya berjamaan', 'Jam wajib menghafal', 'Membaca Al Mulk Bersama']
                ],
                [
                    'waktu' => '21:30 - 04:00',
                    'kegiatan' => ['Istirahat', 'Shalat tahajud']
                ],
                [
                    'waktu' => '04:00 - Bada Shubuh',
                    'kegiatan' => ['Shalat subuh berjamaan']
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('content_settings');
    }
};
