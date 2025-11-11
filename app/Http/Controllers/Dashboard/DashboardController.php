<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
use App\Models\Announcement;
use App\Models\ContentSetting;
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
            // Tambahan stats untuk announcement
            'eligible_for_announcement' => $this->getEligibleRegistrationsCount(),
            'sent_announcements' => Announcement::where('status', 'sent')->count(),
        ];

        $recentRegistrations = Registration::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['user', 'registration'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Data untuk announcement preview
        $recentAnnouncements = Announcement::with('registration.user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard.admin.index', compact('stats', 'recentRegistrations', 'recentPayments', 'recentAnnouncements'));
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

        // Ambil data pembayaran - GUNAKAN COLLECTION
        $payments = collect(); // Inisialisasi sebagai collection kosong
        $latestPayment = null;
        $hasSuccessfulPayment = false;

        if ($registration) {
            $payments = Payment::where('registration_id', $registration->id)
                             ->orderBy('created_at', 'desc')
                             ->get(); // Ini akan return Collection

            $latestPayment = $payments->first();
            $hasSuccessfulPayment = $payments->whereIn('status', ['success', 'lunas'])->isNotEmpty();
        }

        // Ambil pengumuman untuk santri
        $userAnnouncements = collect(); // Inisialisasi sebagai collection kosong
        if ($registration) {
            $userAnnouncements = Announcement::where('registration_id', $registration->id)
                ->where('status', 'sent')
                ->orderBy('sent_at', 'desc')
                ->limit(5)
                ->get(); // Ini akan return Collection
        }

        // Ambil program unggulan untuk ditampilkan di dashboard
        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? $contentSettings->program_unggulan : [];

        // Ambil data untuk stats cards - PERBAIKI INI
        $stats = [
            'document_progress' => $documentProgress,
            'total_payments' => $payments->count(), // Sekarang bisa karena $payments adalah Collection
            'success_payments' => $payments->whereIn('status', ['success', 'lunas'])->count(),
            'pending_payments' => $payments->whereIn('status', ['pending', 'waiting_payment'])->count(),
            'announcements_count' => $userAnnouncements->count(), // Sekarang bisa karena $userAnnouncements adalah Collection
        ];

        return view('dashboard.calon_santri.index', compact(
            'registration',
            'documentProgress',
            'payments',
            'latestPayment',
            'hasSuccessfulPayment',
            'userAnnouncements',
            'stats',
            'programUnggulan'
        ));
    }

    /**
     * Hitung jumlah calon santri yang eligible untuk announcement
     */
    private function getEligibleRegistrationsCount()
    {
        return Registration::with(['user', 'payments', 'package'])
            ->where('status_pendaftaran', 'diterima')
            ->whereHas('payments', function($query) {
                $query->whereIn('status', ['success', 'lunas'])
                      ->whereIn('payment_method', ['xendit', 'cash']);
            })
            ->get()
            ->filter(function($registration) {
                return $registration->hasAllDocuments() &&
                       $registration->package &&
                       $this->isPaymentComplete($registration);
            })
            ->count();
    }

    /**
     * Cek apakah pembayaran sudah lengkap
     */
    private function isPaymentComplete(Registration $registration): bool
    {
        $totalPaid = $registration->payments
            ->whereIn('status', ['success', 'lunas'])
            ->sum('amount');

        $packageAmount = $registration->package->total_amount ?? 0;

        return $totalPaid >= $packageAmount;
    }
}
