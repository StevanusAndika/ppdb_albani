<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan form untuk request reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirim email reset password
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cek apakah user ada dan aktif
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        // Cek jika user login via socialite
        if ($user->isSocialiteUser()) {
            return back()->withErrors(['email' => 'Akun ini terdaftar melalui media sosial. Gunakan metode login yang sama.']);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Menampilkan form reset password
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Cek token reset password
        $tokenExists = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHours(2))
            ->first();

        if (!$tokenExists) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        // Verifikasi token
        if (!Hash::check($request->token, $tokenExists->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cari user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        if ($user->isSocialiteUser()) {
            return back()->withErrors(['email' => 'Akun ini terdaftar melalui media sosial. Tidak dapat mereset password.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Hapus token reset password
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Trigger event
        event(new PasswordReset($user));

        // Login otomatis setelah reset password
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('status', 'Password berhasil direset! Anda telah login secara otomatis.');
    }

    /**
     * Reset password untuk admin
     */
    public function adminResetPassword(Request $request, $userId)
    {
        // Hanya admin yang bisa akses
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($userId);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil direset untuk user ' . $user->name);
    }

    /**
     * Cek status reset password
     */
    public function checkResetStatus(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if ($token) {
            $createdAt = Carbon::parse($token->created_at);
            $expiresAt = $createdAt->addHours(2);

            return response()->json([
                'exists' => true,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
                'is_expired' => $expiresAt->isPast(),
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
