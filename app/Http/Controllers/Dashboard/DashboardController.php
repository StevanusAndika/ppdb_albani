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

        // Ambil data registrasi dengan relasi yang benar
        $registration = Registration::with(['package', 'package.prices' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
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
        $payments = collect();
        $latestPayment = null;
        $hasSuccessfulPayment = false;

        if ($registration) {
            $payments = Payment::where('registration_id', $registration->id)
                             ->orderBy('created_at', 'desc')
                             ->get();

            $latestPayment = $payments->first();
            $hasSuccessfulPayment = $payments->whereIn('status', ['success', 'lunas'])->isNotEmpty();
        }

        // Ambil pengumuman untuk santri
        $userAnnouncements = collect();
        if ($registration) {
            $userAnnouncements = Announcement::where('registration_id', $registration->id)
                ->where('status', 'sent')
                ->orderBy('sent_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Ambil program unggulan untuk ditampilkan di dashboard
        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? ($contentSettings->program_unggulan_data ?? []) : [];

        // Hitung total progress
        $totalProgress = $this->calculateTotalProgress($registration, $documentProgress, $hasSuccessfulPayment);

        // Stats untuk dashboard santri
        $stats = [
            'document_progress' => $documentProgress,
            'total_payments' => $payments->count(),
            'success_payments' => $payments->whereIn('status', ['success', 'lunas'])->count(),
            'pending_payments' => $payments->whereIn('status', ['pending', 'waiting_payment'])->count(),
            'announcements_count' => $userAnnouncements->count(),
            'total_progress' => $totalProgress,
        ];

        return view('dashboard.calon_santri.index', compact(
            'registration',
            'documentProgress',
            'payments',
            'latestPayment',
            'hasSuccessfulPayment',
            'userAnnouncements',
            'stats',
            'programUnggulan',
            'totalProgress'
        ));
    }

    /**
     * Hitung total progress pendaftaran
     */
    private function calculateTotalProgress($registration, $documentProgress, $hasSuccessfulPayment)
    {
        $totalProgress = 25; // Step 1: Buat Akun selalu complete

        if ($registration) {
            $totalProgress += 25; // Step 2: Isi Biodata
        }

        if ($registration && $registration->hasAllDocuments()) {
            $totalProgress += 25; // Step 3: Upload Dokumen lengkap
        } elseif ($registration) {
            // Jika dokumen belum lengkap, hitung berdasarkan progress dokumen
            $totalProgress += ($documentProgress / 100) * 25;
        }

        if ($hasSuccessfulPayment) {
            $totalProgress += 25; // Step 4: Pembayaran lunas
        } elseif ($registration && $registration->hasAllDocuments()) {
            $totalProgress += 10; // Step 4: Siap bayar (partial progress)
        }

        return min(100, round($totalProgress));
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
