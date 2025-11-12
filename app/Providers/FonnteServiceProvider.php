<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('fonnte', function ($app) {
            return new class {
                protected $apiUrl;
                protected $token;

                public function __construct()
                {
                    $this->apiUrl = 'https://api.fonnte.com/send';
                    $this->token = env('FONNTE_TOKEN');

                    // Validasi token
                    if (empty($this->token)) {
                        Log::error('Fonnte token is not set in environment variables');
                    }
                }

                /**
                 * Kirim pesan WhatsApp
                 */
                public function sendMessage(string $phone, string $message): array
                {
                    try {
                        // Format nomor telepon (hapus +62 atau 0 di depan)
                        $formattedPhone = $this->formatPhoneNumber($phone);

                        // Validasi nomor telepon
                        if (empty($formattedPhone)) {
                            return ['success' => false, 'message' => 'Nomor telepon tidak valid'];
                        }

                        $response = Http::timeout(30)->withHeaders([
                            'Authorization' => $this->token
                        ])->post($this->apiUrl, [
                            'target' => $formattedPhone,
                            'message' => $message,
                            'countryCode' => '62',
                        ]);

                        $result = $response->json();

                        if ($response->successful() && isset($result['status']) && $result['status']) {
                            Log::info('Fonnte message sent successfully', [
                                'phone' => $formattedPhone,
                                'response' => $result
                            ]);
                            return ['success' => true, 'message' => 'Pesan berhasil dikirim'];
                        } else {
                            Log::error('Fonnte API error', [
                                'phone' => $formattedPhone,
                                'response' => $result,
                                'status_code' => $response->status()
                            ]);
                            return [
                                'success' => false,
                                'message' => $result['message'] ?? 'Gagal mengirim pesan',
                                'details' => $result
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Fonnte service exception', [
                            'phone' => $phone,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                        ];
                    }
                }

                /**
                 * Format nomor telepon untuk Fonnte
                 */
                private function formatPhoneNumber(string $phone): string
                {
                    // Hapus karakter non-digit
                    $phone = preg_replace('/[^0-9]/', '', $phone);

                    // Jika kosong setelah diformat
                    if (empty($phone)) {
                        return '';
                    }

                    // Jika diawali dengan +62, hapus +
                    if (str_starts_with($phone, '62')) {
                        return $phone;
                    }

                    // Jika diawali dengan 0, ganti dengan 62
                    if (str_starts_with($phone, '0')) {
                        return '62' . substr($phone, 1);
                    }

                    // Jika kurang dari 10 digit, anggap tidak valid
                    if (strlen($phone) < 10) {
                        return '';
                    }

                    return $phone;
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
                    $message = "PEMBERITAHUAN KETIDAKSESUAIAN DATA PENDAFTARAN\n\n"
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


                /**
                 * Kirim notifikasi pembayaran berhasil
                 */
                public function sendPaymentSuccess(string $phone, string $namaSantri, string $paymentCode, string $amount, string $packageName): array
                {
                    $message = "PEMBAYARAN BERHASIL ✅\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Pembayaran Dengan Metode Online Untuk pendaftaran santri Anda telah BERHASIL.\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Paket: {$packageName}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Status: LUNAS\n\n"
                             . "Selamat! Pendaftaran Anda telah dikonfirmasi. \n"
                             . "Tim admin akan menghubungi Anda untuk informasi lebih lanjut.\n\n"
                             . "Terima kasih telah bergabung dengan Pondok Pesantren Bani Syahid.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi pembayaran gagal
                 */
                public function sendPaymentFailed(string $phone, string $namaSantri, string $paymentCode, string $amount, string $reason = ''): array
                {
                    $reasonText = $reason ? "\nAlasan: {$reason}" : "";

                    $message = "PEMBAYARAN GAGAL ❌\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Pembayaran pendaftaran santri Anda GAGAL diproses.{$reasonText}\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Status: GAGAL\n\n"
                             . "Silakan coba kembali atau hubungi admin untuk bantuan.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi pembayaran expired
                 */
                public function sendPaymentExpired(string $phone, string $namaSantri, string $paymentCode, string $amount): array
                {
                    $message = "PEMBAYARAN KADALUARSA ⏰\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Batas waktu pembayaran pendaftaran santri Anda telah HABIS.\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Status: KADALUARSA\n\n"
                             . "Silakan buat pembayaran baru melalui dashboard PPDB.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi invoice dibuat (Xendit)
                 */
                public function sendInvoiceCreated(string $phone, string $namaSantri, string $paymentCode, string $amount, string $expiryDate, string $invoiceUrl): array
                {
                    $message = "INVOICE PEMBAYARAN DIBUAT 📄\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Invoice pembayaran pendaftaran santri telah dibuat.\n\n"
                             . "📋 Detail Invoice:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Batas Waktu: {$expiryDate}\n"
                             . "• Status: MENUNGGU PEMBAYARAN\n\n"
                             . "🔗 Link Pembayaran:\n"
                             . "{$invoiceUrl}\n\n"
                             . "Silakan selesaikan pembayaran sebelum batas waktu yang ditentukan.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi pembayaran cash
                 */
                public function sendCashPaymentInstruction(string $phone, string $namaSantri, string $paymentCode, string $amount): array
                {
                    $message = "INSTRUKSI PEMBAYARAN CASH 💵\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Anda memilih metode pembayaran CASH.\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Status: MENUNGGU PEMBAYARAN\n\n"
                             . "📍 Instruksi Pembayaran:\n"
                             . "Silakan datang langsung ke:\n"
                             . "Pondok Pesantren Al-Qur'an Bani Syahid\n"
                             . "Untuk melakukan pembayaran kepada admin.\n\n"
                             . "⏰ Waktu Kunjungan:\n"
                             . "Senin - Jumat: 08:00 - 16:00 WIB\n"
                             . "Sabtu: 08:00 - 14:00 WIB\n\n"
                             . "Jangan lupa membawa bukti ID pendaftaran ini.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi pembayaran manual oleh admin
                 */
                public function sendManualPaymentConfirmation(string $phone, string $namaSantri, string $paymentCode, string $amount, string $adminName): array
                {
                    $message = "KONFIRMASI PEMBAYARAN MANUAL ✅\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Pembayaran pendaftaran santri Anda telah dikonfirmasi secara MANUAL oleh admin.\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Status: LUNAS\n"
                             . "• Dikonfirmasi oleh: {$adminName}\n\n"
                             . "Selamat! Pendaftaran Anda telah aktif.\n"
                             . "Tim admin akan menghubungi Anda untuk informasi lebih lanjut.\n\n"
                             . "Terima kasih.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi reminder pembayaran
                 */
                public function sendPaymentReminder(string $phone, string $namaSantri, string $paymentCode, string $amount, string $expiryDate): array
                {
                    $message = "PENGINGAT PEMBAYARAN ⚠️\n\n"
                             . "Halo {$namaSantri},\n\n"
                             . "Ini adalah pengingat untuk pembayaran pendaftaran santri Anda.\n\n"
                             . "📋 Detail Pembayaran:\n"
                             . "• ID Pembayaran: {$paymentCode}\n"
                             . "• Jumlah: Rp {$amount}\n"
                             . "• Batas Waktu: {$expiryDate}\n\n"
                             . "Segera selesaikan pembayaran sebelum batas waktu habis.\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Cek status koneksi API Fonnte
                 */
                public function checkConnection(): array
                {
                    try {
                        $response = Http::timeout(10)->withHeaders([
                            'Authorization' => $this->token
                        ])->get('https://api.fonnte.com/me');

                        if ($response->successful()) {
                            return ['success' => true, 'message' => 'Koneksi API Fonnte berhasil'];
                        } else {
                            return ['success' => false, 'message' => 'Gagal terhubung ke API Fonnte'];
                        }
                    } catch (\Exception $e) {
                        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
                    }
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
