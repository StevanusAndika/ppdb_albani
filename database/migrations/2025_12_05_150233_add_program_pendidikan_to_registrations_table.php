<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->enum('program_pendidikan', ['MTS Bani Syahid', 'MA Bani Syahid', 'Takhassus Al-Quran'])
                  ->nullable()
                  ->after('jenjang_pendidikan_terakhir');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('program_pendidikan');
        });
    }
};
