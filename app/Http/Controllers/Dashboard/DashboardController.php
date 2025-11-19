<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
use App\Models\Announcement;
use App\Models\ContentSetting;
use App\Models\Quota;
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

        $recentAnnouncements = Announcement::with('registration.user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard.admin.index', compact('stats', 'recentRegistrations', 'recentPayments', 'recentAnnouncements'));
    }

    public function santriDashboard()
    {
        $user = Auth::user();

        $registration = Registration::with(['package', 'package.prices' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('user_id', $user->id)
        ->first();

        $documentProgress = 0;
        $uploadedDocuments = [];
        $uploadedCount = 0;
        $totalDocuments = 4;

        if ($registration) {
            $documents = [
                'kartu_keluarga' => $registration->kartu_keluaga_path,
                'ijazah' => $registration->ijazah_path,
                'akta_kelahiran' => $registration->akta_kelahiran_path,
                'pas_foto' => $registration->pas_foto_path
            ];

            foreach ($documents as $type => $path) {
                $isUploaded = !empty($path);
                $uploadedDocuments[$type] = $isUploaded;
                if ($isUploaded) {
                    $uploadedCount++;
                }
            }

            $documentProgress = ($uploadedCount / $totalDocuments) * 100;
        }

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

        $userAnnouncements = collect();
        if ($registration) {
            $userAnnouncements = Announcement::where('registration_id', $registration->id)
                ->where('status', 'sent')
                ->orderBy('sent_at', 'desc')
                ->limit(5)
                ->get();
        }

        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? ($contentSettings->program_unggulan_data ?? []) : [];

        
        $totalProgress = $this->calculateTotalProgress($registration, $documentProgress, $hasSuccessfulPayment, $uploadedDocuments); // tambahin parameter wawancara

        $quota = Quota::getActiveQuota();
        $quotaAvailable = $quota ? $quota->isAvailable() : false;

        $stats = [
            'document_progress' => $documentProgress,
            'uploaded_count' => $uploadedCount,
            'total_documents' => $totalDocuments,
            'uploaded_documents' => $uploadedDocuments,
            'total_payments' => $payments->count(),
            'success_payments' => $payments->whereIn('status', ['success', 'lunas'])->count(),
            'pending_payments' => $payments->whereIn('status', ['pending', 'waiting_payment'])->count(),
            'announcements_count' => $userAnnouncements->count(),
            'total_progress' => $totalProgress,
            'quota_available' => $quotaAvailable,
            'quota_remaining' => $quota ? $quota->sisa : 0,
            'quota_total' => $quota ? $quota->kuota : 0,
            'quota_percentage' => $quota ? $quota->persentase_terpakai : 0,

        ];

        $barcodeUrl = null;
        $barcodeDownloadUrl = null;
        $barcodeInfoUrl = null;
        if ($registration) {
            $barcodeUrl = route('barcode.image', $registration->id_pendaftaran);
            $barcodeDownloadUrl = route('barcode.download', $registration->id_pendaftaran);
            $barcodeInfoUrl = route('barcode.show', $registration->id_pendaftaran);
        }

        return view('dashboard.calon_santri.index', compact(
            'registration',
            'documentProgress',
            'uploadedDocuments',
            'uploadedCount',
            'totalDocuments',
            'payments',
            'latestPayment',
            'hasSuccessfulPayment',
            'userAnnouncements',
            'stats',
            'programUnggulan',
            'totalProgress',
            'barcodeUrl',
            'barcodeDownloadUrl',
            'barcodeInfoUrl',
            'quota',
            'quotaAvailable'
        ));
    }

    /**
     * API untuk mendapatkan progress dokumen terbaru
     */
    public function getDocumentProgress()
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.',
                    'progress' => 0,
                    'uploaded_count' => 0,
                    'total_documents' => 4,
                    'uploaded_documents' => []
                ], 404);
            }

            $uploadedDocuments = [];
            $uploadedCount = 0;
            $totalDocuments = 4;

            $documents = [
                'kartu_keluarga' => $registration->kartu_keluaga_path,
                'ijazah' => $registration->ijazah_path,
                'akta_kelahiran' => $registration->akta_kelahiran_path,
                'pas_foto' => $registration->pas_foto_path
            ];

            foreach ($documents as $type => $path) {
                $isUploaded = !empty($path);
                $uploadedDocuments[$type] = $isUploaded;
                if ($isUploaded) {
                    $uploadedCount++;
                }
            }

            $documentProgress = ($uploadedCount / $totalDocuments) * 100;

            return response()->json([
                'success' => true,
                'progress' => $documentProgress,
                'uploaded_count' => $uploadedCount,
                'total_documents' => $totalDocuments,
                'uploaded_documents' => $uploadedDocuments,
                'all_documents_complete' => $uploadedCount === $totalDocuments
            ]);

        } catch (\Exception $e) {
            \Log::error('Get document progress error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil progress dokumen: ' . $e->getMessage(),
                'progress' => 0,
                'uploaded_count' => 0,
                'total_documents' => 4,
                'uploaded_documents' => []
            ], 500);
        }
    }

    /**
     * Hitung total progress pendaftaran
     */
    private function calculateTotalProgress($registration, $documentProgress, $hasSuccessfulPayment, $uploadedDocuments = [])
    {
        $totalProgress = 25;

        if ($registration) {
            $totalProgress += 25;
        }

        if ($registration) {
            $documentContribution = ($documentProgress / 100) * 25;
            $totalProgress += $documentContribution;
        }

        if ($hasSuccessfulPayment) {
            $totalProgress += 25;
        } elseif ($registration && $this->allDocumentsUploaded($uploadedDocuments)) {
            $totalProgress += 10;
        }

        return min(100, round($totalProgress));
    }

    /**
     * Cek apakah semua dokumen sudah diupload
     */
    private function allDocumentsUploaded($uploadedDocuments)
    {
        if (empty($uploadedDocuments)) {
            return false;
        }

        return count(array_filter($uploadedDocuments)) === 4;
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
