<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\ContentSetting;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = app('fonnte');
    }

    public function index()
    {
        $registrations = Registration::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.admin.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['user', 'package', 'package.prices']);

        // AUTO UPDATE: Jika status ditolak tapi data diperbarui, ubah ke perlu_review
        if ($registration->needs_re_review && $registration->status_pendaftaran === 'ditolak') {
            $registration->markAsNeedsReview();
            $registration->refresh();
        }

        if ($registration->status_pendaftaran === 'telah_mengisi') {
            $registration->markAsSeen();
        }

        return view('dashboard.admin.registrations.registration-detail', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:telah_dilihat,menunggu_diverifikasi,ditolak,diterima,perlu_review',
            'status_seleksi' => 'required|in:sudah_mengikuti_seleksi,belum_mengikuti_seleksi', // TAMBAHKAN VALIDASI
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $oldStatus = $registration->status_pendaftaran;
            $newStatus = $request->status;
            $newStatusSeleksi = $request->status_seleksi;

            // Validasi untuk status diterima
            if ($newStatus === 'diterima') {
                $validationResult = $this->validateApprovalRequirements($registration);

                if (!$validationResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $validationResult['message']
                    ], 422);
                }
            }

            // Update status
            switch ($newStatus) {
                case 'telah_dilihat':
                    $registration->markAsSeen();
                    break;
                case 'menunggu_diverifikasi':
                    $registration->markAsPending();
                    break;
                case 'ditolak':
                    $registration->markAsRejected($request->catatan);
                    $this->sendRejectionNotification($registration);
                    break;
                case 'diterima':
                    $registration->markAsApproved();
                    $this->sendApprovalNotification($registration);
                    break;
                case 'perlu_review':
                    $registration->markAsNeedsReview();
                    break;
            }

            // Update status seleksi
            if ($newStatusSeleksi === 'sudah_mengikuti_seleksi') {
                $registration->markAsSudahSeleksi();
            } else {
                $registration->markAsBelumSeleksi();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diupdate.',
                'status_label' => $registration->status_label,
                'status_seleksi_label' => $registration->status_seleksi_label
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status seleksi saja
     */
    public function updateStatusSeleksi(Request $request, Registration $registration)
    {
        $request->validate([
            'status_seleksi' => 'required|in:sudah_mengikuti_seleksi,belum_mengikuti_seleksi'
        ]);

        try {
            if ($request->status_seleksi === 'sudah_mengikuti_seleksi') {
                $registration->markAsSudahSeleksi();
            } else {
                $registration->markAsBelumSeleksi();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status seleksi berhasil diupdate.',
                'status_seleksi_label' => $registration->status_seleksi_label
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update dokumen dengan penolakan
     */
    public function updateDocumentsWithRejection(Request $request, Registration $registration)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:1000',
            'keep_kartu_keluarga' => 'boolean',
            'keep_ijazah' => 'boolean',
            'keep_akta_kelahiran' => 'boolean',
            'keep_pas_foto' => 'boolean'
        ]);

        try {
            // Hapus dokumen yang tidak dicentang
            if (!$request->keep_kartu_keluarga && $registration->kartu_keluaga_path) {
                if (Storage::disk('public')->exists($registration->kartu_keluaga_path)) {
                    Storage::disk('public')->delete($registration->kartu_keluaga_path);
                }
                $registration->kartu_keluaga_path = null;
            }

            if (!$request->keep_ijazah && $registration->ijazah_path) {
                if (Storage::disk('public')->exists($registration->ijazah_path)) {
                    Storage::disk('public')->delete($registration->ijazah_path);
                }
                $registration->ijazah_path = null;
            }

            if (!$request->keep_akta_kelahiran && $registration->akta_kelahiran_path) {
                if (Storage::disk('public')->exists($registration->akta_kelahiran_path)) {
                    Storage::disk('public')->delete($registration->akta_kelahiran_path);
                }
                $registration->akta_kelahiran_path = null;
            }

            if (!$request->keep_pas_foto && $registration->pas_foto_path) {
                if (Storage::disk('public')->exists($registration->pas_foto_path)) {
                    Storage::disk('public')->delete($registration->pas_foto_path);
                }
                $registration->pas_foto_path = null;
            }

            // Update status dan catatan
            $registration->update([
                'status_pendaftaran' => 'ditolak',
                'catatan_admin' => $request->reject_reason,
                'ditolak_pada' => now(),
                'diperbarui_setelah_ditolak' => false
            ]);

            // Kirim notifikasi penolakan
            $this->sendRejectionNotification($registration);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui dan pendaftaran ditolak.',
                'uploaded_documents_count' => $registration->fresh()->uploaded_documents_count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validasi syarat untuk menerima pendaftaran
     */
    private function validateApprovalRequirements(Registration $registration): array
    {
        $errors = [];

        // 1. Cek dokumen lengkap
        if (!$registration->hasAllDocuments()) {
            $errors[] = 'Dokumen belum lengkap';
        }

        // 2. Cek biodata lengkap (dengan field opsional)
        if (!$registration->isBiodataComplete()) {
            $errors[] = 'Biodata belum lengkap';
        }

        // 3. Cek pembayaran lunas
        if (!$registration->hasSuccessfulPayment()) {
            $errors[] = 'Belum ada pembayaran yang lunas';
        }

        // tambahin cek kuota==================================================

        //=====================================================================

        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Tidak dapat menerima pendaftaran: ' . implode(', ', $errors)
            ];
        }

        return ['success' => true];
    }

    /**
     * Kirim notifikasi penerimaan
     */
    private function sendApprovalNotification(Registration $registration)
    {
        $phone = $registration->nomor_telpon_orang_tua;
        $namaSantri = $registration->nama_lengkap;
$message = "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n"
         . "SELAMAT! 🎉\n\n"
         . "Kepada Yth. Bapak/Ibu Orang Tua/Wali Santri\n"
         . "Atas Nama: *{$namaSantri}*\n\n"
         . "Kami dengan senang hati menginformasikan bahwa:\n"
         . "✅ *DATA PENDAFTARAN CALON SANTRI TELAH KAMI CEK DAN KAMI  TERIMA*\n"
         . "✅ *SESUAI DENGAN SYARAT DAN KETENTUAN YANG BERLAKU*\n\n"
         . "Tim admin akan segera menghubungi Bapak/Ibu untuk informasi lebih lanjut mengenai *PROSES SELEKSI TES*.\n\n"
         . "Terima kasih atas kepercayaan dan partisipasinya.\n\n"
         . "Wassalamu'alaikum Warahmatullahi Wabarakatuh\n"
         . "Panitia PPDB\n"
         . "Pondok Pesantren Al Quran Bani Syahid";

        return $this->fonnteService->sendMessage($phone, $message);
    }

    private function sendRejectionNotification(Registration $registration)
    {
        $phone = $registration->nomor_telpon_orang_tua;
        $namaSantri = $registration->nama_lengkap;
        $alasan = $registration->catatan_admin ?? 'Data yang diisi tidak lengkap atau tidak memenuhi persyaratan.';

        return $this->fonnteService->sendRegistrationRejection($phone, $namaSantri, $alasan);
    }

    public function downloadInvoicePdf($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)
                     ->with([
                         'user',
                         'registration',
                         'registration.package',
                         'registration.programUnggulan'
                     ])
                     ->firstOrFail();

        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$payment->isPaid()) {
            return back()->with('error', 'Invoice hanya tersedia untuk pembayaran yang berhasil.');
        }

        $packagePrices = Price::where('package_id', $payment->registration->package_id)
                         ->active()
                         ->ordered()
                         ->get();

        $data = [
            'payment' => $payment,
            'packagePrices' => $packagePrices,
        ];

        $pdf = \PDF::loadView('dashboard.calon_santri.payments.invoice-pdf', $data);

        $filename = "Invoice-{$payment->payment_code}.pdf";

        return $pdf->download($filename);
    }

    public function downloadInvoice($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)
                        ->with([
                            'user',
                            'registration',
                            'registration.package',
                        ])
                        ->firstOrFail();

        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$payment->isPaid()) {
            return back()->with('error', 'Invoice hanya tersedia untuk pembayaran yang berhasil.');
        }

        $packagePrices = Price::where('package_id', $payment->registration->package_id)
                            ->active()
                            ->ordered()
                            ->get();

        return view('dashboard.calon_santri.payments.invoice', compact('payment', 'packagePrices'));
    }

    public function sendNotification(Request $request, Registration $registration)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $phone = $registration->nomor_telpon_orang_tua;
            $result = $this->fonnteService->sendMessage($phone, $request->message);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dikirim via WhatsApp.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim notifikasi: ' . $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload dokumen oleh admin
     */
    public function uploadDocument(Request $request, Registration $registration)
    {
        $request->validate([
            'document_type' => 'required|in:kartu_keluarga,ijazah,akta_kelahiran,pas_foto',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        try {
            $documentType = $request->document_type;
            $fieldName = $documentType . '_path';

            // Hapus file lama jika ada
            if ($registration->$fieldName && Storage::disk('public')->exists($registration->$fieldName)) {
                Storage::disk('public')->delete($registration->$fieldName);
            }

            // Simpan file baru
            $path = $request->file('document')->store('documents/' . $registration->id_pendaftaran, 'public');

            $registration->update([$fieldName => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload.',
                'document_path' => $path,
                'document_url' => Storage::url($path),
                'uploaded_count' => $registration->fresh()->uploaded_documents_count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update catatan admin
     */
    public function updateAdminNotes(Request $request, Registration $registration)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        try {
            $registration->update([
                'catatan_admin' => $request->catatan_admin
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan admin berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating admin notes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan catatan admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset status ke menunggu verifikasi (untuk kasus perlu review)
     */
    public function resetToPending(Registration $registration)
    {
        try {
            $registration->markAsPending();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil direset ke menunggu verifikasi.',
                'status_label' => $registration->status_label
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View document file - PERBAIKAN AKSES DOKUMEN
     */
    public function viewDocument(Registration $registration, $documentType)
    {
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            abort(404, 'Jenis dokumen tidak valid.');
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath) {
            abort(404, 'File tidak ditemukan. Path dokumen kosong.');
        }

        // Perbaikan: Cek file exists dengan path yang benar
        if (!Storage::disk('public')->exists($filePath)) {
            \Log::error('Document not found in storage', [
                'file_path' => $filePath,
                'document_type' => $documentType,
                'registration_id' => $registration->id,
                'storage_path' => Storage::disk('public')->path($filePath)
            ]);
            abort(404, 'File tidak ditemukan di storage.');
        }

        try {
            $file = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            $fileName = basename($filePath);

            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            \Log::error('Error viewing document: ' . $e->getMessage(), [
                'file_path' => $filePath,
                'document_type' => $documentType
            ]);
            abort(500, 'Gagal memuat file: ' . $e->getMessage());
        }
    }

    /**
     * Download document file - PERBAIKAN AKSES DOKUMEN
     */
    public function downloadDocument(Registration $registration, $documentType)
    {
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            abort(404, 'Jenis dokumen tidak valid.');
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath) {
            abort(404, 'File tidak ditemukan. Path dokumen kosong.');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            \Log::error('Document not found for download', [
                'file_path' => $filePath,
                'document_type' => $documentType,
                'registration_id' => $registration->id
            ]);
            abort(404, 'File tidak ditemukan di storage.');
        }

        $documentNames = [
            'kartu_keluarga' => 'Kartu-Keluarga',
            'ijazah' => 'Ijazah',
            'akta_kelahiran' => 'Akta-Kelahiran',
            'pas_foto' => 'Pas-Foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;
        $fileName = "{$baseName}_{$registration->nama_lengkap}." . pathinfo($filePath, PATHINFO_EXTENSION);

        try {
            return Storage::disk('public')->download($filePath, $fileName);
        } catch (\Exception $e) {
            \Log::error('Error downloading document: ' . $e->getMessage(), [
                'file_path' => $filePath,
                'document_type' => $documentType
            ]);
            abort(500, 'Gagal mendownload file: ' . $e->getMessage());
        }
    }

    /**
     * Get document URL for viewing - Method baru untuk mendapatkan URL
     */
    public function getDocumentUrl(Registration $registration, $documentType)
    {
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            return null;
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return null;
        }

        return [
            'url' => route('admin.registrations.view-document', [$registration->id, $documentType]),
            'download_url' => route('admin.registrations.download-document', [$registration->id, $documentType]),
            'storage_url' => Storage::url($filePath),
            'file_name' => basename($filePath),
            'file_size' => Storage::disk('public')->size($filePath),
            'mime_type' => Storage::disk('public')->mimeType($filePath)
        ];
    }

    /**
     * Get document column name
     */
    private function getDocumentColumn($documentType)
    {
        $columns = [
            'kartu_keluarga' => 'kartu_keluaga_path',
            'ijazah' => 'ijazah_path',
            'akta_kelahiran' => 'akta_kelahiran_path',
            'pas_foto' => 'pas_foto_path'
        ];

        return $columns[$documentType] ?? $documentType . '_path';
    }

    /**
     * Debug document paths - Method untuk troubleshooting
     */
    public function debugDocumentPaths(Registration $registration)
    {
        $documents = [
            'kartu_keluarga' => $registration->kartu_keluaga_path,
            'ijazah' => $registration->ijazah_path,
            'akta_kelahiran' => $registration->akta_kelahiran_path,
            'pas_foto' => $registration->pas_foto_path
        ];

        $results = [];
        foreach ($documents as $type => $path) {
            $results[$type] = [
                'database_path' => $path,
                'storage_exists' => $path ? Storage::disk('public')->exists($path) : false,
                'storage_path' => $path ? Storage::disk('public')->path($path) : null,
                'url' => $path ? Storage::url($path) : null,
                'route_url' => $path ? route('admin.registrations.view-document', [$registration->id, $type]) : null
            ];
        }

        return response()->json($results);
    }
}
