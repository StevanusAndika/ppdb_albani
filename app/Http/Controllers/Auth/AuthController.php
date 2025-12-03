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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    // Konstanta untuk login attempts
    const MAX_LOGIN_ATTEMPTS = 3;
    const LOCK_TIME_MINUTES = 5; // 5 menit

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
            $scoreThreshold = config('services.recaptcha.score_threshold', 0.5);
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
     * Show login form
     */
    public function showLoginForm()
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        return view('auth.login', [
            'recaptcha_site_key' => $this->getRecaptchaSiteKey(),
            'recaptcha_enabled' => $this->isRecaptchaEnabled()
        ]);
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

        return view('auth.register', [
            'recaptcha_site_key' => $this->getRecaptchaSiteKey(),
            'recaptcha_enabled' => $this->isRecaptchaEnabled()
        ]);
    }

    /**
     * Handle manual login with reCAPTCHA and login attempts tracking
     */
    public function login(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        // Validasi reCAPTCHA jika enabled
        if ($this->isRecaptchaEnabled() && !$this->validateRecaptcha($request)) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput();
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

        // Cek apakah account terkunci
        if ($user->isLocked()) {
            $remainingMinutes = $user->getLockRemainingMinutes();

            if ($remainingMinutes > 0) {
                return redirect()->route('password.request')
                    ->with('error', 'Akun Anda terkunci selama ' . $remainingMinutes . ' menit karena 3 kali percobaan login gagal. Silakan reset password untuk membuka akun.')
                    ->with('locked_user_email', $user->email);
            } else {
                // Auto unlock jika waktu lock sudah habis
                $user->unlockAccount();
            }
        }

        // Cek apakah account tidak aktif
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
            // Reset login attempts jika berhasil
            $user->resetLoginAttempts();

            $request->session()->regenerate();

            // Redirect berdasarkan role dengan notifikasi yang menyertakan nama user
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name . '.');
            } else {
                return redirect()->route('santri.dashboard')->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name . '.');
            }
        }

        // Jika login gagal, increment login attempts
        $user->incrementLoginAttempts();

        // Cek apakah sekarang account terkunci
        if ($user->isLocked()) {
            return redirect()->route('password.request')
                ->with('error', 'Akun Anda terkunci selama 5 menit karena 3 kali percobaan login gagal. Silakan reset password untuk membuka akun.')
                ->with('locked_user_email', $user->email);
        }

        // Tampilkan pesan error berdasarkan sisa percobaan
        $remainingAttempts = $user->getRemainingAttempts();

        if ($remainingAttempts > 0) {
            return back()->withErrors([
                'password' => 'Password salah. Sisa percobaan: ' . $remainingAttempts . ' kali.',
            ])->withInput();
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ])->withInput();
    }

    /**
     * Handle manual registration with reCAPTCHA and unique validation
     */
    public function register(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        // Validasi reCAPTCHA jika enabled
        if ($this->isRecaptchaEnabled() && !$this->validateRecaptcha($request)) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:users,phone_number'
            ],
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
            'phone_number.max' => 'Nomor telepon maksimal 15 digit.',
            'phone_number.unique' => 'Silahkan Gunakan Nomor Lain.',
            'email.unique' => 'Email sudah digunakan.'
        ]);

        if ($validator->fails()) {
            // Pesan error yang aman (tidak menampilkan kredensial spesifik)
            $errors = $validator->errors();
            $errorMessages = [];

            foreach ($errors->keys() as $key) {
                switch ($key) {
                    case 'email':
                        $errorMessages['email'] = 'Email sudah digunakan.';
                        break;
                    case 'phone_number':
                        $errorMessages['phone_number'] = 'Nomor telepon sudah digunakan.';
                        break;
                    default:
                        $errorMessages[$key] = $errors->first($key);
                }
            }

            return back()
                ->withErrors($errorMessages)
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
                'login_attempts' => 0,
                'locked_until' => null,
                'last_login_attempt' => null,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil ' . $user->name . '! Akun Anda telah aktif. Silakan login dengan akun Anda.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Handle socialite registration with reCAPTCHA
     */
    public function handleSocialiteRegistration(Request $request)
    {
        // Check if already logged in
        $redirect = $this->redirectIfAuthenticated();
        if ($redirect) {
            return $redirect;
        }

        // Validasi reCAPTCHA jika enabled
        if ($this->isRecaptchaEnabled() && !$this->validateRecaptcha($request)) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:users,phone_number'
            ],
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex' => 'Nomor telepon hanya boleh angka.',
            'phone_number.max' => 'Nomor telepon maksimal 15 digit.',
            'phone_number.unique' => 'Nomor telepon sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.'
        ]);

        if ($validator->fails()) {
            // Pesan error yang aman
            $errors = $validator->errors();
            $errorMessages = [];

            foreach ($errors->keys() as $key) {
                switch ($key) {
                    case 'email':
                        $errorMessages['email'] = 'Email sudah digunakan.';
                        break;
                    case 'phone_number':
                        $errorMessages['phone_number'] = 'Nomor telepon sudah digunakan.';
                        break;
                    default:
                        $errorMessages[$key] = $errors->first($key);
                }
            }

            return back()
                ->withErrors($errorMessages)
                ->withInput();
        }

        try {
            DB::beginTransaction();

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
                'login_attempts' => 0,
                'locked_until' => null,
                'last_login_attempt' => null,
            ]);

            DB::commit();

            Auth::login($user, true);

            return redirect()->route('santri.dashboard')->with('success', 'Registrasi dengan ' . ucfirst($request->provider) . ' berhasil! Akun Anda telah aktif. Selamat datang ' . $user->name . '.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Socialite registration error: ' . $e->getMessage());

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
     * Handle provider callback for socialite login
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

        // Cek apakah account terkunci
        if ($user->isLocked()) {
            $remainingMinutes = $user->getLockRemainingMinutes();

            return redirect()->route('login')
                ->with('error', 'Akun Anda terkunci selama ' . $remainingMinutes . ' menit karena 3 kali percobaan login gagal. Silakan reset password untuk membuka akun.')
                ->with('locked_user_email', $user->email);
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

        // Reset login attempts untuk socialite login
        $user->resetLoginAttempts();

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

    /**
     * Check if phone number exists (for AJAX validation)
     */
    public function checkPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false,
                'message' => 'Format nomor telepon tidak valid.'
            ], 400);
        }

        // Format nomor telepon untuk validasi konsisten
        $phone = preg_replace('/[^0-9]/', '', $request->phone_number);

        $exists = User::where('phone_number', 'like', '%' . $phone . '%')->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor telepon sudah digunakan.' : 'Nomor telepon tersedia.'
        ]);
    }
}
