<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredDocuments extends Command
{
    protected $signature = 'documents:cleanup';
    protected $description = 'Hapus dokumen yang sudah expired (3-4 tahun)';

    public function handle()
    {
        $this->info('Memulai pembersihan dokumen expired...');
        Log::info('Memulai pembersihan dokumen expired');

        $expiredRegistrations = Registration::expiringDocuments()->get();

        $deletedCount = 0;
        $errorCount = 0;

        foreach ($expiredRegistrations as $registration) {
            try {
                $this->info("Memproses dokumen untuk: {$registration->id_pendaftaran}");

                // Backup info sebelum hapus
                $documents = [
                    'kartu_keluarga' => $registration->kartu_keluaga_path,
                    'ijazah' => $registration->ijazah_path,
                    'akta_kelahiran' => $registration->akta_kelahiran_path,
                    'pas_foto' => $registration->pas_foto_path
                ];

                // Hapus dokumen
                $registration->deleteAllDocuments();
                $deletedCount++;

                $this->info("✓ Dokumen untuk {$registration->id_pendaftaran} berhasil dihapus");
                Log::info("Dokumen expired dihapus", [
                    'registration_id' => $registration->id,
                    'id_pendaftaran' => $registration->id_pendaftaran,
                    'documents' => $documents,
                    'deleted_at' => now()->toDateTimeString()
                ]);

            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Gagal menghapus dokumen untuk {$registration->id_pendaftaran}: " . $e->getMessage());
                Log::error("Gagal menghapus dokumen expired", [
                    'registration_id' => $registration->id,
                    'id_pendaftaran' => $registration->id_pendaftaran,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $summary = "Pembersihan selesai. {$deletedCount} dokumen berhasil dihapus, {$errorCount} error.";
        $this->info($summary);
        Log::info($summary);

        // Jika ada error, return exit code error
        if ($errorCount > 0) {
            return 1;
        }

        return 0;
    }
}
