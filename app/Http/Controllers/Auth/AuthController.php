<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProvideCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan ' . $provider . ': ' . $e->getMessage());
        }

        // find or create user and send params user get from socialite and provider
        $authUser = $this->findOrCreateUser($user, $provider);

        // login user
        Auth::login($authUser, true);

        // redirect ke dashboard berdasarkan role
        return redirect()->route('dashboard');
    }

    public function findOrCreateUser($socialUser, $provider)
    {
        // Cari user berdasarkan email dari socialite
        $user = User::where('email', $socialUser->getEmail())->first();

        // Jika user sudah ada
        if ($user) {
            // Update provider information jika belum ada
            if (empty($user->provider_id)) {
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
            'password' => bcrypt(Str::random(16)), // Password random karena login via socialite
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
            'email_verified_at' => now(), // Verifikasi email otomatis karena dari provider terpercaya
            'role' => 'calon_santri', // Default role untuk user socialite
            'is_active' => true,
        ]);

        return $user;
    }
}
