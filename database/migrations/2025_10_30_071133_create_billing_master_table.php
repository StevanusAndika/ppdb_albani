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
        Schema::create('billing_master', function (Blueprint $table) {
            $table->uuid('recid_billing_master')->primary();
            $table->string('billing_name'); // Nama tagihan (misalnya: daftar ulang, spp)
            $table->decimal('amount', 15, 2); // Besaran tagihan dengan skala 2 digit desimal
            $table->integer('status')->default(1); // Status show (1) atau hide (2)
            $table->string('created_by')->nullable();
            $table->string('last_modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_master');
    }
};
