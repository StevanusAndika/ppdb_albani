<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_scores', function (Blueprint $table) {
            $table->id(); // ID nilai (kunci utama)
            $table->foreignId('registration_id')->constrained()->onDelete('cascade'); // ID pendaftaran
            $table->enum('jenis_tes', ['tulis', 'wawancara', 'baca_quran']); // Jenis tes
            $table->decimal('nilai_tes', 5, 2); // Nilai tes
            $table->foreignId('penguji_id')->constrained('users')->onDelete('cascade'); // ID penguji
            $table->text('catatan')->nullable(); // Catatan dari penguji
            $table->date('tanggal_tes'); // Tanggal tes
            $table->timestamps();

            $table->index('registration_id');
            $table->index('jenis_tes');
            $table->index('penguji_id');
            $table->index('tanggal_tes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_scores');
    }
};
