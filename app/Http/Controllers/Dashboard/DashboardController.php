<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan role dengan notifikasi
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Dashboard Admin!');
        } else {
            return redirect()->route('santri.dashboard')->with('success', 'Selamat datang di Dashboard Santri!');
        }
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();

        // Pastikan hanya admin yang bisa akses
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('dashboard.admin', compact('user'));
    }

    /**
     * Santri Dashboard
     */
    public function santriDashboard()
    {
        $user = Auth::user();

        // Pastikan hanya calon_santri yang bisa akses
        if (!$user->isCalonSantri()) {
            abort(403, 'Unauthorized access.');
        }

        return view('dashboard.santri', compact('user'));
    }
}
