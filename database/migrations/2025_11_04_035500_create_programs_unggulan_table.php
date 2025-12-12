<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs_unggulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program')->unique();
            $table->decimal('potongan', 5, 2)->comment('Discount percentage (0-100)');
            $table->enum('perlu_verifikasi', ['yes', 'no'])->default('no');
            $table->json('dokumen_tambahan')->nullable()->comment('Additional required documents as JSON array');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs_unggulan');
    }
};
