<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
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

        // Jika user ditemukan, tampilkan konfirmasi reset password
        return back()
            ->withInput()
            ->with('show_confirmation', true)
            ->with('user_email', $user->email);
    }

    /**
     * Handle password reset confirmation
     */
    public function reset(Request $request)
    {
        // Validasi input dengan password strength
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'action' => 'required|in:confirm,cancel',
            'new_password' => [
                'required_if:action,confirm',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'new_password.required_if' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.regex' => 'Password harus mengandung minimal 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 simbol.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_confirmation', true)
                ->with('user_email', $request->email);
        }

        // Jika user membatalkan reset
        if ($request->action === 'cancel') {
            return redirect()->route('password.request')
                ->with('info', 'Reset password dibatalkan.');
        }

        // Cek kembali apakah user terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'User tidak ditemukan.');
        }

        try {
            // Update password dengan yang baru
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');

        } catch (\Exception $e) {
            return redirect()->route('password.request')
                ->with('error', 'Terjadi kesalahan saat reset password. Silakan coba lagi.');
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
