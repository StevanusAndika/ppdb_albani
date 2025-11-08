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

class PasswordResetController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = app('fonnte');
    }

    /**
     * Show the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request - Kirim OTP via WhatsApp
     */
    public function sendResetLinkEmail(Request $request)
    {
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

        // Cek cooldown pengiriman OTP (1 menit untuk testing, bisa diubah ke 10 menit)
        $cooldownKey = 'otp_cooldown_' . $user->email;
        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            $minutes = ceil($remainingTime / 60);

            return back()
                ->withInput()
                ->with('error', "Anda sudah meminta OTP. Silakan tunggu {$minutes} menit sebelum meminta OTP lagi.");
        }

        try {
            // Generate OTP 6 digit
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            Log::info('Generated OTP', [
                'email' => $user->email,
                'otp' => $otp,
                'time' => now()->toDateTimeString()
            ]);

            // Hapus OTP sebelumnya untuk email ini
            PasswordResetOtp::where('email', $user->email)->delete();

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
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false,
            ]);

            Log::info('OTP saved to database', [
                'email' => $user->email,
                'otp_id' => $passwordResetOtp->id,
                'expires_at' => $passwordResetOtp->expires_at
            ]);

            // Kirim OTP via WhatsApp
            Log::info('Sending OTP via Fonnte', [
                'phone' => $formattedPhone,
                'otp' => $otp
            ]);

            $sendResult = $this->fonnteService->sendOtp($formattedPhone, $otp);

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

            // Set cooldown 1 menit untuk testing (ubah ke 10 menit di production)
            Cache::put($cooldownKey, time() + 60, 60); // 1 menit untuk testing

            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $user->email)
                ->with('otp_code', $otp) // Hanya untuk debugging, hapus di production
                ->with('success', 'Kode OTP telah dikirim via WhatsApp ke nomor terdaftar. OTP berlaku 10 menit.');

        } catch (\Exception $e) {
            Log::error('Error sending OTP: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi OTP dengan debugging
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
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

        // Debug: Tampilkan semua OTP yang ada di database untuk email ini
        $allOtps = PasswordResetOtp::where('email', $request->email)->get();
        Log::info('All OTPs in database for this email', [
            'count' => $allOtps->count(),
            'otps' => $allOtps->map(function($otp) {
                return [
                    'id' => $otp->id,
                    'otp' => $otp->otp,
                    'is_used' => $otp->is_used,
                    'expires_at' => $otp->expires_at,
                    'created_at' => $otp->created_at
                ];
            })
        ]);

        // Cek OTP dengan kondisi yang lebih longkar untuk debugging
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        Log::info('OTP record found', [
            'exists' => !is_null($otpRecord),
            'is_used' => $otpRecord ? $otpRecord->is_used : 'N/A',
            'expired' => $otpRecord ? $otpRecord->expires_at->isPast() : 'N/A',
            'current_time' => now()->toDateTimeString(),
            'expires_at' => $otpRecord ? $otpRecord->expires_at->toDateTimeString() : 'N/A'
        ]);

        if (!$otpRecord) {
            Log::warning('OTP not found in database', [
                'email' => $request->email,
                'otp' => $request->otp
            ]);
            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email)
                ->with('error', 'Kode OTP tidak ditemukan.');
        }

        if ($otpRecord->is_used) {
            Log::warning('OTP already used', [
                'email' => $request->email,
                'otp' => $request->otp
            ]);
            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email)
                ->with('error', 'Kode OTP sudah digunakan sebelumnya.');
        }

        if ($otpRecord->expires_at->isPast()) {
            Log::warning('OTP expired', [
                'email' => $request->email,
                'otp' => $request->otp,
                'expires_at' => $otpRecord->expires_at,
                'current_time' => now()
            ]);
            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email)
                ->with('error', 'Kode OTP sudah kadaluarsa.');
        }

        // Tandai OTP sebagai digunakan
        $otpRecord->update(['is_used' => true]);

        Log::info('OTP verified successfully', [
            'email' => $request->email,
            'otp' => $request->otp
        ]);

        return back()
            ->withInput()
            ->with('show_password_reset', true)
            ->with('user_email', $request->email)
            ->with('otp_verified', true)
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
            'otp' => 'required|digits:6',
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

        // Verifikasi ulang OTP untuk keamanan
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', true)
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi reset password tidak valid. Silakan ulangi proses dari awal.');
        }

        // Cek user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'User tidak ditemukan.');
        }

        try {
            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Hapus OTP record setelah berhasil reset
            PasswordResetOtp::where('email', $request->email)->delete();

            // Hapus cooldown cache
            Cache::forget('otp_cooldown_' . $request->email);

            Log::info('Password reset successful', [
                'email' => $request->email,
                'user_id' => $user->id
            ]);

            return redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');

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

        // Cek cooldown pengiriman OTP
        $cooldownKey = 'otp_cooldown_' . $user->email;
        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            $minutes = ceil($remainingTime / 60);

            return response()->json([
                'success' => false,
                'message' => "Anda sudah meminta OTP. Silakan tunggu {$minutes} menit sebelum meminta OTP lagi."
            ], 429);
        }

        try {
            // Generate OTP baru
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Hapus OTP sebelumnya
            PasswordResetOtp::where('email', $user->email)->delete();

            // Format nomor telepon
            $formattedPhone = $this->formatPhoneNumber($user->phone_number);

            // Simpan OTP baru
            $passwordResetOtp = PasswordResetOtp::create([
                'email' => $user->email,
                'otp' => $otp,
                'phone_number' => $formattedPhone,
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false,
            ]);

            // Kirim OTP
            $sendResult = $this->fonnteService->sendOtp($formattedPhone, $otp);

            if (!$sendResult['success']) {
                $passwordResetOtp->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP: ' . $sendResult['message']
                ], 500);
            }

            // Set cooldown 1 menit untuk testing (ubah ke 10 menit di production)
            Cache::put($cooldownKey, time() + 60, 60); // 1 menit untuk testing

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim.'
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
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Debug function untuk melihat OTP di database
     */
    public function debugOtp(Request $request)
    {
        $email = $request->email;
        $otps = PasswordResetOtp::where('email', $email)->get();

        return response()->json([
            'email' => $email,
            'otp_count' => $otps->count(),
            'otps' => $otps->map(function($otp) {
                return [
                    'id' => $otp->id,
                    'otp' => $otp->otp,
                    'is_used' => $otp->is_used,
                    'expires_at' => $otp->expires_at->toDateTimeString(),
                    'created_at' => $otp->created_at->toDateTimeString(),
                    'is_expired' => $otp->expires_at->isPast()
                ];
            })
        ]);
    }
}
