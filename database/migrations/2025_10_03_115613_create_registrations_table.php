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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id(); // ID pendaftaran (kunci utama)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID pengguna
            $table->foreignId('program_id')->constrained()->onDelete('cascade'); // ID program
            $table->string('nomor_pendaftaran')->unique(); // Nomor pendaftaran unik
            // $table->enum('status', [
            //     'draft',
            //     'submitted',
            //     'verified',
            //     'rejected',
            //     'accepted',
            //     'passed',
            //     'failed'
            // ])->default('draft'); // Status pendaftaran
            $table->date('tanggal_pendaftaran'); // Tanggal pendaftaran
            $table->timestamps(); // Stempel waktu

            // Index untuk optimisasi query
            $table->index('nomor_pendaftaran');
            $table->index('status');
            $table->index('tanggal_pendaftaran');
            $table->index(['user_id', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
