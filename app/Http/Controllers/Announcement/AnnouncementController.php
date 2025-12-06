<?php

namespace App\Http\Controllers\Announcement;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\GraduationAnnouncement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = app('fonnte');
    }

    /**
     * Halaman utama pengumuman KELULUSAN
     */
    public function index()
    {
        $eligibleRegistrations = $this->getEligibleRegistrations();

        // Gunakan model GraduationAnnouncement untuk data kelulusan
        $announcements = GraduationAnnouncement::with(['registration.user', 'registration.package'])
            ->where('status', 'sent')
            ->latest()
            ->paginate(10);

        // Hitung statistik untuk kelulusan
        $sentCount = GraduationAnnouncement::where('status', 'sent')->count();
        $failedCount = GraduationAnnouncement::where('status', 'failed')->count();
        $totalSent = $sentCount;

        return view('dashboard.admin.announcements.index', compact(
            'eligibleRegistrations',
            'announcements',
            'sentCount',
            'failedCount',
            'totalSent'
        ));
    }

    /**
     * Dapatkan calon santri yang memenuhi syarat KELULUSAN
     */
    public function getEligibleRegistrations()
    {
        // Query dasar untuk mendapatkan registrasi yang memenuhi syarat
        $eligibleRegistrations = Registration::with(['user', 'payments', 'package'])
            ->where('status_pendaftaran', 'diterima')
            ->where('status_seleksi', 'sudah_mengikuti_seleksi')
            ->whereHas('payments', function ($query) {
                $query->whereIn('status', ['success', 'lunas']);
            })
            ->whereHas('user', function ($query) {
                $query->where('is_active', true)
                      ->whereNotNull('phone_number');
            })
            ->get()
            ->filter(function ($registration) {
                // Filter tambahan untuk validasi kelayakan
                return $this->isEligibleForGraduation($registration) &&
                       !$this->hasGraduationBeenSent($registration->id);
            });

        return $eligibleRegistrations;
    }

    /**
     * Kirim pesan KELULUSAN individual
     */
    public function sendIndividualMessage(Request $request, $registrationId)
    {
        try {
            DB::beginTransaction();

            $registration = Registration::with(['user', 'payments'])->findOrFail($registrationId);

            // Validasi kelayakan untuk kelulusan
            if (!$this->isEligibleForGraduation($registration)) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getEligibilityErrorMessage($registration)
                ], 400);
            }

            // Cek apakah sudah pernah dikirim pesan kelulusan
            if ($this->hasGraduationBeenSent($registration->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan kelulusan sudah pernah dikirim ke calon santri ini'
                ], 400);
            }

            $phoneNumber = $registration->user->getFormattedPhoneNumber();

            if (empty($phoneNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon tidak valid atau kosong'
                ], 400);
            }

            $namaSantri = $registration->nama_lengkap;
            $programPendidikan = $registration->program_pendidikan_label ?? $registration->program_pendidikan;
            $idPendaftaran = $registration->id_pendaftaran;

            // Format pesan KELULUSAN
            $message = $this->formatGraduationMessage($namaSantri, $programPendidikan, $idPendaftaran);

            // Kirim pesan via Fonnte Service
            $result = $this->sendFonnteMessage($phoneNumber, $message);

            // Simpan ke GRADUATION_ANNOUNCEMENTS
            $announcement = GraduationAnnouncement::create([
                'registration_id' => $registration->id,
                'announcement_type' => 'individual',
                'title' => 'Pengumuman Kelulusan Individual',
                'message' => $message,
                'status' => $result['success'] ? 'sent' : 'failed',
                'recipients' => [$phoneNumber],
                'recipient_count' => 1,
                'sent_at' => $result['success'] ? now() : null
            ]);

            DB::commit();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Pesan kelulusan berhasil dikirim' : 'Gagal mengirim pesan: ' . $result['message'],
                'data' => $announcement,
                'remove_from_list' => $result['success']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending individual graduation message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim pesan KELULUSAN secara massal
     */
    public function sendBulkMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $eligibleRegistrations = $this->getEligibleRegistrations();

            if ($eligibleRegistrations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada calon santri yang memenuhi syarat kelulusan'
                ], 400);
            }

            $phoneNumbers = [];
            $successCount = 0;
            $failedCount = 0;
            $alreadySentCount = 0;
            $successfulRegistrations = [];
            $failedRegistrations = [];

            foreach ($eligibleRegistrations as $registration) {
                $phoneNumber = $registration->user->getFormattedPhoneNumber();

                if (empty($phoneNumber)) {
                    Log::warning("Registration {$registration->id} tidak memiliki nomor telepon yang valid");
                    $failedRegistrations[] = [
                        'id' => $registration->id,
                        'name' => $registration->nama_lengkap,
                        'reason' => 'Nomor telepon tidak valid'
                    ];
                    continue;
                }

                // Cek apakah nomor ini sudah pernah dikirim pesan kelulusan
                if ($this->hasPhoneBeenSentSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman kelulusan ke {$phoneNumber} - sudah pernah dikirim");
                    continue;
                }

                $phoneNumbers[] = $phoneNumber;
                $namaSantri = $registration->nama_lengkap;
                $programPendidikan = $registration->program_pendidikan_label ?? $registration->program_pendidikan;
                $idPendaftaran = $registration->id_pendaftaran;

                // Format pesan kelulusan
                $message = $this->formatGraduationMessage($namaSantri, $programPendidikan, $idPendaftaran);

                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                    $successfulRegistrations[] = $registration->id;

                    // Simpan individual record untuk yang berhasil
                    GraduationAnnouncement::create([
                        'registration_id' => $registration->id,
                        'announcement_type' => 'individual',
                        'title' => 'Pengumuman Kelulusan (Bulk)',
                        'message' => $message,
                        'status' => 'sent',
                        'recipients' => [$phoneNumber],
                        'recipient_count' => 1,
                        'sent_at' => now()
                    ]);
                } else {
                    $failedCount++;
                    $failedRegistrations[] = [
                        'id' => $registration->id,
                        'name' => $registration->nama_lengkap,
                        'reason' => $result['message']
                    ];
                    Log::error('Failed to send graduation message to ' . $phoneNumber . ': ' . $result['message']);

                    // Simpan individual record untuk yang gagal
                    GraduationAnnouncement::create([
                        'registration_id' => $registration->id,
                        'announcement_type' => 'individual',
                        'title' => 'Pengumuman Kelulusan (Bulk - Failed)',
                        'message' => $message,
                        'status' => 'failed',
                        'recipients' => [$phoneNumber],
                        'recipient_count' => 1,
                        'sent_at' => null
                    ]);
                }
            }

            // Simpan summary record untuk bulk message
            if ($successCount > 0 || $failedCount > 0) {
                GraduationAnnouncement::create([
                    'registration_id' => null,
                    'announcement_type' => 'summary',
                    'title' => 'Pengumuman Kelulusan Massal - Summary',
                    'message' => 'Pesan pengumuman kelulusan telah dikirim ke ' . $successCount . ' penerima',
                    'status' => $successCount > 0 ? 'sent' : 'failed',
                    'recipients' => $phoneNumbers,
                    'recipient_count' => count($phoneNumbers),
                    'sent_at' => $successCount > 0 ? now() : null
                ]);
            }

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} pesan kelulusan";

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
                    'failed_registrations' => $failedRegistrations
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending bulk graduation message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim pesan kelulusan ke semua santri yang lulus
     */
    public function sendToAllSantri(Request $request)
    {
        try {
            DB::beginTransaction();

            // HANYA ambil santri yang sudah mengikuti seleksi, diterima, dan memenuhi semua syarat
            $santriUsers = User::where('role', 'calon_santri')
                ->where('is_active', true)
                ->whereNotNull('phone_number')
                ->with(['registrations' => function($query) {
                    $query->where('status_pendaftaran', 'diterima')
                          ->where('status_seleksi', 'sudah_mengikuti_seleksi')
                          ->with(['payments']);
                }])
                ->get()
                ->filter(function($user) {
                    // Filter hanya user yang memiliki registrasi sesuai kriteria kelulusan
                    if ($user->registrations->isEmpty()) {
                        return false;
                    }

                    $registration = $user->registrations->first();
                    return $registration->hasAllDocuments() &&
                           $registration->hasSuccessfulPayment();
                });

            if ($santriUsers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada santri yang memenuhi syarat kelulusan'
                ], 400);
            }

            $phoneNumbers = [];
            $successCount = 0;
            $failedCount = 0;
            $alreadySentCount = 0;
            $successfulUsers = [];
            $failedUsers = [];

            foreach ($santriUsers as $user) {
                $phoneNumber = $user->getFormattedPhoneNumber();

                if (empty($phoneNumber)) {
                    Log::warning("User {$user->id} tidak memiliki nomor telepon yang valid");
                    $failedUsers[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'reason' => 'Nomor telepon tidak valid'
                    ];
                    continue;
                }

                // Cek apakah nomor ini sudah pernah dikirim pesan kelulusan
                if ($this->hasPhoneBeenSentSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman kelulusan ke {$phoneNumber} - sudah pernah dikirim");
                    continue;
                }

                $phoneNumbers[] = $phoneNumber;

                // Ambil data registration pertama (asumsi satu user satu registration)
                $registration = $user->registrations->first();
                $namaSantri = $registration->nama_lengkap ?? $user->name;
                $programPendidikan = $registration->program_pendidikan_label ?? $registration->program_pendidikan ?? 'Pondok Pesantren Bani Syahid';
                $idPendaftaran = $registration->id_pendaftaran ?? '';

                // Format pesan kelulusan
                $message = $this->formatGraduationMessage($namaSantri, $programPendidikan, $idPendaftaran);

                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                    $successfulUsers[] = $user->id;

                    // Simpan announcement untuk setiap registration yang dimiliki user
                    foreach ($user->registrations as $reg) {
                        GraduationAnnouncement::create([
                            'registration_id' => $reg->id,
                            'announcement_type' => 'individual',
                            'title' => 'Pengumuman Kelulusan (Semua Santri)',
                            'message' => $message,
                            'status' => 'sent',
                            'recipients' => [$phoneNumber],
                            'recipient_count' => 1,
                            'sent_at' => now()
                        ]);
                    }
                } else {
                    $failedCount++;
                    $failedUsers[] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'reason' => $result['message']
                    ];
                    Log::error('Failed to send graduation message to ' . $phoneNumber . ': ' . $result['message']);

                    // Simpan announcement untuk setiap registration yang dimiliki user
                    foreach ($user->registrations as $reg) {
                        GraduationAnnouncement::create([
                            'registration_id' => $reg->id,
                            'announcement_type' => 'individual',
                            'title' => 'Pengumuman Kelulusan (Semua Santri - Gagal)',
                            'message' => $message,
                            'status' => 'failed',
                            'recipients' => [$phoneNumber],
                            'recipient_count' => 1,
                            'sent_at' => null
                        ]);
                    }
                }
            }

            // Simpan announcement summary untuk bulk message
            if (!empty($phoneNumbers)) {
                GraduationAnnouncement::create([
                    'registration_id' => null,
                    'announcement_type' => 'summary',
                    'title' => 'Pengumuman untuk Semua Santri yang Lulus - Summary',
                    'message' => 'Pesan pengumuman kelulusan telah dikirim ke ' . $successCount . ' santri yang lulus',
                    'status' => $successCount > 0 ? 'sent' : 'failed',
                    'recipients' => $phoneNumbers,
                    'recipient_count' => count($phoneNumbers),
                    'sent_at' => $successCount > 0 ? now() : null
                ]);
            }

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} pesan kelulusan ke semua santri yang lulus";

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
                    'successful_users' => $successfulUsers,
                    'failed_users' => $failedUsers
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending graduation message to all santri: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ====================================================
     * HELPER METHODS YANG DIPERBAIKI
     * ====================================================
     */

    /**
     * Cek kelayakan untuk pengumuman kelulusan - VERSI DIPERBAIKI
     */
    private function isEligibleForGraduation(Registration $registration): bool
    {
        try {
            // 1. Status pendaftaran harus 'diterima'
            if ($registration->status_pendaftaran !== 'diterima') {
                return false;
            }

            // 2. Status seleksi harus 'sudah_mengikuti_seleksi'
            if ($registration->status_seleksi !== 'sudah_mengikuti_seleksi') {
                return false;
            }

            // 3. Dokumen harus lengkap
            if (!$registration->hasAllDocuments()) {
                return false;
            }

            // 4. Harus ada pembayaran yang lunas
            if (!$registration->hasSuccessfulPayment()) {
                return false;
            }

            // 5. User harus aktif
            if (!$registration->user || !$registration->user->is_active) {
                return false;
            }

            // 6. User harus punya nomor telepon
            if (empty($registration->user->phone_number)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error in isEligibleForGraduation: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek apakah pesan kelulusan sudah pernah dikirim ke registration ini
     */
    private function hasGraduationBeenSent($registrationId): bool
    {
        try {
            return GraduationAnnouncement::where('registration_id', $registrationId)
                ->where('status', 'sent')
                ->where('announcement_type', 'individual')
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error in hasGraduationBeenSent: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek apakah nomor telepon sudah pernah dikirim - VERSI BARU
     */
    private function hasPhoneBeenSentSuccessfully($phoneNumber): bool
    {
        try {
            return GraduationAnnouncement::where('status', 'sent')
                ->whereJsonContains('recipients', $phoneNumber)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error in hasPhoneBeenSentSuccessfully: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Dapatkan pesan error untuk kelayakan - VERSI DIPERBAIKI
     */
    private function getEligibilityErrorMessage(Registration $registration): string
    {
        try {
            $errors = [];

            if ($registration->status_pendaftaran !== 'diterima') {
                $errors[] = 'Status pendaftaran belum "Diterima"';
            }

            if ($registration->status_seleksi !== 'sudah_mengikuti_seleksi') {
                $errors[] = 'Belum mengikuti seleksi';
            }

            if (!$registration->hasAllDocuments()) {
                $errors[] = 'Dokumen belum lengkap';
            }

            if (!$registration->hasSuccessfulPayment()) {
                $errors[] = 'Pembayaran belum lunas';
            }

            if (!$registration->user || !$registration->user->is_active) {
                $errors[] = 'User tidak aktif';
            }

            if (empty($registration->user->phone_number)) {
                $errors[] = 'Nomor telepon tidak tersedia';
            }

            return 'Calon santri tidak memenuhi syarat: ' . implode(', ', $errors);
        } catch (\Exception $e) {
            Log::error('Error in getEligibilityErrorMessage: ' . $e->getMessage());
            return 'Terjadi kesalahan saat memvalidasi data';
        }
    }

    /**
     * Format pesan pengumuman kelulusan
     */
    private function formatGraduationMessage(string $namaSantri, string $programPendidikan, string $idPendaftaran = ''): string
    {
        $programText = $programPendidikan ? "di {$programPendidikan}" : "di Pondok Pesantren Bani Syahid";
        $idText = $idPendaftaran ? " (ID: {$idPendaftaran})" : "";

        $message = "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n"
                 . "Kepada Yth.\n"
                 . "*{$namaSantri}{$idText}*\n\n"
                 . "Dengan memanjatkan puji syukur ke hadirat Allah Subhanahu Wa Ta'ala, \n"
                 . "kami sampaikan kabar gembira bahwa:\n\n"
                 . "📜 *HASIL SELEKSI PENERIMAAN SANTRI BARU*\n\n"
                 . "Berdasarkan hasil evaluasi Ujian Tulis Seleksi Masuk yang telah Anda ikuti,\n"
                 . "kami dengan ini menyatakan bahwa:\n\n"
                 . "✅ *ANDA DINYATAKAN LULUS DAN DITERIMA SEBAGAI SANTRI*\n"
                 . "   *PROGRAM PENDIDIKAN PILIHAN {$programText}*\n\n"
                 . "🎉 *SELAMAT!* 🎉\n\n"
                 . "Jika Ada pertanyaan lebih lanjut, silakan hubungi kontak administrasi kami di bawah ini:\n\n"
                 . "📞 *KONTAK ADMINISTRASI:*\n"
                 . "• WhatsApp PPDB Putra: +62895-1027-9293\n"
                 . "• WhatsApp PPDB Putri:  +62821-8395-3533\n\n"
                 . "⏰ *CATATAN PENTING:*\n"
                 . "Terima kasih atas kepercayaan Anda kepada Pondok Pesantren Bani Syahid.\n\n"
                 . "Wassalamu'alaikum Warahmatullahi Wabarakatuh,\n"
                 . "*Panitia PPDB*\n"
                 . "Pondok Pesantren Bani Syahid\n"
                 . "Tahun Ajaran 2025/2026";

        return $message;
    }

    /**
     * Send message via Fonnte Service
     */
    private function sendFonnteMessage($phone, $message)
    {
        try {
            $result = $this->fonnteService->sendMessage($phone, $message);

            return [
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? 'Unknown error from Fonnte'
            ];

        } catch (\Exception $e) {
            Log::error('Fonnte Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update status seleksi untuk registration - VERSI DIPERBAIKI
     */
    public function updateStatusSeleksi(Request $request, $registrationId)
    {
        try {
            $registration = Registration::findOrFail($registrationId);
            $status = $request->input('status_seleksi');

            if (!in_array($status, ['sudah_mengikuti_seleksi', 'belum_mengikuti_seleksi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status seleksi tidak valid'
                ], 400);
            }

            $registration->update([
                'status_seleksi' => $status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status seleksi berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating status seleksi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
