<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id(); // ID program (kunci utama)
            $table->string('nama_program'); // Nama program (Tahfidz, Tahsin, dll)
            $table->text('deskripsi')->nullable(); // Deskripsi program
            $table->integer('kuota_penerimaan'); // Kuota penerimaan
            $table->decimal('biaya_pendaftaran', 15, 2); // Biaya pendaftaran
            $table->string('durasi_program'); // Durasi program
            $table->boolean('status_aktif')->default(true); // Status aktif/tidak
            $table->timestamps(); // Stempel waktu (created_at & updated_at)

            // Index untuk optimisasi query
            $table->index('status_aktif');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
