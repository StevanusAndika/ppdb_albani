<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Providers\FonnteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
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

        try {
            // Generate OTP 6 digit
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Hapus OTP sebelumnya untuk email ini
            PasswordResetOtp::where('email', $user->email)->delete();

            // Simpan OTP ke database
            $passwordResetOtp = PasswordResetOtp::create([
                'email' => $user->email,
                'otp' => $otp,
                'phone_number' => $user->getFormattedPhoneNumber(),
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false,
            ]);

            // Kirim OTP via WhatsApp
            $sendResult = $this->fonnteService->sendOtp(
                $user->getFormattedPhoneNumber(),
                $otp
            );

            if (!$sendResult['success']) {
                // Hapus OTP jika gagal dikirim
                $passwordResetOtp->delete();

                return back()
                    ->withInput()
                    ->with('error', 'Gagal mengirim OTP: ' . $sendResult['message']);
            }

            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $user->email)
                ->with('success', 'Kode OTP telah dikirim via WhatsApp ke nomor terdaftar.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi OTP
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

        // Cek OTP
        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->valid()
            ->first();

        if (!$otpRecord) {
            return back()
                ->withInput()
                ->with('show_otp_verification', true)
                ->with('user_email', $request->email)
                ->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        }

        // Tandai OTP sebagai digunakan
        $otpRecord->markAsUsed();

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

            return redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');

        } catch (\Exception $e) {
            return redirect()->route('password.request')
                ->with('error', 'Terjadi kesalahan saat reset password. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP
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

        try {
            // Generate OTP baru
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Hapus OTP sebelumnya
            PasswordResetOtp::where('email', $user->email)->delete();

            // Simpan OTP baru
            $passwordResetOtp = PasswordResetOtp::create([
                'email' => $user->email,
                'otp' => $otp,
                'phone_number' => $user->getFormattedPhoneNumber(),
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false,
            ]);

            // Kirim OTP
            $sendResult = $this->fonnteService->sendOtp(
                $user->getFormattedPhoneNumber(),
                $otp
            );

            if (!$sendResult['success']) {
                $passwordResetOtp->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP: ' . $sendResult['message']
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show reset form (untuk token-based reset - opsional)
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
}
