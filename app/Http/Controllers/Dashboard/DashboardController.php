<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCalonSantri()) {
            return redirect()->route('santri.dashboard');
        }

        return view('dashboard.index');
    }

    public function adminDashboard()
    {
        $stats = [
            'total_registrations' => Registration::count(),
            'pending_registrations' => Registration::where('status_pendaftaran', 'menunggu_diverifikasi')->count(),
            'approved_registrations' => Registration::where('status_pendaftaran', 'diterima')->count(),
            'rejected_registrations' => Registration::where('status_pendaftaran', 'ditolak')->count(),
        ];

        $recentRegistrations = Registration::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin.index', compact('stats', 'recentRegistrations'));
    }

    public function santriDashboard()
    {
        $user = Auth::user();
        $registration = Registration::with(['package.activePrices'])
            ->where('user_id', $user->id)
            ->first();

        $documentProgress = 0;
        if ($registration) {
            $uploadedCount = 0;
            if ($registration->kartu_keluaga_path) $uploadedCount++;
            if ($registration->ijazah_path) $uploadedCount++;
            if ($registration->akta_kelahiran_path) $uploadedCount++;
            if ($registration->pas_foto_path) $uploadedCount++;
            $documentProgress = ($uploadedCount / 4) * 100;
        }

        return view('dashboard.calon_santri.index', compact('registration', 'documentProgress'));
    }
}
