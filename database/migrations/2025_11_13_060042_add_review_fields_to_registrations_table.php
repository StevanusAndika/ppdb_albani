<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Hanya tambahkan field yang belum ada
            if (!Schema::hasColumn('registrations', 'ditolak_pada')) {
                $table->timestamp('ditolak_pada')->nullable()->after('dilihat_pada');
            }

            if (!Schema::hasColumn('registrations', 'diperbarui_setelah_ditolak')) {
                $table->boolean('diperbarui_setelah_ditolak')->default(false)->after('ditolak_pada');
            }

            // Cek dulu apakah field program_unggulan_id sudah ada
            if (!Schema::hasColumn('registrations', 'program_unggulan_id')) {
                $table->string('program_unggulan_id')->nullable()->after('nik');
            }

            // Ubah enum status_pendaftaran untuk menambah status baru
            // Hanya jika belum ada status 'perlu_review'
            try {
                DB::statement("ALTER TABLE registrations MODIFY COLUMN status_pendaftaran ENUM('belum_mendaftar', 'telah_mengisi', 'telah_dilihat', 'menunggu_diverifikasi', 'ditolak', 'diterima', 'perlu_review') DEFAULT 'belum_mendaftar'");
            } catch (\Exception $e) {
                // Jika sudah ada, skip saja
                \Log::info('Status perlu_review sudah ada di tabel registrations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Hanya drop column jika ada
            if (Schema::hasColumn('registrations', 'ditolak_pada')) {
                $table->dropColumn('ditolak_pada');
            }

            if (Schema::hasColumn('registrations', 'diperbarui_setelah_ditolak')) {
                $table->dropColumn('diperbarui_setelah_ditolak');
            }

            // Jangan drop program_unggulan_id karena sudah ada dari migration sebelumnya
        });
    }
};
