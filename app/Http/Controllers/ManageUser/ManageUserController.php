<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // Update method index
        public function index(Request $request)
        {
            $search = $request->input('search');

            $users = User::select('id', 'name', 'email', 'phone_number', 'role', 'is_active', 'created_at')
                        ->when($search, function ($query, $search) {
                            return $query->where(function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('phone_number', 'like', '%' . $search . '%');
                            });
                        })
                        ->latest()
                        ->paginate(10)
                        ->withQueryString();

            return view('dashboard.admin.manage-user.index', compact('users', 'search'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [
            'admin' => 'Admin',
            'calon_santri' => 'Calon Santri'
        ];

        // Generate password untuk opsi generate (jika diperlukan)
        $generatedPassword = session('generated_password', $this->generateStrongPassword());

        return view('dashboard.admin.manage-user.create', compact('roles', 'generatedPassword'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Buat rules validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|string|in:admin,calon_santri',
            'password_option' => 'required|in:manual,generate',
        ];

        // Tambahkan rules berdasarkan opsi password
        if ($request->password_option === 'manual') {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['generated_password'] = 'required|string|min:8';
        }

        $request->validate($rules, [
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'generated_password.min' => 'Password harus minimal 8 karakter.',
        ]);

        try {
            // Tentukan password berdasarkan opsi
            if ($request->password_option === 'generate') {
                $password = $request->generated_password;
            } else {
                $password = $request->password;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role' => $request->role,
                'password' => Hash::make($password),
                'is_active' => true,
            ]);

            // Kirim notifikasi ke WhatsApp user
            $this->sendAccountCreatedNotification($user, $password);

            $message = 'User berhasil ditambahkan.';
            if ($request->password_option === 'generate') {
                $message .= " Harap catat data-data yang telah anda buat!";
            }

            // Hapus session generated password setelah berhasil
            session()->forget('generated_password');

            return redirect()->route('admin.manage-users.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            // Simpan generated password di session untuk digunakan kembali
            if ($request->password_option === 'generate' && $request->generated_password) {
                session(['generated_password' => $request->generated_password]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [
            'admin' => 'Admin',
            'calon_santri' => 'Calon Santri'
        ];

        // Generate password untuk opsi reset password
        $generatedPassword = session('generated_password', $this->generateStrongPassword());

        return view('dashboard.admin.manage-user.edit', compact('user', 'roles', 'generatedPassword'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,calon_santri',
        ];

        // Tambahkan validasi password jika opsi reset password dipilih
        if ($request->has('reset_password_option') && in_array($request->reset_password_option, ['generate', 'manual'])) {
            $rules['reset_password_option'] = 'required|in:generate,manual';

            if ($request->reset_password_option === 'manual') {
                $rules['new_password'] = ['required', 'string', 'min:8', 'confirmed'];
            } else {
                $rules['generated_password'] = 'required|string|min:8';
            }
        }

        $request->validate($rules, [
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'new_password.min' => 'Password harus minimal 8 karakter.',
            'generated_password.min' => 'Password harus minimal 8 karakter.',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            $isPasswordReset = false;
            $newPassword = null;

            // Jika opsi reset password dipilih
            if ($request->has('reset_password_option') && $request->reset_password_option) {
                $isPasswordReset = true;

                // Tentukan password baru
                if ($request->reset_password_option === 'generate') {
                    $newPassword = $request->generated_password;
                } else {
                    $newPassword = $request->new_password;
                }

                // Update password
                $data['password'] = Hash::make($newPassword);
            }

            $user->update($data);

            $message = 'User berhasil diperbarui.';

            // Kirim notifikasi jika reset password dilakukan
            if ($isPasswordReset && $newPassword) {
                $message = 'User berhasil diperbarui. Anda berhasil melakukan reset password melalui admin.';

                // Kirim notifikasi reset password
                $this->sendPasswordResetNotification($user, $newPassword);
            }

            // Hapus session generated password setelah berhasil
            session()->forget('generated_password');

            return redirect()->route('admin.manage-users.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            // Simpan generated password di session untuk digunakan kembali
            if ($request->has('reset_password_option') && $request->reset_password_option === 'generate' && $request->generated_password) {
                session(['generated_password' => $request->generated_password]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.'
            ], 400);
        }

        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "User berhasil {$status}.");
    }

    /**
     * Generate random password via AJAX
     */
    public function generatePassword()
    {
        $password = $this->generateStrongPassword();

        return response()->json([
            'password' => $password,
            'message' => 'Password acak berhasil dibuat'
        ]);
    }

    /**
     * Generate strong password
     */
    private function generateStrongPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $all = $uppercase . $lowercase . $numbers . $symbols;

        // Pastikan minimal satu karakter dari setiap jenis
        $password = $uppercase[rand(0, strlen($uppercase) - 1)]
                  . $lowercase[rand(0, strlen($lowercase) - 1)]
                  . $numbers[rand(0, strlen($numbers) - 1)]
                  . $symbols[rand(0, strlen($symbols) - 1)];

        // Tambahkan karakter random hingga mencapai panjang yang diinginkan
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[rand(0, strlen($all) - 1)];
        }

        // Acak urutan password
        return str_shuffle($password);
    }

    /**
     * Kirim notifikasi akun berhasil dibuat melalui WhatsApp
     */
    private function sendAccountCreatedNotification(User $user, string $password): void
    {
        try {
            // Periksa apakah user memiliki nomor telepon
            if (empty($user->phone_number)) {
                \Log::warning('User tidak memiliki nomor telepon untuk mengirim notifikasi pembuatan akun', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return;
            }

            // Format pesan WhatsApp
            $roleText = $user->role === 'admin' ? 'Admin' : 'Calon Santri';
            $dashboardUrl = $user->role === 'admin'
                ? route('admin.dashboard')
                : route('santri.dashboard');

                $message = "🎉 AKUN BERHASIL DIBUAT 🎉\n\n"
                     . "Assalamu'alaikum Warahmatullahi Wabarakatuh,\n\n"
                     . "Halo {$user->name},\n\n"
                     . "Akun Anda telah berhasil dibuat di Sistem PPDB Pondok Pesantren Al-Quran Bani Syahid.\n\n"
                     . "📋 DETAIL AKUN:\n"
                     . "• Nama: {$user->name}\n"
                     . "• Email: {$user->email}\n"
                     . "• No. Telepon: {$user->phone_number}\n"
                     . "• Role: {$roleText}\n"
                     . "• Password: *{$password}*\n"
                     . "• Status: Aktif ✅\n\n"
                     . "🔒 KEAMANAN:\n"
                     . "• Jangan bagikan password dan data akun anda kepada siapapun.\n"
                     . "• Password ini bersifat rahasia.\n"
                     . "• Jaga kerahasiaan informasi akun\n\n"

                     . "📞 BANTUAN:\n"
                     . "Jika mengalami kendala, hubungi:\n"
                     . "• Admin PPDB Putra:+62 895-1027-9293\n"
                     . "• Admin PPDB Putri:+62 821-8395-3533\n"
                     . "Wassalamu'alaikum Warahmatullahi Wabarakatuh,\n"
                     . "Panitia PPDB\n"
                     . "Pondok Pesantren Al-Quran Bani Syahid";


            // Kirim melalui Fonnte
            $fonnte = app('fonnte');
            $result = $fonnte->sendMessage($user->phone_number, $message);

            if ($result['success']) {
                \Log::info('Notifikasi pembuatan akun berhasil dikirim via WhatsApp', [
                    'user_id' => $user->id,
                    'phone' => $user->phone_number,
                    'role' => $user->role
                ]);
            } else {
                \Log::error('Gagal mengirim notifikasi pembuatan akun via WhatsApp', [
                    'user_id' => $user->id,
                    'phone' => $user->phone_number,
                    'error' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Error dalam mengirim notifikasi pembuatan akun', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Kirim notifikasi reset password melalui WhatsApp
     */
    private function sendPasswordResetNotification(User $user, string $newPassword): void
    {
        try {
            // Periksa apakah user memiliki nomor telepon
            if (empty($user->phone_number)) {
                \Log::warning('User tidak memiliki nomor telepon untuk mengirim notifikasi reset password', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return;
            }

            // Format pesan WhatsApp
            $roleText = $user->role === 'admin' ? 'Admin' : 'Calon Santri';
            $dashboardUrl = $user->role === 'admin'
                ? route('admin.dashboard')
                : route('santri.dashboard');

            $message = "🔐 RESET PASSWORD BERHASIL\n\n"
                     . "Assalamu'alaikum Warahmatullahi Wabarakatuh,\n\n"
                     . "Halo {$user->name},\n\n"
                     . "Anda telah berhasil meminta reset password kepada admin.\n\n"
                     . "📋 DETAIL AKUN:\n"
                     . "• Email: {$user->email}\n"
                     . "• Role: {$roleText}\n"
                     . "• Password Baru: *{$newPassword}*\n"
                     . "• Reset dilakukan pada: " . now()->translatedFormat('d F Y H:i') . "\n\n"
                     . "🔒 KEAMANAN:\n"
                     . "• Jangan bagikan password dan data akun anda kepada siapapun\n"
                     . "• Password ini bersifat rahasia\n\n"
                     . "• Jaga kerahasiaan informasi akun\n\n"
                     . "📞 BANTUAN:\n"
                     . "Jika mengalami masalah, hubungi:\n"
                     . "• Admin PPDB Putra: +62 895-1027-9293\n"
                     . "• Admin PPDB Putri: +62 821-8395-3533\n"
                     . "Terima kasih atas perhatiannya.\n\n"
                     . "Wassalamu'alaikum Warahmatullahi Wabarakatuh,\n"
                     . "Tim Admin PPDB\n"
                     . "Pondok Pesantren Al-Quran Bani Syahid";

            // Kirim melalui Fonnte
            $fonnte = app('fonnte');
            $result = $fonnte->sendMessage($user->phone_number, $message);

            if ($result['success']) {
                \Log::info('Notifikasi reset password berhasil dikirim via WhatsApp', [
                    'user_id' => $user->id,
                    'phone' => $user->phone_number
                ]);
            } else {
                \Log::error('Gagal mengirim notifikasi reset password via WhatsApp', [
                    'user_id' => $user->id,
                    'phone' => $user->phone_number,
                    'error' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Error dalam mengirim notifikasi reset password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
