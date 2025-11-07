<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
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
            'total_payments' => Payment::count(),
            'success_payments' => Payment::whereIn('status', ['success', 'lunas'])->count(),
            'pending_payments' => Payment::whereIn('status', ['pending', 'waiting_payment'])->count(),
            'total_users' => User::count(),
        ];

        $recentRegistrations = Registration::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['user', 'registration'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin.index', compact('stats', 'recentRegistrations', 'recentPayments'));
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

        // Ambil data pembayaran
        $payments = [];
        $latestPayment = null;
        $hasSuccessfulPayment = false;

        if ($registration) {
            $payments = Payment::where('registration_id', $registration->id)
                             ->orderBy('created_at', 'desc')
                             ->get();

            $latestPayment = $payments->first();
            $hasSuccessfulPayment = $payments->whereIn('status', ['success', 'lunas'])->isNotEmpty();
        }

        return view('dashboard.calon_santri.index', compact(
            'registration',
            'documentProgress',
            'payments',
            'latestPayment',
            'hasSuccessfulPayment'
        ));
    }
}
