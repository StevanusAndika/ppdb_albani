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

class SeleksiAnnoucementController extends Controller
{
    public function index()
    {
        $eligibleRegistrations = $this->getEligibleRegistrations();
        $announcements = Announcement::with('registration.user')
            ->where('title', 'LIKE', '%Seleksi%')
            ->latest()
            ->paginate(10);

        // Hitung statistik untuk dikirim ke view
        $sentCount = Announcement::where('status', 'sent')
            ->where('title', 'LIKE', '%Seleksi%')
            ->count();
        $failedCount = Announcement::where('status', 'failed')
            ->where('title', 'LIKE', '%Seleksi%')
            ->count();
        $totalSent = $sentCount;

        return view('dashboard.admin.seleksi-announcements.index', compact(
            'eligibleRegistrations',
            'announcements',
            'sentCount',
            'failedCount',
            'totalSent'
        ));
    }

    public function getEligibleRegistrations()
    {
        // Dapatkan semua registration yang eligible untuk seleksi
        $eligibleRegistrations = Registration::with(['user', 'payments', 'package'])
            ->whereIn('status_pendaftaran', ['diterima', 'telah_dilihat', 'menunggu_diverifikasi', 'perlu_review'])
            ->where('status_seleksi', 'belum_mengikuti_seleksi')
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

        // Filter out yang sudah pernah dikirim pesan seleksi berhasil
        return $eligibleRegistrations->filter(function ($registration) {
            return !$this->hasBeenSentSeleksiSuccessfully($registration->id);
        });
    }

    public function sendIndividualSeleksi(Request $request, $registrationId)
    {
        try {
            DB::beginTransaction();

            $registration = Registration::with(['user'])->findOrFail($registrationId);
            $tanggalSeleksi = $request->validate(['tanggal_seleksi' => 'required|date'])['tanggal_seleksi'];

            // Validasi kelayakan dan cek apakah sudah pernah dikirim
            if (!$this->isEligibleForSeleksiAnnouncement($registration)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Calon santri tidak memenuhi syarat untuk undangan seleksi'
                ], 400);
            }

