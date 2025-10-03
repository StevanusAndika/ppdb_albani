<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // ID pembayaran (kunci utama)
            $table->foreignId('registration_id')->constrained()->onDelete('cascade'); // ID pendaftaran
            $table->string('nomor_invoice')->unique(); // Nomor invoice unik
            $table->decimal('jumlah_pembayaran', 15, 2); // Jumlah pembayaran
            $table->string('metode_pembayaran'); // Metode pembayaran
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('gateway_pembayaran'); // Gateway pembayaran
            $table->string('id_transaksi_gateway')->nullable(); // ID transaksi gateway
            $table->timestamp('waktu_pembayaran_sukses')->nullable(); // Waktu pembayaran sukses
            $table->timestamp('waktu_kadaluarsa')->nullable(); // Waktu kadaluarsa pembayaran
            $table->timestamps();

            $table->index('registration_id');
            $table->index('nomor_invoice');
            $table->index('status');
            $table->index('id_transaksi_gateway');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
