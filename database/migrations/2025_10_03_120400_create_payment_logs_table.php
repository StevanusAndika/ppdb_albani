<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id(); // ID log (kunci utama)
            $table->foreignId('payment_id')->constrained()->onDelete('cascade'); // ID pembayaran
            $table->enum('jenis_log', ['request', 'callback', 'notification']); // Jenis log
            $table->json('data_payload'); // Data payload (format JSON)
            $table->string('status_respons'); // Status respons
            $table->timestamp('waktu_pembuatan_log')->useCurrent(); // Waktu pembuatan log
            $table->timestamps();

            $table->index('payment_id');
            $table->index('jenis_log');
            $table->index('waktu_pembuatan_log');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
