<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove program unggulan domain (table + registration column).
     */
    public function up(): void
    {
        // Drop FK and column from registrations if present
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'program_unggulan_id')) {
                // Drop FK safely when exists
                try {
                    $table->dropForeign(['program_unggulan_id']);
                } catch (\Throwable $e) {
                    // Ignore if FK name differs or missing
                }

                try {
                    $table->dropColumn('program_unggulan_id');
                } catch (\Throwable $e) {
                    // Ignore if drop fails; better to continue
                }
            }
        });

        // Drop programs_unggulan table if exists
        Schema::dropIfExists('programs_unggulan');
    }

    /**
     * Restore program unggulan structures.
     */
    public function down(): void
    {
        // Recreate programs_unggulan table (simplified from original migration)
        Schema::create('programs_unggulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program')->unique();
            $table->decimal('potongan', 5, 2)->comment('Discount percentage (0-100)');
            $table->enum('perlu_verifikasi', ['yes', 'no'])->default('no');
            $table->json('dokumen_tambahan')->nullable()->comment('Additional required documents as JSON array');
            $table->timestamps();
        });

        // Re-add program_unggulan_id to registrations (nullable FK)
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'program_unggulan_id')) {
                $table->foreignId('program_unggulan_id')
                    ->nullable()
                    ->after('package_id')
                    ->constrained('programs_unggulan')
                    ->nullOnDelete();
            }
        });
    }
};
