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
     * Check if user is already authenticated
     */
    private function redirectIfAuthenticated()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('santri.dashboard');
            }
        }
        return null;
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        return view('auth.register');
    }

    /**
     * Handle manual login - UPDATED: Menampilkan nama user di success message
     */
    public function login(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        // Cek apakah user ada
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput()->with('redirect_to_register', true);
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->withInput();
        }

        // Socialite user bisa login manual jika sudah punya password
        if ($user->isSocialiteUser() && !$user->canLoginManually()) {
            return back()->withErrors([
                'email' => 'Akun ini terdaftar melalui ' . ucfirst($user->provider_name) . '. Silakan login menggunakan ' . ucfirst($user->provider_name) . ' atau reset password untuk membuat password manual.',
            ])->withInput()->with('socialite_user_no_password', true);
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role dengan notifikasi yang menyertakan nama user
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name . '.');
            } else {
                return redirect()->route('santri.dashboard')->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name . '.');
            }
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ])->withInput();
    }

    /**
     * Handle manual registration - UPDATED: phone_number required & menampilkan nama user
     */
    public function register(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
            'phone_number.max' => 'Nomor telepon maksimal 15 digit.',
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
                'role' => 'calon_santri',
                'is_active' => true,
                'provider_id' => null,
                'provider_name' => null,
                'email_verified_at' => null,
            ]);

            DB::commit();

            // Redirect ke login dengan pesan sukses yang menyertakan nama user
            return redirect()->route('login')->with('success', 'Registrasi berhasil ' . $user->name . '! Akun Anda telah aktif. Silakan login dengan akun Anda.');

        } catch (Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Handle socialite registration - UPDATED: phone_number required & menampilkan nama user
     */
    public function handleSocialiteRegistration(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
            'phone_number.max' => 'Nomor telepon maksimal 15 digit.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Socialite user dibuat dengan password random agar bisa login manual
            $randomPassword = Str::random(12);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($randomPassword),
                'phone_number' => $request->phone_number,
                'role' => 'calon_santri',
                'is_active' => true,
                'provider_id' => $request->provider_id,
                'provider_name' => $request->provider,
                'email_verified_at' => now(),
            ]);

            DB::commit();

            // Login user setelah registrasi socialite
            Auth::login($user, true);

            // Redirect ke dashboard dengan pesan sukses yang menyertakan nama user
            return redirect()->route('santri.dashboard')->with('success', 'Registrasi dengan ' . ucfirst($request->provider) . ' berhasil! Akun Anda telah aktif. Selamat datang ' . $user->name . '.');

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

        return redirect('/')->with('success', 'Logout berhasil!');
    }

    /**
     * Redirect to provider for socialite login
     */
    public function redirectToProvider($provider)
    {
        $allowedProviders = ['google'];

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'Provider tidak didukung.');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan dengan provider ' . $provider);
        }
    }

    /**
     * Handle provider callback for socialite login - UPDATED: Menampilkan nama user di success message
     */
    public function handleProvideCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan ' . $provider . '. Silakan coba lagi.');
        }

        // Cari user berdasarkan email
        $user = User::where('email', $socialUser->getEmail())->first();

        // Jika user tidak ditemukan, redirect ke register dengan data socialite
        if (!$user) {
            return redirect()->route('register')->with([
                'socialite_data' => [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? explode('@', $socialUser->getEmail())[0],
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ],
                'info' => 'Silahkan lengkapi data'
            ]);
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Update provider info jika user sudah ada tapi belum punya provider info
        if (!$user->isSocialiteUser()) {
            $user->update([
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider
            ]);
        }

        // Login user
        Auth::login($user, true);

        // Redirect berdasarkan role dengan pesan sukses yang menyertakan nama user
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user->name . '.');
        } else {
            return redirect()->route('santri.dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user->name . '.');
        }
    }

    /**
     * Check if email exists (for AJAX validation)
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false,
                'message' => 'Format email tidak valid.'
            ], 400);
        }

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah terdaftar.' : 'Email tersedia.'
        ]);
    }
}
