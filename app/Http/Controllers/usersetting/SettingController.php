<?php

namespace App\Http\Controllers\usersetting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.santri.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'profile');
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            return redirect()->route('santri.settings.index')
                ->with('success', 'Profil berhasil diperbarui!')
                ->with('active_tab', 'profile');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil.')
                ->with('active_tab', 'profile');
        }
    }

    public function disconnectGoogle()
    {
        $user = Auth::user();

        if (!$user->isSocialiteUser()) {
            return redirect()->route('santri.settings.index')
                ->with('error', 'Akun tidak terhubung dengan Google.')
                ->with('active_tab', 'google');
        }

        try {
            $user->update([
                'provider_id' => null,
                'provider_name' => null,
            ]);

            return redirect()->route('santri.settings.index')
                ->with('success', 'Berhasil memutuskan koneksi dengan Google!')
                ->with('active_tab', 'google');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memutuskan koneksi.')
                ->with('active_tab', 'google');
        }
    }
}
