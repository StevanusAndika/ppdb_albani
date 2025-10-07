<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle manual login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user ada
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput();
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->withInput();
        }

        // Cek apakah user adalah socialite user yang mencoba login manual
        if ($user->isSocialiteUser()) {
            return back()->withErrors([
                'email' => 'Akun ini terdaftar melalui ' . ucfirst($user->provider_name) . '. Silakan login menggunakan ' . ucfirst($user->provider_name) . '.',
            ])->withInput();
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ])->withInput();
    }

    /**
     * Handle manual registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => 'calon_santri', // Default role untuk registrasi manual
                'is_active' => true,
                'provider_id' => null,
                'provider_name' => null,
                'email_verified_at' => null, // Email belum terverifikasi untuk registrasi manual
            ]);

            DB::commit();

            // Auto login setelah registrasi
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect to provider for socialite login
     */
    public function redirectToProvider($provider)
    {
        $allowedProviders = ['google', 'facebook', 'github']; // Tambahkan provider lain jika needed

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'Provider tidak didukung.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback for socialite login
     */
    public function handleProvideCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan ' . $provider . ': ' . $e->getMessage());
        }

        // find or create user and send params user get from socialite and provider
        $authUser = $this->findOrCreateUser($socialUser, $provider);

        // Cek apakah user aktif
        if (!$authUser->is_active) {
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // login user
        Auth::login($authUser, true);

        // redirect ke dashboard
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Find or create user for socialite login
     */
    public function findOrCreateUser($socialUser, $provider)
    {
        // Cari user berdasarkan email dari socialite
        $user = User::where('email', $socialUser->getEmail())->first();

        // Jika user sudah ada
        if ($user) {
            // Jika user sudah ada dengan provider yang sama, update provider info jika perlu
            if ($user->provider_name !== $provider || $user->provider_id !== $socialUser->getId()) {
                $user->update([
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $provider
                ]);
            }

            return $user;
        }

        // Jika user belum ada, create user baru
        $user = User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(16)), // Password random karena login via socialite
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
            'email_verified_at' => now(), // Verifikasi email otomatis karena dari provider terpercaya
            'role' => 'calon_santri', // Default role untuk user socialite
            'is_active' => true,
        ]);

        return $user;
    }

    /**
     * Check if email exists (for AJAX validation)
     */
    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah terdaftar.' : 'Email tersedia.'
        ]);
    }
}
