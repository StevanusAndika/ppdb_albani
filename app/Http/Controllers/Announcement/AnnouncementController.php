<?php

namespace App\Http\Controllers\Announcement;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    public function index()
    {
        $eligibleRegistrations = $this->getEligibleRegistrations();
        $announcements = Announcement::with('registration.user')
            ->latest()
            ->paginate(10);

        // Hitung statistik untuk dikirim ke view
        $sentCount = Announcement::where('status', 'sent')->count();
        $failedCount = Announcement::where('status', 'failed')->count();
        $totalSent = $sentCount;

        return view('dashboard.admin.announcements.index', compact(
            'eligibleRegistrations',
            'announcements',
            'sentCount',
            'failedCount',
            'totalSent'
        ));
    }

    public function getEligibleRegistrations()
    {
        // Dapatkan semua registration yang eligible
        $eligibleRegistrations = Registration::with(['user', 'payments', 'package'])
            ->where('status_pendaftaran', 'diterima')
            ->whereHas('payments', function ($query) {
                $query->whereIn('status', ['success', 'lunas']);
            })
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->filter(function ($registration) {
                // Cek dokumen lengkap
                $hasAllDocuments = $registration->hasAllDocuments();

                // Cek pembayaran lunas (xendit atau cash)
                $hasSuccessfulPayment = $registration->hasSuccessfulPayment();

                return $hasAllDocuments && $hasSuccessfulPayment;
            });

        // Filter out yang sudah pernah dikirim pesan berhasil
        return $eligibleRegistrations->filter(function ($registration) {
            return !$this->hasBeenSentSuccessfully($registration->id);
        });
    }

    public function sendIndividualMessage(Request $request, $registrationId)
    {
        try {
            DB::beginTransaction();

            $registration = Registration::with(['user'])->findOrFail($registrationId);

            // Validasi kelayakan dan cek apakah sudah pernah dikirim
            if (!$this->isEligibleForAnnouncement($registration)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Calon santri tidak memenuhi syarat: dokumen belum lengkap atau pembayaran belum lunas'
                ], 400);
            }

            if ($this->hasBeenSentSuccessfully($registration->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan sudah pernah dikirim ke calon santri ini'
                ], 400);
            }

            $phoneNumber = $registration->user->getFormattedPhoneNumber();
            $message = "Selamat, anda dinyatakan lolos dan siap menjadi santri di Pondok Pesantren Bani Syahid";

            // Kirim pesan via Fonnte
            $result = $this->sendFonnteMessage($phoneNumber, $message);

            // Simpan ke database
            $announcement = Announcement::create([
                'registration_id' => $registration->id,
                'title' => 'Pengumuman Kelulusan Individual',
                'message' => $message,
                'status' => $result['success'] ? 'sent' : 'failed',
                'recipients' => [$phoneNumber],
                'sent_at' => $result['success'] ? now() : null
            ]);

            DB::commit();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Pesan berhasil dikirim' : 'Gagal mengirim pesan: ' . $result['message'],
                'data' => $announcement,
                'remove_from_list' => $result['success']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending individual message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendBulkMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $eligibleRegistrations = $this->getEligibleRegistrations();

            if ($eligibleRegistrations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada calon santri yang memenuhi syarat'
                ], 400);
            }

            $phoneNumbers = [];
            $successCount = 0;
            $failedCount = 0;
            $alreadySentCount = 0; // Counter untuk yang sudah dikirim
            $successfulRegistrations = [];
            $message = "Selamat, anda dinyatakan lolos dan siap menjadi santri di Pondok Pesantren Bani Syahid";

            foreach ($eligibleRegistrations as $registration) {
                $phoneNumber = $registration->user->getFormattedPhoneNumber();

                // Cek apakah nomor ini sudah pernah dikirim pesan berhasil
                if ($this->hasPhoneBeenSentSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman ke {$phoneNumber} - sudah pernah dikirim");
                    continue; // Skip pengiriman untuk nomor ini
                }

                $phoneNumbers[] = $phoneNumber;

                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                    $successfulRegistrations[] = $registration->id;

                    // Simpan individual record untuk yang berhasil
                    Announcement::create([
                        'registration_id' => $registration->id,
                        'title' => 'Pengumuman Kelulusan (Bulk)',
                        'message' => $message,
                        'status' => 'sent',
                        'recipients' => [$phoneNumber],
                        'sent_at' => now()
                    ]);
                } else {
                    $failedCount++;
                    Log::error('Failed to send message to ' . $phoneNumber . ': ' . $result['message']);

                    // Simpan individual record untuk yang gagal
                    Announcement::create([
                        'registration_id' => $registration->id,
                        'title' => 'Pengumuman Kelulusan (Bulk - Failed)',
                        'message' => $message,
                        'status' => 'failed',
                        'recipients' => [$phoneNumber],
                        'sent_at' => null
                    ]);
                }
            }

            // Simpan summary record untuk bulk message
            $announcement = Announcement::create([
                'registration_id' => null,
                'title' => 'Pengumuman Kelulusan Massal - Summary',
                'message' => $message,
                'status' => $successCount > 0 ? 'sent' : 'failed',
                'recipients' => $phoneNumbers,
                'sent_at' => $successCount > 0 ? now() : null
            ]);

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} pesan";

            if ($failedCount > 0) {
                $responseMessage .= ", gagal {$failedCount} pesan";
            }

            if ($alreadySentCount > 0) {
                $responseMessage .= ", {$alreadySentCount} sudah pernah dikirim sebelumnya";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'data' => [
                    'total' => $eligibleRegistrations->count(),
                    'success' => $successCount,
                    'failed' => $failedCount,
                    'already_sent' => $alreadySentCount,
                    'successful_registrations' => $successfulRegistrations,
                    'announcement' => $announcement
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending bulk message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendToAllSantri(Request $request)
    {
        try {
            DB::beginTransaction();

            $santriUsers = User::where('role', 'calon_santri')
                ->where('is_active', true)
                ->whereNotNull('phone_number')
                ->get();

            if ($santriUsers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada santri yang tersedia'
                ], 400);
            }

            $phoneNumbers = [];
            $successCount = 0;
            $failedCount = 0;
            $alreadySentCount = 0;
            $message = "Selamat, anda dinyatakan lolos dan siap menjadi santri di Pondok Pesantren Bani Syahid";

            foreach ($santriUsers as $user) {
                $phoneNumber = $user->getFormattedPhoneNumber();

                // Cek apakah nomor ini sudah pernah dikirim pesan berhasil
                if ($this->hasPhoneBeenSentSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman ke {$phoneNumber} - sudah pernah dikirim");
                    continue; // Skip pengiriman untuk nomor ini
                }

                $phoneNumbers[] = $phoneNumber;

                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                    Log::error('Failed to send message to ' . $phoneNumber . ': ' . $result['message']);
                }
            }

            // Simpan announcement untuk semua santri
            $announcement = Announcement::create([
                'registration_id' => null,
                'title' => 'Pengumuman untuk Semua Santri',
                'message' => $message,
                'status' => $successCount > 0 ? 'sent' : 'failed',
                'recipients' => $phoneNumbers,
                'sent_at' => $successCount > 0 ? now() : null
            ]);

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} pesan ke semua santri";

            if ($failedCount > 0) {
                $responseMessage .= ", gagal {$failedCount} pesan";
            }

            if ($alreadySentCount > 0) {
                $responseMessage .= ", {$alreadySentCount} sudah pernah dikirim sebelumnya";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'data' => [
                    'total' => $santriUsers->count(),
                    'success' => $successCount,
                    'failed' => $failedCount,
                    'already_sent' => $alreadySentCount,
                    'announcement' => $announcement
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending message to all santri: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isEligibleForAnnouncement(Registration $registration): bool
    {
        return $registration->status_pendaftaran === 'diterima' &&
               $registration->hasAllDocuments() &&
               $registration->hasSuccessfulPayment() &&
               $registration->user->is_active;
    }

    /**
     * Cek apakah pesan sudah pernah dikirim berhasil ke registration ini
     */
    private function hasBeenSentSuccessfully($registrationId): bool
    {
        return Announcement::where('registration_id', $registrationId)
            ->where('status', 'sent')
            ->exists();
    }

    /**
     * Cek apakah nomor telepon sudah pernah dikirim pesan berhasil
     * Method baru untuk cek berdasarkan nomor telepon
     */
    private function hasPhoneBeenSentSuccessfully($phoneNumber): bool
    {
        return Announcement::where('status', 'sent')
            ->whereJsonContains('recipients', $phoneNumber)
            ->exists();
    }

    /**
     * Send message via Fonnte API
     */
    private function sendFonnteMessage($phone, $message)
    {
        try {
            $token = config('services.fonnte.token');
            $baseUrl = config('services.fonnte.url', 'https://api.fonnte.com');

            if (empty($token)) {
                return [
                    'success' => false,
                    'message' => 'Fonnte token tidak dikonfigurasi'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => $token
            ])->timeout(30)
              ->post($baseUrl . '/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful()) {
                if (isset($result['status']) && $result['status'] === true) {
                    return [
                        'success' => true,
                        'message' => 'Pesan berhasil dikirim',
                        'response' => $result
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Unknown error from Fonnte'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'HTTP Error: ' . $response->status() . ' - ' . ($result['message'] ?? 'Unknown error')
                ];
            }

        } catch (\Exception $e) {
            Log::error('Fonnte API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
