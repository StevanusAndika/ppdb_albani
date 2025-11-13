<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotas', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 9)->unique(); // Format: 2024-2025
            $table->integer('kuota')->default(0);
            $table->integer('terpakai')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotas');
    }
};
