<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class PasswordResetController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = app('fonnte');
    }

    /**
     * Generate OTP 6 digit random
     */
    private function generateOtp(): string
    {
        // Generate OTP 6 digit random
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Format sisa waktu dengan pembulatan ke atas
     */
    private function formatRemainingTime(Carbon $expiryTime): string
    {
        $now = Carbon::now();

        if ($now >= $expiryTime) {
            return "0 menit"; // Sudah expired
        }

        // Hitung sisa waktu dalam detik
        $remainingSeconds = $now->diffInSeconds($expiryTime, false);

        // Bulatkan ke atas ke menit terdekat
        $remainingMinutes = ceil($remainingSeconds / 60);

        return "{$remainingMinutes} menit";
    }

    /**
     * Format pesan WhatsApp untuk OTP
     */
    private function formatOtpMessage(string $otp): string
    {
        return "Kode OTP Reset Password PPDB Pondok Pesantren Al-Qur'an Bani Syahid: *{$otp}*\n\nJangan berikan kode ini kepada siapapun.\n\nJika Anda tidak meminta reset password, silahkan hubungi admin atau ubah password anda dilain waktu.";
    }

    /**
     * Check OTP cooldown untuk AJAX request
     */
    public function checkCooldown(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.'
            ], 400);
        }

        $cooldownKey = 'otp_cooldown_' . $request->email;

        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            $minutes = ceil($remainingTime / 60);

            return response()->json([
                'success' => false,
                'on_cooldown' => true,
                'remaining_time' => $remainingTime,
                'minutes' => $minutes,
                'message' => "Silakan tunggu {$minutes} menit sebelum meminta OTP lagi."
            ]);
        }

        return response()->json([
            'success' => true,
            'on_cooldown' => false,
            'message' => 'Bisa meminta OTP baru.'
        ]);
    }

    /**
     * Validate reCAPTCHA v3 - Skip di local jika tidak ada keys
     */
    private function validateRecaptcha(Request $request): bool
    {
        $recaptchaSecret = config('services.recaptcha.secret_key');
        $recaptchaResponse = $request->input('g-recaptcha-response');

        // Jika di local environment dan tidak ada secret key, skip validation
        if (app()->environment('local', 'testing') && empty($recaptchaSecret)) {
            Log::info('reCAPTCHA validation skipped in local environment (no secret key)');
            return true;
        }

        // Jika tidak ada response token, return false
        if (!$recaptchaResponse) {
            Log::warning('reCAPTCHA token missing');
            return false;
        }

        if (!$recaptchaSecret) {
            Log::warning('reCAPTCHA secret key missing');
            return false;
        }

        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip(),
                ]
            ]);

            $body = json_decode($response->getBody());

            Log::info('reCAPTCHA Response', [
                'success' => $body->success,
                'score' => $body->score ?? 0,
                'action' => $body->action ?? null,
                'hostname' => $body->hostname ?? null,
                'errors' => $body->{'error-codes'} ?? [],
                'environment' => app()->environment()
            ]);

            // Validasi success
            if (!$body->success) {
                return false;
            }

            // Validasi score minimal
            $scoreThreshold = config('services.recaptcha.score_threshold', 0.7);
            if (!isset($body->score) || $body->score < $scoreThreshold) {
                Log::warning('reCAPTCHA score too low', [
                    'score' => $body->score,
                    'threshold' => $scoreThreshold
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('reCAPTCHA validation error: ' . $e->getMessage());
            // Di local, jika error connection dan tidak ada secret key, skip validation
            if (app()->environment('local', 'testing') && empty($recaptchaSecret)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Get reCAPTCHA site key untuk view
     */
    private function getRecaptchaSiteKey(): string
    {
        return config('services.recaptcha.site_key', '');
    }

    /**
     * Check if reCAPTCHA is enabled
     */
    private function isRecaptchaEnabled(): bool
    {
        $siteKey = config('services.recaptcha.site_key');
        $secretKey = config('services.recaptcha.secret_key');

        // Di local, jika tidak ada keys, anggap tidak enabled
        if (app()->environment('local', 'testing') && (empty($siteKey) || empty($secretKey))) {
            return false;
        }

        return !empty($siteKey) && !empty($secretKey);
    }

    /**
     * Show the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password', [
            'recaptcha_site_key' => $this->getRecaptchaSiteKey(),
            'recaptcha_enabled' => $this->isRecaptchaEnabled()
        ]);
    }

    /**
     * Handle forgot password request - Kirim OTP via WhatsApp
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi reCAPTCHA jika enabled
        if ($this->isRecaptchaEnabled() && !$this->validateRecaptcha($request)) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput()
                ->with('error', 'Verifikasi keamanan gagal.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Format email tidak valid.');
        }

        // Cek apakah user terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->with('error', 'Email tidak ditemukan dalam sistem.');
        }

        // Cek apakah user memiliki nomor telepon
        if (!$user->phone_number) {
            return back()
                ->withInput()
                ->with('error', 'Akun tidak memiliki nomor telepon yang terdaftar. Silakan hubungi administrator.');
        }

        // Cek apakah sudah ada OTP yang masih aktif (belum expired)
        $activeOtp = PasswordResetOtp::where('email', $user->email)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeOtp) {
            // Tampilkan sisa waktu OTP yang masih aktif - DIBULATKAN KE ATAS
            $remainingTime = $this->formatRemainingTime($activeOtp->expires_at);

            Log::info('Active OTP found', [
                'email' => $user->email,
                'otp' => $activeOtp->otp,
                'remaining_time' => $remainingTime
            ]);

            $message = 'Anda sudah memiliki OTP aktif. ';

            if ($activeOtp->is_used) {
                $message .= 'OTP sudah diverifikasi. ';
            } else {
                $message .= 'OTP belum digunakan. ';
            }

            $message .= 'Sisa waktu: ' . $remainingTime;

            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $user->email)
                ->with('info', $message);
        }

        // Cek cooldown pengiriman OTP (hanya untuk mencegah spam)
        $cooldownKey = 'otp_cooldown_' . $user->email;
        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            $minutes = ceil($remainingTime / 60); // Dibulatkan ke atas

            return back()
                ->withInput()
                ->with('error', "Anda sudah meminta OTP baru. Silakan tunggu {$minutes} menit sebelum meminta OTP lagi.");
        }

        try {
            // Generate OTP 6 digit random
            $otp = $this->generateOtp();

            // Waktu berlaku: 5 MENIT
            $expiresAt = Carbon::now()->addMinutes(5);

            Log::info('Generated 6-digit OTP', [
                'email' => $user->email,
                'otp' => $otp,
                'expires_at' => $expiresAt->toDateTimeString(),
                'valid_for' => '5 menit',
                'time' => now()->toDateTimeString()
            ]);

            // Hapus OTP yang sudah expired
            PasswordResetOtp::where('email', $user->email)
                ->where('expires_at', '<=', Carbon::now())
                ->delete();

            // Format nomor telepon
            $formattedPhone = $this->formatPhoneNumber($user->phone_number);

            Log::info('Formatted phone number', [
                'original' => $user->phone_number,
                'formatted' => $formattedPhone
            ]);

            // Simpan OTP ke database
            $passwordResetOtp = PasswordResetOtp::create([
                'email' => $user->email,
                'otp' => $otp,
                'phone_number' => $formattedPhone,
                'expires_at' => $expiresAt,
                'is_used' => false,
            ]);

            Log::info('OTP saved to database', [
                'email' => $user->email,
                'otp_id' => $passwordResetOtp->id,
                'otp' => $otp,
                'expires_at' => $passwordResetOtp->expires_at
            ]);

            // Kirim OTP via WhatsApp
            Log::info('Sending OTP via Fonnte', [
                'phone' => $formattedPhone,
                'otp' => $otp
            ]);

            // Format pesan WhatsApp sesuai permintaan
            $whatsappMessage = $this->formatOtpMessage($otp);

            // Kirim pesan dengan format khusus
            $sendResult = $this->fonnteService->sendMessage($formattedPhone, $whatsappMessage);

            Log::info('Fonnte response', [
                'success' => $sendResult['success'],
                'message' => $sendResult['message']
            ]);

            if (!$sendResult['success']) {
                // Hapus OTP jika gagal dikirim
                $passwordResetOtp->delete();

                return back()
                    ->withInput()
                    ->with('error', 'Gagal mengirim OTP: ' . $sendResult['message']);
            }

            // Set cooldown 1 menit untuk mencegah spam
            Cache::put($cooldownKey, time() + 60, 60); // 1 menit cooldown

            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $user->email)
                ->with('success', "Kode OTP telah dikirim via WhatsApp ke nomor terdaftar. OTP berlaku 5 menit.");

        } catch (\Exception $e) {
            Log::error('Error sending OTP: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi beberapa saat.');
        }
    }

    /**
     * Verifikasi OTP - OTP bisa digunakan meskipun sudah digunakan sebelumnya
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6', // Tetap 6 digit
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email);
        }

        Log::info('OTP verification attempt', [
            'email' => $request->email,
            'otp_input' => $request->otp,
            'time' => now()->toDateTimeString()
        ]);

        // Cek OTP yang belum expired (boleh sudah digunakan atau belum)
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            Log::warning('OTP not found or expired', [
                'email' => $request->email,
                'otp' => $request->otp,
                'available_otps' => PasswordResetOtp::where('email', $request->email)->get()->map(function($otp) {
                    return [
                        'otp' => $otp->otp,
                        'is_used' => $otp->is_used,
                        'expires_at' => $otp->expires_at,
                        'is_expired' => $otp->expires_at <= Carbon::now(),
                    ];
                })
            ]);

            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email)
                ->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa. Silakan minta OTP baru.');
        }

        // Tandai OTP sebagai digunakan (atau update timestamp jika sudah digunakan)
        $otpRecord->update([
            'is_used' => true,
            'used_at' => Carbon::now()
        ]);

        Log::info('OTP verified successfully', [
            'email' => $request->email,
            'otp' => $request->otp,
            'was_previously_used' => $otpRecord->wasRecentlyCreated ? 'no' : 'yes'
        ]);

        return back()
            ->withInput()
            ->with('show_password_reset', true)
            ->with('user_email', $request->email)
            ->with('otp_verified', true)
            ->with('otp_code', $request->otp) // Simpan OTP untuk verifikasi di reset
            ->with('success', 'OTP berhasil diverifikasi. Silakan buat password baru.');
    }

    /**
     * Handle password reset setelah OTP terverifikasi
     */
    public function reset(Request $request)
    {
        // Validasi input dengan password strength
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6', // Tetap 6 digit
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.regex' => 'Password harus mengandung minimal 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 simbol.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_password_reset', true)
                ->with('user_email', $request->email);
        }

        // Verifikasi ulang OTP untuk keamanan - OTP harus belum expired
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            Log::warning('Invalid OTP for password reset', [
                'email' => $request->email,
                'otp' => $request->otp,
                'time' => now()->toDateTimeString()
            ]);

            return redirect()->route('password.request')
                ->with('error', 'Sesi reset password tidak valid atau sudah kadaluarsa. Silakan ulangi proses dari awal.');
        }

        // Cek user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'User tidak ditemukan.');
        }

        try {
            // Update password dan unlock account (reset lock 5 menit)
            $user->password = Hash::make($request->new_password);
            $user->login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_attempt = null;
            $user->is_active = true;
            $user->save();

            // Hapus SEMUA OTP record untuk email ini setelah berhasil reset
            PasswordResetOtp::where('email', $request->email)->delete();

            // Hapus cooldown cache
            Cache::forget('otp_cooldown_' . $request->email);

            Log::info('Password reset successful with account unlock', [
                'email' => $request->email,
                'user_id' => $user->id,
                'was_locked' => $user->locked_until ? 'yes' : 'no',
                'otp_used_count' => $otpRecord->is_used ? 'previously used' : 'first time use'
            ]);

            return redirect()->route('login')
                ->with('success', 'Password berhasil direset dan akun telah diaktifkan kembali! Silakan login dengan password baru.');

        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return redirect()->route('password.request')
                ->with('error', 'Terjadi kesalahan saat reset password. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP dengan cooldown
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        // Cek apakah sudah ada OTP yang masih aktif (belum expired)
        $activeOtp = PasswordResetOtp::where('email', $user->email)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeOtp) {
            $remainingTime = $this->formatRemainingTime($activeOtp->expires_at);

            $message = 'Anda masih memiliki OTP aktif. ';

            if ($activeOtp->is_used) {
                $message .= 'OTP sudah diverifikasi. ';
            } else {
                $message .= 'OTP belum digunakan. ';
            }

            $message .= 'Sisa waktu: ' . $remainingTime;

            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        // Cek cooldown pengiriman OTP
        $cooldownKey = 'otp_cooldown_' . $user->email;
        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            $minutes = ceil($remainingTime / 60); // Dibulatkan ke atas

            return response()->json([
                'success' => false,
                'message' => "Silakan tunggu {$minutes} menit sebelum meminta OTP lagi."
            ], 429);
        }

        try {
            // Generate OTP baru 6 digit
            $otp = $this->generateOtp();
            $expiresAt = Carbon::now()->addMinutes(5); // 5 menit

            // Hapus OTP yang sudah expired
            PasswordResetOtp::where('email', $user->email)
                ->where('expires_at', '<=', Carbon::now())
                ->delete();

            // Format nomor telepon
            $formattedPhone = $this->formatPhoneNumber($user->phone_number);

            // Simpan OTP baru
            $passwordResetOtp = PasswordResetOtp::create([
                'email' => $user->email,
                'otp' => $otp,
                'phone_number' => $formattedPhone,
                'expires_at' => $expiresAt,
                'is_used' => false,
            ]);

            // Format pesan WhatsApp sesuai permintaan
            $whatsappMessage = $this->formatOtpMessage($otp);

            // Kirim OTP dengan pesan yang jelas
            $sendResult = $this->fonnteService->sendMessage($formattedPhone, $whatsappMessage);

            if (!$sendResult['success']) {
                $passwordResetOtp->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP: ' . $sendResult['message']
                ], 500);
            }

            // Set cooldown 1 menit untuk mencegah spam
            Cache::put($cooldownKey, time() + 60, 60);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim. Berlaku 5 menit.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error resending OTP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format nomor telepon untuk Fonnte
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan +62, hapus +
        if (strpos($phone, '62') === 0) {
            return $phone;
        }

        // Jika diawali dengan 0, ganti dengan 62
        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Show reset form (untuk token-based reset - opsional)
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'recaptcha_site_key' => $this->getRecaptchaSiteKey(),
            'recaptcha_enabled' => $this->isRecaptchaEnabled()
        ]);
    }
}
