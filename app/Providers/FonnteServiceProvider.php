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
                protected $apiKey;
                protected $deviceId;

                public function __construct()
                {
                    $this->apiUrl = 'https://api.fonnte.com/send';
                    $this->token = config('services.fonnte.token', env('FONNTE_TOKEN'));
                    $this->apiKey = config('services.fonnte.api_key', env('FONNTE_API_KEY'));
                    $this->deviceId = config('services.fonnte.device_id', env('FONNTE_DEVICE_ID'));

                    // Validasi token
                    if (empty($this->token) && empty($this->apiKey)) {
                        Log::error('Fonnte token atau API key is not set in environment variables');
                    }
                }

                /**
                 * Kirim pesan WhatsApp
                 */
                public function sendMessage(string $phone, string $message, array $options = []): array
                {
                    try {
                        // Format nomor telepon (hapus +62 atau 0 di depan)
                        $formattedPhone = $this->formatPhoneNumber($phone);

                        // Validasi nomor telepon
                        if (empty($formattedPhone)) {
                            Log::error('Nomor telepon tidak valid untuk Fonnte', ['phone' => $phone]);
                            return ['success' => false, 'message' => 'Nomor telepon tidak valid'];
                        }

                        // Validasi pesan
                        if (empty($message)) {
                            Log::error('Pesan kosong untuk Fonnte', ['phone' => $formattedPhone]);
                            return ['success' => false, 'message' => 'Pesan tidak boleh kosong'];
                        }

                        // Persiapan headers berdasarkan apakah menggunakan token atau API key
                        $headers = [];
                        if (!empty($this->token)) {
                            $headers['Authorization'] = $this->token;
                        } else {
                            $headers['Authorization'] = $this->apiKey;
                        }

                        // Persiapan payload
                        $payload = [
                            'target' => $formattedPhone,
                            'message' => $message,
                            'countryCode' => '62',
                        ];

                        // Tambahkan device_id jika tersedia
                        if (!empty($this->deviceId)) {
                            $payload['device_id'] = $this->deviceId;
                        }

                        // Gabungkan dengan options tambahan
                        $payload = array_merge($payload, $options);

                        Log::info('Mengirim pesan Fonnte', [
                            'phone' => $formattedPhone,
                            'message_length' => strlen($message),
                            'payload' => $payload
                        ]);

                        $response = Http::timeout(30)
                            ->withHeaders($headers)
                            ->post($this->apiUrl, $payload);

                        $result = $response->json();

                        if ($response->successful()) {
                            if (isset($result['status']) && $result['status'] === true) {
                                Log::info('Fonnte message sent successfully', [
                                    'phone' => $formattedPhone,
                                    'response' => $result,
                                    'message_id' => $result['id'] ?? null
                                ]);
                                return [
                                    'success' => true,
                                    'message' => 'Pesan berhasil dikirim',
                                    'response' => $result
                                ];
                            } else {
                                Log::error('Fonnte API returned false status', [
                                    'phone' => $formattedPhone,
                                    'response' => $result,
                                    'status_code' => $response->status()
                                ]);
                                return [
                                    'success' => false,
                                    'message' => $result['message'] ?? 'Gagal mengirim pesan',
                                    'details' => $result,
                                    'status_code' => $response->status()
                                ];
                            }
                        } else {
                            Log::error('Fonnte API HTTP error', [
                                'phone' => $formattedPhone,
                                'response' => $result,
                                'status_code' => $response->status(),
                                'headers' => $response->headers()
                            ]);
                            return [
                                'success' => false,
                                'message' => 'HTTP Error: ' . $response->status() . ' - ' . ($result['message'] ?? 'Unknown error'),
                                'details' => $result,
                                'status_code' => $response->status()
                            ];
                        }
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        Log::error('Fonnte API connection timeout', [
                            'phone' => $phone,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Koneksi timeout ke server Fonnte. Silakan coba lagi.',
                            'error_type' => 'connection_timeout'
                        ];
                    } catch (\Exception $e) {
                        Log::error('Fonnte service exception', [
                            'phone' => $phone,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                            'error_type' => 'exception'
                        ];
                    }
                }

                /**
                 * Format nomor telepon untuk Fonnte
                 */
                private function formatPhoneNumber(string $phone): string
                {
                    try {
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
                            $formatted = '62' . substr($phone, 1);
                            // Validasi panjang minimal
                            if (strlen($formatted) >= 11) {
                                return $formatted;
                            }
                        }

                        // Jika sudah 11-15 digit tanpa prefix
                        if (strlen($phone) >= 11 && strlen($phone) <= 15) {
                            // Cek apakah sudah memiliki kode negara
                            if (!str_starts_with($phone, '62')) {
                                return '62' . $phone;
                            }
                            return $phone;
                        }

                        // Jika kurang dari 10 digit, anggap tidak valid
                        Log::warning('Nomor telepon terlalu pendek', ['phone' => $phone, 'length' => strlen($phone)]);
                        return '';
                    } catch (\Exception $e) {
                        Log::error('Error formatting phone number', [
                            'phone' => $phone,
                            'error' => $e->getMessage()
                        ]);
                        return '';
                    }
                }

                /**
                 * Kirim OTP via WhatsApp
                 */
                public function sendOtp(string $phone, string $otp, string $recipientName = ''): array
                {
                    $name = !empty($recipientName) ? $recipientName : 'Pengguna';

                    $message = "Kode OTP Reset Password PPDB Pondok Pesantren Bani Syahid\n\n"
                             . "Halo {$name},\n\n"
                             . "Kode OTP Anda: *{$otp}*\n"
                             . "Kode ini berlaku selama 10 menit.\n\n"
                             . "🔒 Keamanan:\n"
                             . "• Jangan berikan kode ini kepada siapapun\n"
                             . "• Kode ini hanya untuk reset password\n"
                             . "• Jika Anda tidak meminta reset password, abaikan pesan ini\n\n"
                             . "Terima kasih,\n"
                             . "Panitia PPDB Pondok Pesantren Bani Syahid";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi penolakan pendaftaran
                 */
                public function sendRegistrationRejection(string $phone, string $namaSantri, string $alasan): array
                {
                   $message = "PEMBERITAHUAN PERBAIKAN DATA PENDAFTARAN\n\n"
                            . "Kepada Yth.\n"
                            . "Bapak/Ibu Orang Tua/Wali Santri\n"
                            . "Atas Nama: *{$namaSantri}*\n\n"
                            . "*Assalamu'alaikum Warahmatullahi Wabarakatuh*\n\n"
                            . "Setelah kami melakukan pemeriksaan, kami menemukan bahwa data pendaftaran calon santri *{$namaSantri}* *BELUM DAPAT DIPROSES LEBIH LANJUT KARENA :*\n\n"
                            . " *{$alasan}*\n\n"
                            . "*TINDAK LANJUT YANG DIPERLUKAN:*\n"
                            . "1. Login ke sistem PPDB\n"
                            . "2. Melengkapi data yang masih kurang\n"
                            . "3. Memperbaiki data yang tidak sesuai\n"
                            . "4. Mengunggah ulang dokumen yang diperlukan\n\n"
                            . "Setelah memperbaiki data, Anda dapat mengirimkan kembali untuk kami proses kembali.\n\n"
                            . "Terima kasih atas perhatian dan kerja samanya.\n\n"
                            . "*Wassalamu'alaikum Warahmatullahi Wabarakatuh*\n"
                            . "Panitia PPDB\n"
                            . "Pondok Pesantren Al Quran Bani Syahid";

                    return $this->sendMessage($phone, $message, ['delay' => 2]);
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
                             . "💡 Solusi:\n"
                             . "1. Periksa saldo/kartu kredit Anda\n"
                             . "2. Coba metode pembayaran lain\n"
                             . "3. Hubungi admin untuk bantuan\n\n"
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
                             . "🔄 Langkah Selanjutnya:\n"
                             . "1. Buat pembayaran baru melalui dashboard PPDB\n"
                             . "2. Pilih metode pembayaran yang tersedia\n"
                             . "3. Selesaikan sebelum batas waktu baru\n\n"
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
                             . "💰 Cara Bayar:\n"
                             . "1. Klik link di atas\n"
                             . "2. Pilih metode pembayaran\n"
                             . "3. Ikuti instruksi\n"
                             . "4. Simpan bukti pembayaran\n\n"
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
                             . "📍 Lokasi Pembayaran:\n"
                             . "Pondok Pesantren Al-Qur'an Bani Syahid\n"
                             . "Alamat: [Isi alamat lengkap]\n\n"
                             . "⏰ Waktu Kunjungan:\n"
                             . "• Senin - Jumat: 08:00 - 16:00 WIB\n"
                             . "• Sabtu: 08:00 - 14:00 WIB\n"
                             . "• Minggu: Libur\n\n"
                             . "📝 Persiapan:\n"
                             . "1. Bawa bukti ID pendaftaran ini\n"
                             . "2. Bawa uang pas sejumlah Rp {$amount}\n"
                             . "3. Datang ke lokasi sesuai jam kerja\n\n"
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
                             . "• Dikonfirmasi oleh: {$adminName}\n"
                             . "• Tanggal Konfirmasi: " . date('d/m/Y H:i') . "\n\n"
                             . "🎉 Selamat! Pendaftaran Anda telah aktif.\n"
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
                             . "• Batas Waktu: {$expiryDate}\n"
                             . "• Status: BELUM LUNAS\n\n"
                             . "⏰ Segera selesaikan pembayaran sebelum batas waktu habis.\n\n"
                             . "🔗 Akses dashboard: [link dashboard]\n\n"
                             . "Salam,\n"
                             . "Panitia PPDB";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim pengumuman kelulusan
                 */
                public function sendGraduationAnnouncement(string $phone, string $namaSantri, array $details = []): array
                {
                    $program = $details['program'] ?? 'Pondok Pesantren Bani Syahid';
                    $tanggalDaftarUlang = $details['tanggal_daftar_ulang'] ?? 'akan diinformasikan kemudian';
                    $kontakAdmin = $details['kontak_admin'] ?? 'admin@pondokpesantren.com';

                    $message = "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\n"
                             . "Kepada Yth. Calon Santri atas nama:\n"
                             . "*{$namaSantri}*\n\n"
                             . "Dengan memanjatkan puji syukur ke hadirat Allah Subhanahu Wa Ta'ala, kami sampalkan kabar gembira. \n"
                             . "Berdasarkan hasil evaluasi Ujian Tulis Seleksi Masuk yang telah Anda ikuti, \n"
                             . "Anda *DINYATAKAN LULUS* dan diterima sebagai santri di:\n"
                             . "*{$program}*\n\n"
                             . "🎉 SELAMAT! 🎉\n\n"
                             . "📋 INFORMASI PENDAFTARAN ULANG:\n"
                             . "• Anda dipersilakan untuk segera melengkapi administrasi pendaftaran ulang\n"
                             . "• Waktu: {$tanggalDaftarUlang}\n"
                             . "• Informasi lebih lanjut akan disampaikan melalui kontak yang terdaftar\n\n"
                             . "📞 KONTAK:\n"
                             . "Jika ada pertanyaan, hubungi:\n"
                             . "{$kontakAdmin}\n\n"
                             . "Terima kasih atas kepercayaan Anda.\n\n"
                             . "Wassalamu'alaikum Warahmatullahi Wabarakatuh,\n"
                             . "Panitia PPDB\n"
                             . "Pondok Pesantren Bani Syahid";

                    return $this->sendMessage($phone, $message, ['delay' => 1]);
                }

                /**
                 * Kirim undangan tes seleksi
                 */
                public function sendSelectionTestInvitation(string $phone, string $namaSantri, array $details = []): array
                {
                    $tanggal = $details['tanggal'] ?? 'akan diinformasikan kemudian';
                    $waktu = $details['waktu'] ?? '08:00 WIB';
                    $tempat = $details['tempat'] ?? 'Pondok Pesantren Bani Syahid';
                    $yangHarusDibawa = $details['yang_harus_dibawa'] ?? 'Alat tulis dan identitas diri';

                    $message = "UNDANGAN TES SELEKSI MASUK\n\n"
                             . "Kepada Yth. Calon Santri:\n"
                             . "*{$namaSantri}*\n\n"
                             . "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n"
                             . "Anda diundang untuk mengikuti Tes Seleksi Masuk Pondok Pesantren Bani Syahid.\n\n"
                             . "📅 JADWAL TES:\n"
                             . "• Tanggal: {$tanggal}\n"
                             . "• Waktu: {$waktu}\n"
                             . "• Tempat: {$tempat}\n\n"
                             . "📝 PERSIAPAN:\n"
                             . "• {$yangHarusDibawa}\n"
                             . "• Datang 30 menit lebih awal\n"
                             . "• Mengenakan pakaian sopan dan rapi\n\n"
                             . "⚠️ CATATAN PENTING:\n"
                             . "• Keterlambatan tidak ditoleransi\n"
                             . "• Harap konfirmasi kehadiran\n"
                             . "• Bawa semua dokumen yang diperlukan\n\n"
                             . "Terima kasih,\n"
                             . "Panitia Seleksi PPDB\n"
                             . "Pondok Pesantren Bani Syahid";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim pesan custom dengan template
                 */
                public function sendCustomMessage(string $phone, string $template, array $variables = []): array
                {
                    $message = $this->compileTemplate($template, $variables);
                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim instruksi transfer bank
                 */
                public function sendBankTransferInstruction(string $phone, string $namaSantri, string $paymentCode, string $amount, string $senderName): array
                {
                    $message = "Assalamualaikum {$namaSantri},\n\n";
                    $message .= "Bukti transfer Anda telah kami terima dengan detail:\n";
                    $message .= "• Kode Pembayaran: {$paymentCode}\n";
                    $message .= "• Jumlah: Rp {$amount}\n";
                    $message .= "• Atas Nama: {$senderName}\n\n";
                    $message .= "Kami sedang memverifikasi bukti transfer Anda. Silakan tunggu konfirmasi dari admin dalam 1-2 jam kerja.\n\n";
                    $message .= "Jika ada pertanyaan, hubungi kami di nomor ini.\n\n";
                    $message .= "Terima kasih,\nAdmin Pesantren Al-Qur'an Bani Syahid";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi verifikasi transfer bank berhasil
                 */
                public function sendBankTransferVerified(string $phone, string $namaSantri, string $paymentCode, string $amount, string $adminName): array
                {
                    $message = "Assalamualaikum {$namaSantri},\n\n";
                    $message .= "Pembayaran transfer bank Anda telah berhasil diverifikasi! ✅\n\n";
                    $message .= "Detail Pembayaran:\n";
                    $message .= "• Kode Pembayaran: {$paymentCode}\n";
                    $message .= "• Jumlah: Rp {$amount}\n";
                    $message .= "• Status: Lunas\n";
                    $message .= "• Diverifikasi oleh: {$adminName}\n\n";
                    $message .= "Pendaftaran Anda telah diterima. Silakan cek dashboard untuk informasi lebih lanjut.\n\n";
                    $message .= "Terima kasih,\nAdmin Pesantren Al-Qur'an Bani Syahid";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Kirim notifikasi penolakan transfer bank
                 */
                public function sendBankTransferRejected(string $phone, string $namaSantri, string $paymentCode, string $amount, string $rejectionReason): array
                {
                    $message = "Assalamualaikum {$namaSantri},\n\n";
                    $message .= "Maaf, bukti transfer Anda tidak dapat kami verifikasi. ❌\n\n";
                    $message .= "Detail Pembayaran:\n";
                    $message .= "• Kode Pembayaran: {$paymentCode}\n";
                    $message .= "• Jumlah: Rp {$amount}\n";
                    $message .= "• Status: Ditolak\n\n";
                    $message .= "Alasan Penolakan:\n{$rejectionReason}\n\n";
                    $message .= "Silakan upload kembali bukti transfer yang benar, atau hubungi admin untuk bantuan lebih lanjut.\n\n";
                    $message .= "Terima kasih,\nAdmin Pesantren Al-Qur'an Bani Syahid";

                    return $this->sendMessage($phone, $message);
                }

                /**
                 * Compile template dengan variables
                 */
                private function compileTemplate(string $template, array $variables): string
                {
                    foreach ($variables as $key => $value) {
                        $template = str_replace("{{{$key}}}", $value, $template);
                    }
                    return $template;
                }

                /**
                 * Cek status koneksi API Fonnte
                 */
                public function checkConnection(): array
                {
                    try {
                        // Persiapan headers
                        $headers = [];
                        if (!empty($this->token)) {
                            $headers['Authorization'] = $this->token;
                        } else {
                            $headers['Authorization'] = $this->apiKey;
                        }

                        $response = Http::timeout(10)
                            ->withHeaders($headers)
                            ->get('https://api.fonnte.com/me');

                        if ($response->successful()) {
                            $result = $response->json();
                            if (isset($result['status']) && $result['status'] === true) {
                                return [
                                    'success' => true,
                                    'message' => 'Koneksi API Fonnte berhasil',
                                    'data' => $result
                                ];
                            } else {
                                return [
                                    'success' => false,
                                    'message' => 'API Fonnte merespon dengan status false',
                                    'data' => $result
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'message' => 'Gagal terhubung ke API Fonnte. Status: ' . $response->status(),
                                'status_code' => $response->status()
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Fonnte connection check failed', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage(),
                            'error_type' => 'connection_failed'
                        ];
                    }
                }

                /**
                 * Cek kuota pesan tersisa
                 */
                public function checkQuota(): array
                {
                    try {
                        // Persiapan headers
                        $headers = [];
                        if (!empty($this->token)) {
                            $headers['Authorization'] = $this->token;
                        } else {
                            $headers['Authorization'] = $this->apiKey;
                        }

                        $response = Http::timeout(10)
                            ->withHeaders($headers)
                            ->get('https://api.fonnte.com/quota');

                        if ($response->successful()) {
                            $result = $response->json();
                            if (isset($result['status']) && $result['status'] === true) {
                                return [
                                    'success' => true,
                                    'message' => 'Berhasil mendapatkan informasi kuota',
                                    'quota' => $result['quota'] ?? null,
                                    'used' => $result['used'] ?? null,
                                    'remaining' => $result['remaining'] ?? null,
                                    'data' => $result
                                ];
                            } else {
                                return [
                                    'success' => false,
                                    'message' => 'Gagal mendapatkan informasi kuota',
                                    'data' => $result
                                ];
                            }
                        } else {
                            return [
                                'success' => false,
                                'message' => 'Gagal terhubung untuk cek kuota',
                                'status_code' => $response->status()
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Fonnte quota check failed', [
                            'error' => $e->getMessage()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage()
                        ];
                    }
                }

                /**
                 * Kirim pesan broadcast ke multiple numbers
                 */
                public function sendBroadcast(array $phoneNumbers, string $message, array $options = []): array
                {
                    $results = [];
                    $successCount = 0;
                    $failedCount = 0;

                    foreach ($phoneNumbers as $phone) {
                        $result = $this->sendMessage($phone, $message, $options);
                        $results[] = [
                            'phone' => $phone,
                            'success' => $result['success'],
                            'message' => $result['message']
                        ];

                        if ($result['success']) {
                            $successCount++;
                        } else {
                            $failedCount++;
                        }

                        // Delay antar pengiriman untuk menghindari rate limit
                        usleep(500000); // 0.5 detik
                    }

                    return [
                        'success' => $successCount > 0,
                        'total' => count($phoneNumbers),
                        'success_count' => $successCount,
                        'failed_count' => $failedCount,
                        'results' => $results
                    ];
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file (optional)
        $this->publishes([
            __DIR__.'/../config/fonnte.php' => config_path('fonnte.php'),
        ], 'fonnte-config');
    }
}
