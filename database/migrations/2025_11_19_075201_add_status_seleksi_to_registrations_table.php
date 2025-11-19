<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->enum('status_seleksi', [
                'sudah_mengikuti_seleksi',
                'belum_mengikuti_seleksi'
            ])->default('belum_mengikuti_seleksi')->after('status_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('status_seleksi');
        });
    }
};
