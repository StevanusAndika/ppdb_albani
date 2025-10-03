<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id(); // ID dokumen (kunci utama)
            $table->foreignId('registration_id')->constrained()->onDelete('cascade'); // ID pendaftaran
            $table->enum('jenis_dokumen', [
                'foto',
                'ktp',
                'kk',
                'akta_kelahiran',
                'rapor',
                'lainnya'
            ]); // Jenis dokumen
            $table->string('nama_file_asli'); // Nama file asli
            $table->string('path_penyimpanan'); // Path penyimpanan file
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable(); // Alasan penolakan
            $table->timestamp('waktu_upload')->useCurrent(); // Waktu upload
            $table->timestamps();

            $table->index('registration_id');
            $table->index('jenis_dokumen');
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
