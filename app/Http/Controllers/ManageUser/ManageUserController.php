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
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role', 'is_active', 'created_at')
                    ->latest()
                    ->paginate(10);

        return view('dashboard.admin.manage-user.index', compact('users'));
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
        $generatedPassword = session('generated_password', Str::random(12));

        return view('dashboard.admin.manage-user.create', compact('roles', 'generatedPassword'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|string|in:admin,calon_santri',
            'password_option' => 'required|in:manual,generate',
            'password' => 'required_if:password_option,manual|string|min:8|confirmed',
            'generated_password' => 'required_if:password_option,generate|string|min:8',
        ], [
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

            $message = 'User berhasil ditambahkan.';
            if ($request->password_option === 'generate') {
                $message .= "Harap catat data-data yang telah anda buat!";
            }

            return redirect()->route('admin.manage-users.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            // Simpan generated password di session untuk digunakan kembali
            if ($request->password_option === 'generate' && $request->generated_password) {
                session(['generated_password' => $request->generated_password]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput(); // Ini yang MEMPERTAHANKAN semua input
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

        return view('dashboard.admin.manage-user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,calon_santri',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.manage-users.index')
            ->with('success', 'User berhasil diperbarui.');
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
     * Generate random password
     */
    public function generatePassword()
    {
        $password = Str::random(12);

        return response()->json([
            'password' => $password,
            'message' => 'Password acak berhasil dibuat'
        ]);
    }
}
