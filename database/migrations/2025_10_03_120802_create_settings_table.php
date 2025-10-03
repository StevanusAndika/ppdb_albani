<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); // ID pengaturan (kunci utama)
            $table->string('kunci_pengaturan')->unique(); // Kunci pengaturan
            $table->text('nilai_pengaturan')->nullable(); // Nilai pengaturan
            $table->text('deskripsi_pengaturan')->nullable(); // Deskripsi pengaturan
            $table->foreignId('diperbarui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('kunci_pengaturan');
            $table->index('diperbarui_oleh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