            if ($this->hasBeenSentSeleksiSuccessfully($registration->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan seleksi sudah pernah dikirim ke calon santri ini'
                ], 400);
            }

            $phoneNumber = $registration->user->getFormattedPhoneNumber();
            $formattedDate = \Carbon\Carbon::parse($tanggalSeleksi)->translatedFormat('l, d F Y');

            $message = $this->getIndividualSeleksiMessage($registration->nama_lengkap, $formattedDate, $registration->status_pendaftaran);

            // Kirim pesan via Fonnte
            $result = $this->sendFonnteMessage($phoneNumber, $message);

            // Simpan ke database
            $announcement = Announcement::create([
                'registration_id' => $registration->id,
                'title' => 'Undangan Tes Seleksi Individual',
                'message' => $message,
                'status' => $result['success'] ? 'sent' : 'failed',
                'recipients' => [$phoneNumber],
                'sent_at' => $result['success'] ? now() : null
            ]);

            DB::commit();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Undangan seleksi berhasil dikirim' : 'Gagal mengirim undangan: ' . $result['message'],
                'data' => $announcement,
                'remove_from_list' => $result['success']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending individual seleksi message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendBulkSeleksi(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ]);

            $eligibleRegistrations = $this->getEligibleRegistrations();

            if ($eligibleRegistrations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada calon santri yang memenuhi syarat untuk undangan seleksi'
                ], 400);
            }

            $phoneNumbers = [];
            $successCount = 0;
            $failedCount = 0;
            $alreadySentCount = 0;
            $successfulRegistrations = [];

            $formattedStart = \Carbon\Carbon::parse($validated['tanggal_mulai'])->translatedFormat('d F Y');
            $formattedEnd = \Carbon\Carbon::parse($validated['tanggal_selesai'])->translatedFormat('d F Y');

            foreach ($eligibleRegistrations as $registration) {
                $phoneNumber = $registration->user->getFormattedPhoneNumber();

                // Cek apakah nomor ini sudah pernah dikirim undangan seleksi berhasil
                if ($this->hasPhoneBeenSentSeleksiSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman undangan seleksi ke {$phoneNumber} - sudah pernah dikirim");
                    continue;
                }

                $phoneNumbers[] = $phoneNumber;

                $message = $this->getBulkSeleksiMessage($registration->nama_lengkap, $formattedStart, $formattedEnd, $registration->status_pendaftaran);
                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                    $successfulRegistrations[] = $registration->id;

                    // Simpan individual record untuk yang berhasil
                    Announcement::create([
                        'registration_id' => $registration->id,
                        'title' => 'Undangan Tes Seleksi Massal',
                        'message' => $message,
                        'status' => 'sent',
                        'recipients' => [$phoneNumber],
                        'sent_at' => now()
                    ]);
                } else {
                    $failedCount++;
                    Log::error('Failed to send seleksi message to ' . $phoneNumber . ': ' . $result['message']);

                    // Simpan individual record untuk yang gagal
                    Announcement::create([
                        'registration_id' => $registration->id,
                        'title' => 'Undangan Tes Seleksi (Bulk - Failed)',
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
                'title' => 'Undangan Tes Seleksi Massal - Summary',
                'message' => "Undangan tes seleksi periode {$formattedStart} - {$formattedEnd}",
                'status' => $successCount > 0 ? 'sent' : 'failed',
                'recipients' => $phoneNumbers,
                'sent_at' => $successCount > 0 ? now() : null
            ]);

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} undangan seleksi";

            if ($failedCount > 0) {
                $responseMessage .= ", gagal {$failedCount} undangan";
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
            Log::error('Error sending bulk seleksi message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendToAllSantriSeleksi(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ]);

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

            $formattedStart = \Carbon\Carbon::parse($validated['tanggal_mulai'])->translatedFormat('d F Y');
            $formattedEnd = \Carbon\Carbon::parse($validated['tanggal_selesai'])->translatedFormat('d F Y');

            foreach ($santriUsers as $user) {
                $phoneNumber = $user->getFormattedPhoneNumber();

                // Cek apakah nomor ini sudah pernah dikirim undangan seleksi berhasil
                if ($this->hasPhoneBeenSentSeleksiSuccessfully($phoneNumber)) {
                    $alreadySentCount++;
                    Log::info("Skip pengiriman undangan seleksi ke {$phoneNumber} - sudah pernah dikirim");
                    continue;
                }

                $phoneNumbers[] = $phoneNumber;

                $message = $this->getGeneralSeleksiMessage($user->name, $formattedStart, $formattedEnd);
                $result = $this->sendFonnteMessage($phoneNumber, $message);

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                    Log::error('Failed to send seleksi message to ' . $phoneNumber . ': ' . $result['message']);
                }
            }

            // Simpan announcement untuk semua santri
            $announcement = Announcement::create([
                'registration_id' => null,
                'title' => 'Undangan Tes Seleksi untuk Semua Santri',
                'message' => "Undangan tes seleksi periode {$formattedStart} - {$formattedEnd} untuk semua santri",
                'status' => $successCount > 0 ? 'sent' : 'failed',
                'recipients' => $phoneNumbers,
                'sent_at' => $successCount > 0 ? now() : null
            ]);

            DB::commit();

            $responseMessage = "Berhasil mengirim {$successCount} undangan seleksi ke semua santri";

            if ($failedCount > 0) {
                $responseMessage .= ", gagal {$failedCount} undangan";
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
            Log::error('Error sending seleksi message to all santri: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isEligibleForSeleksiAnnouncement(Registration $registration): bool
    {
        return in_array($registration->status_pendaftaran, ['diterima', 'telah_dilihat', 'menunggu_diverifikasi', 'perlu_review']) &&
               $registration->status_seleksi === 'belum_mengikuti_seleksi' &&
               $registration->hasAllDocuments() &&
               $registration->hasSuccessfulPayment() &&
               $registration->user->is_active;
    }

    private function hasBeenSentSeleksiSuccessfully($registrationId): bool
    {
        return Announcement::where('registration_id', $registrationId)
            ->where('title', 'LIKE', '%Seleksi%')
            ->where('status', 'sent')
            ->exists();
    }

    private function hasPhoneBeenSentSeleksiSuccessfully($phoneNumber): bool
    {
        return Announcement::where('status', 'sent')
            ->where('title', 'LIKE', '%Seleksi%')
            ->whereJsonContains('recipients', $phoneNumber)
            ->exists();
    }

    private function getIndividualSeleksiMessage($namaSantri, $tanggalSeleksi, $statusPendaftaran): string
    {
        $statusInfo = $this->getStatusInfo($statusPendaftaran);

        return "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n" .
               "Kepada Yth. Calon Santri {$namaSantri}\n\n" .
               "{$statusInfo}\n\n" .
               "Kami mengundang Anda untuk mengikuti Tes Seleksi Masuk Pondok Pesantren Bani Syahid.\n\n" .
               "📅 *Tanggal Tes Seleksi:*\n" .
               "{$tanggalSeleksi}\n\n" .
               "⏰ *Waktu:*\n" .
               "08.00 - 16.00 WIB\n\n" .
               "📍 *Tempat:*\n" .
               "Pondok Pesantren Al-Quran Bani Syahid\n" .
               "Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452\n\n" .
               "📝 *Yang perlu dipersiapkan:*\n" .
               "• Alat tulis (pensil, pulpen, penghapus)\n" .
               "• Barcode Pendaftaran\n" .
               "• Berpakaian Rapi Dan Sopan(Bagi calon santri/santriwati dan orang tua/wali santri/santriwati )\n\n" .

               "Harap hadir 30 menit sebelum waktu tes dimulai.\n\n" .
               "Konfirmasi kehadiran dapat dilakukan melalui WhatsApp ini.\n\n" .
               "Terima kasih atas perhatiannya.\n\n" .
               "Wassalamu'alaikum Warahmatullahi Wabarakatuh\n" .
               "Panitia Tes Seleksi\n" .
               "Pondok Pesantren Bani Syahid";
    }

    private function getBulkSeleksiMessage($namaSantri, $tanggalMulai, $tanggalSelesai, $statusPendaftaran): string
    {
        $statusInfo = $this->getStatusInfo($statusPendaftaran);

        return "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n" .
               "Kepada Yth. Calon Santri {$namaSantri}\n\n" .
               "{$statusInfo}\n\n" .
               "Kami mengundang Anda untuk mengikuti Tes Seleksi Masuk Pondok Pesantren Bani Syahid.\n\n" .
               "📅 *Periode Tes Seleksi:*\n" .
               "{$tanggalMulai} - {$tanggalSelesai}\n\n" .
               "⏰ *Waktu:*\n" .
               "08.00 - 16.00 WIB\n\n" .
               "📍 *Tempat:*\n" .
               "Pondok Pesantren Al-Quran Bani Syahid\n" .
               "Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452\n\n" .
               "📝 *Yang perlu dipersiapkan:*\n" .
               "• Alat tulis (pensil, pulpen, penghapus)\n" .
               "• Barcode Pendaftaran\n" .
              "• Berpakaian Rapi Dan Sopan(Bagi calon santri/santriwati dan orang tua/wali santri/santriwati )\n\n" .
               "Harap konfirmasi kesediaan mengikuti tes melalui WhatsApp ini.\n\n" .
               "Diharapkan untuk hadir 30 menit sebelum waktu tes dimulai.\n\n" .
               "Terima kasih atas perhatiannya.\n\n" .
               "Wassalamu'alaikum Warahmatullahi Wabarakatuh\n" .
               "Panitia Tes Seleksi\n" .
               "Pondok Pesantren Al-Quran Bani Syahid";
    }

    private function getGeneralSeleksiMessage($namaSantri, $tanggalMulai, $tanggalSelesai): string
    {
        return "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n" .
               "Kepada Yth. Santri {$namaSantri}\n\n" .
               "Kami mengundang Anda untuk mengikuti Tes Seleksi Masuk Pondok Pesantren Al-Quran Bani Syahid.\n\n" .
               "📅 *Periode Tes Seleksi:*\n" .
               "{$tanggalMulai} - {$tanggalSelesai}\n\n" .
               "⏰ *Waktu:*\n" .
               "08.00 - 16.00 WIB\n\n" .
               "📍 *Tempat:*\n" .
               "Pondok Pesantren Al-Quran Bani Syahid\n" .
               "Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452\n\n" .
               "📝 *Yang perlu dipersiapkan:*\n" .
               "• Alat tulis (pensil, pulpen, penghapus)\n" .
               "• Barcode Pendaftaran\n" .
               "• Berpakaian Rapi Dan Sopan(Bagi calon santri/santriwati dan orang tua/wali santri/santriwati )\n\n" .
               "Diharapkan untuk hadir 30 menit sebelum waktu tes dimulai.\n\n" .
               "Konfirmasi kehadiran via WhatsApp ini.\n\n" .
                "Terima kasih atas perhatiannya.\n\n" .
               "Wassalamu'alaikum Warahmatullahi Wabarakatuh\n" .
               "Panitia Tes Seleksi\n" .
               "Pondok Pesantren Al-Quran Bani Syahid";
    }

    private function getStatusInfo($statusPendaftaran): string
    {
        $statusMessages = [
            'diterima' => "Berdasarkan berkas pendaftaran yang telah kami terima, Anda memenuhi persyaratan administrasi untuk mengikuti tes seleksi.",
            'telah_dilihat' => "Berkas pendaftaran Anda telah kami tinjau dan memenuhi persyaratan untuk mengikuti tes seleksi.",
            'menunggu_diverifikasi' => "Berkas pendaftaran Anda sedang dalam proses verifikasi dan telah memenuhi syarat untuk mengikuti tes seleksi.",
            'perlu_review' => "Berkas pendaftaran Anda memerlukan review tambahan, namun Anda dapat mengikuti tes seleksi terlebih dahulu.",
        ];

        return $statusMessages[$statusPendaftaran] ?? "Berdasarkan pendaftaran yang telah dilakukan, Anda diundang untuk mengikuti tes seleksi.";
    }

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
