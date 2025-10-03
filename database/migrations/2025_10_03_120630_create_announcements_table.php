<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); // ID pengumuman (kunci utama)
            $table->string('judul_pengumuman'); // Judul pengumuman
            $table->text('isi_pengumuman'); // Isi pengumuman
            $table->foreignId('penulis_id')->constrained('users')->onDelete('cascade'); // ID penulis
            $table->enum('target_audience', [
                'semua',
                'calon_santri',
                'lulus',
                'tidak_lulus',
                'admin',
                'panitia'
            ])->default('semua'); // Target audience
            $table->boolean('status_publikasi')->default(false); // Status publikasi
            $table->timestamp('waktu_publikasi')->nullable(); // Waktu publikasi
            $table->timestamps();

            $table->index('penulis_id');
            $table->index('target_audience');
            $table->index('status_publikasi');
            $table->index('waktu_publikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
