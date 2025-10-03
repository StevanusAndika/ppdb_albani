<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_details', function (Blueprint $table) {
            $table->id(); // ID detail (kunci utama)
            $table->foreignId('registration_id')->constrained()->onDelete('cascade'); // ID pendaftaran
            $table->string('nama_lengkap'); // Nama lengkap calon santri
            $table->string('tempat_lahir'); // Tempat lahir
            $table->date('tanggal_lahir'); // Tanggal lahir
            $table->enum('jenis_kelamin', ['L', 'P']); // Jenis kelamin
            $table->string('agama'); // Agama
            $table->text('alamat_lengkap'); // Alamat lengkap
            $table->string('sekolah_asal'); // Sekolah asal
            $table->string('nama_ayah'); // Nama ayah
            $table->string('nama_ibu'); // Nama ibu
            $table->string('telepon_orang_tua'); // Telepon orang tua
            $table->string('pekerjaan_orang_tua'); // Pekerjaan orang tua
            $table->timestamps();

            $table->index('registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_details');
    }
};
