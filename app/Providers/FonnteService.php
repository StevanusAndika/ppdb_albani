<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = 'https://api.fonnte.com/send';
        $this->token = env('FONNTE_TOKEN');
    }

    /**
     * Kirim pesan WhatsApp
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status']) {
                Log::info('Fonnte message sent successfully', [
                    'phone' => $phone,
                    'response' => $result
                ]);
                return ['success' => true, 'message' => 'Pesan berhasil dikirim'];
            } else {
                Log::error('Fonnte API error', [
                    'phone' => $phone,
                    'response' => $result
                ]);
                return ['success' => false, 'message' => $result['message'] ?? 'Gagal mengirim pesan'];
            }
        } catch (\Exception $e) {
            Log::error('Fonnte service exception', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Kirim OTP via WhatsApp
     */
    public function sendOtp(string $phone, string $otp): array
    {
        $message = "Kode OTP Reset Password PPDB Pondok Pesantren Bani Syahid\n\n"
                 . "Kode OTP Anda: *{$otp}*\n"
                 . "Kode ini berlaku selama 10 menit.\n\n"
                 . "Jangan berikan kode ini kepada siapapun.\n"
                 . "Jika Anda tidak meminta reset password, abaikan pesan ini.";

        return $this->sendMessage($phone, $message);
    }

    /**
 * Kirim notifikasi penolakan pendaftaran
 */
    public function sendRegistrationRejection(string $phone, string $namaSantri, string $alasan): array
    {
    $message = "PEMBERITAHUAN PENOLAKAN PENDAFTARAN\n\n"
             . "Kepada Yth. Orang Tua/Wali Santri {$namaSantri},\n\n"
             . "Dengan hormat, kami sampaikan bahwa pendaftaran calon santri *{$namaSantri}* "
             . "tidak dapat kami terima dengan alasan:\n\n"
             . "*{$alasan}*\n\n"
             . "Silakan login ke sistem PPDB untuk:\n"
             . "1. Melengkapi data yang kurang\n"
             . "2. Memperbaiki data yang tidak sesuai\n"
             . "3. Mengunggah ulang dokumen yang diperlukan\n\n"
             . "Anda dapat mengisi ulang formulir pendaftaran dengan data yang benar dan lengkap.\n\n"
             . "Terima kasih atas perhatiannya.\n\n"
             . "Salam,\n"
             . "Panitia PPDB Pondok Pesantren Bani Syahid";

    return $this->sendMessage($phone, $message);
}
}
