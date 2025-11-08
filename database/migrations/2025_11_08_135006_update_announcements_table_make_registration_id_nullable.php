<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Hapus foreign key constraint sementara
            $table->dropForeign(['registration_id']);

            // Ubah column menjadi nullable
            $table->foreignId('registration_id')->nullable()->change();

            // Tambahkan kembali foreign key dengan nullable
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->foreignId('registration_id')->nullable(false)->change();
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }
};
