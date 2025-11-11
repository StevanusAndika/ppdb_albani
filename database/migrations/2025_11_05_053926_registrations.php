<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('id_pendaftaran')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');

            // Data Pribadi Santri
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->text('alamat_tinggal');
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('kota');
            $table->string('kode_pos', 5);
            $table->string('nis_nisn_nsp')->nullable();
            $table->string('nama_ibu_kandung');
            $table->string('nama_ayah_kandung');
            $table->string('pekerjaan_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('alergi_obat')->nullable();
            $table->decimal('penghasilan_ayah', 15, 2)->nullable();
            $table->decimal('penghasilan_ibu', 15, 2)->nullable();
            $table->string('nomor_telpon_orang_tua');
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu']);
            $table->enum('status_orang_tua', ['lengkap', 'cerai_hidup', 'cerai_mati']);
            $table->enum('status_pernikahan', ['menikah', 'belum_menikah']);
            $table->string('jenjang_pendidikan_terakhir');
            $table->string('nama_sekolah_terakhir');
            $table->text('alamat_sekolah_terakhir');

            // Data Kesehatan
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('kebangsaan', ['WNI', 'WNA'])->default('WNI');
            $table->string('penyakit_kronis')->nullable();

            // Data Wali
            $table->string('nama_wali');
            $table->text('alamat_wali');
            $table->string('rt_wali', 3)->nullable();
            $table->string('rw_wali', 3)->nullable();
            $table->string('kecamatan_wali');
            $table->string('kelurahan_wali');
            $table->string('kota_wali');
            $table->string('kode_pos_wali', 5);
            $table->string('nomor_telpon_wali');

            // Path Dokumen
            $table->string('kartu_keluaga_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('akta_kelahiran_path')->nullable();
            $table->string('pas_foto_path')->nullable();

            // Status Pendaftaran
            $table->enum('status_pendaftaran', [
                'belum_mendaftar',
                'telah_mengisi',
                'telah_dilihat',
                'menunggu_diverifikasi',
                'ditolak',
                'diterima'
            ])->default('belum_mendaftar');

            $table->text('catatan_admin')->nullable();
            $table->timestamp('dilihat_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
