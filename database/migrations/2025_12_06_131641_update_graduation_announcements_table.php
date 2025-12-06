<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduation_announcements', function (Blueprint $table) {
            // Ubah registration_id menjadi nullable untuk summary/bulk messages
            $table->foreignId('registration_id')->nullable()->change();

            // Tambah column type untuk membedakan individual/bulk
            $table->enum('announcement_type', ['individual', 'bulk', 'summary'])->default('individual')->after('registration_id');

            // Tambah column untuk menyimpan jumlah penerima
            $table->integer('recipient_count')->default(1)->after('recipients');
        });
    }

    public function down(): void
    {
        Schema::table('graduation_announcements', function (Blueprint $table) {
            $table->dropColumn(['announcement_type', 'recipient_count']);
            $table->foreignId('registration_id')->nullable(false)->change();
        });
    }
};
