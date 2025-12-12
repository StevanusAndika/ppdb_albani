<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display all transactions for admin
     */
    public function index()
    {
        $payments = Payment::with(['user', 'registration', 'registration.package'])
                          ->latest()
                          ->paginate(10);

        $stats = [
            'total' => Payment::count(),
            'success' => Payment::whereIn('status', ['success', 'lunas'])->count(),
            'pending' => Payment::whereIn('status', ['pending', 'waiting_payment', 'processing'])->count(),
            'failed' => Payment::whereIn('status', ['failed', 'expired'])->count()
        ];

        return view('dashboard.admin.transactions.index', compact('payments', 'stats'));
    }

    /**
     * Display transaction detail
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'registration', 'registration.package']);

        return view('dashboard.admin.transactions.detail', compact('payment'));
    }

    /**
     * Update payment status manually (for cash payments)
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:lunas,failed',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $payment->status;
            $payment->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'paid_at' => $request->status === 'lunas' ? now() : null
            ]);

            // Update registration status if payment is successful
            if ($request->status === 'lunas' && $oldStatus !== 'lunas') {
                $payment->registration->update([
                    'status_pendaftaran' => 'diterima'
                ]);

                // Kirim notifikasi jika status berubah menjadi lunas
                $fonnte = app('fonnte');
                $fonnte->sendManualPaymentConfirmation(
                    $payment->user->getFormattedPhoneNumber(),
                    $payment->user->name,
                    $payment->payment_code,
                    number_format($payment->amount, 0, ',', '.'),
                    auth()->user()->name
                );
            }

            DB::commit();

            return redirect()->route('admin.transactions.show', $payment)
                ->with('success', 'Status pembayaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Verify bank transfer payment
     */
    public function verifyBankTransfer(Request $request, Payment $payment)
    {
        // Check if payment is bank transfer and waiting verification
        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'waiting_verification') {
            return back()->with('error', 'Pembayaran ini tidak dapat diverifikasi.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $payment->status;
            $payment->update([
                'status' => 'lunas',
                'paid_at' => now(),
                'admin_notes' => ($payment->admin_notes ?? '') . ' | Diverifikasi oleh ' . auth()->user()->name . ' pada ' . now()->format('d-m-Y H:i')
            ]);

            // Update registration status
            $payment->registration->update([
                'status_pendaftaran' => 'diterima'
            ]);

            // Send WhatsApp notification
            $fonnte = app('fonnte');
            $fonnte->sendBankTransferVerified(
                $payment->user->getFormattedPhoneNumber(),
                $payment->user->name,
                $payment->payment_code,
                number_format($payment->amount, 0, ',', '.'),
                auth()->user()->name
            );

            DB::commit();

            return redirect()->route('admin.transactions.show', $payment)
                ->with('success', 'Pembayaran transfer bank berhasil diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject bank transfer payment
     */
    public function rejectBankTransfer(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Check if payment is bank transfer and waiting verification
        if ($payment->payment_method !== 'bank_transfer' || $payment->status !== 'waiting_verification') {
            return back()->with('error', 'Pembayaran ini tidak dapat ditolak.');
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'failed',
                'admin_notes' => 'Ditolak oleh ' . auth()->user()->name . ' | Alasan: ' . $request->rejection_reason . ' | ' . now()->format('d-m-Y H:i')
            ]);

            // Release quota
            Quota::releaseQuota();

            // Send WhatsApp notification
            $fonnte = app('fonnte');
            $fonnte->sendBankTransferRejected(
                $payment->user->getFormattedPhoneNumber(),
                $payment->user->name,
                $payment->payment_code,
                number_format($payment->amount, 0, ',', '.'),
                $request->rejection_reason
            );

            DB::commit();

            return redirect()->route('admin.transactions.show', $payment)
                ->with('success', 'Pembayaran transfer bank berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Search transactions by payment code or user name
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $payments = Payment::with(['user', 'registration', 'registration.package'])
                          ->where('payment_code', 'like', "%{$search}%")
                          ->orWhereHas('user', function($query) use ($search) {
                              $query->where('name', 'like', "%{$search}%");
                          })
                          ->orWhereHas('registration', function($query) use ($search) {
                              $query->where('id_pendaftaran', 'like', "%{$search}%");
                          })
                          ->latest()
                          ->paginate(10);

        $stats = [
            'total' => $payments->total(),
            'success' => Payment::whereIn('status', ['success', 'lunas'])->count(),
            'pending' => Payment::whereIn('status', ['pending', 'waiting_payment', 'processing'])->count(),
            'failed' => Payment::whereIn('status', ['failed', 'expired'])->count()
        ];

        return view('dashboard.admin.transactions.index', compact('payments', 'search', 'stats'));
    }

    /**
     * Export transactions to Excel
     */
    public function export(Request $request)
    {
        // Implement export to Excel functionality here
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }

    /**
     * Bulk update payment status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'status' => 'required|in:lunas,failed',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $updatedCount = 0;

            foreach ($request->payment_ids as $paymentId) {
                $payment = Payment::find($paymentId);

                if ($payment && $payment->isPending()) {
                    $payment->update([
                        'status' => $request->status,
                        'admin_notes' => $request->admin_notes,
                        'paid_at' => $request->status === 'lunas' ? now() : null
                    ]);

                    if ($request->status === 'lunas') {
                        $payment->registration->update([
                            'status_pendaftaran' => 'diterima'
                        ]);
                    }

                    $updatedCount++;
                }
            }

            DB::commit();

            return back()->with('success', "Berhasil memperbarui {$updatedCount} pembayaran.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Download invoice as PDF untuk admin
     */
    public function downloadInvoicePdf($paymentCode)
    {
        try {
            Log::info('=== ADMIN PDF INVOICE GENERATION START ===');
            Log::info('Payment Code: ' . $paymentCode);
            Log::info('Admin ID: ' . auth()->id());

            $payment = Payment::where('payment_code', $paymentCode)
                             ->with([
                                 'user',
                                 'registration',
                                 'registration.package'
                             ])
                             ->first();

            if (!$payment) {
                Log::error('Payment not found for code: ' . $paymentCode);
                return back()->with('error', 'Pembayaran tidak ditemukan.');
            }

            // Validasi hanya admin yang bisa akses
            if (!auth()->user()->isAdmin()) {
                Log::warning('Unauthorized admin PDF access attempt for payment: ' . $paymentCode . ' by user: ' . auth()->id());
                abort(403, 'Unauthorized');
            }

            // Load package prices for detailed breakdown
            $packagePrices = Price::where('package_id', $payment->registration->package_id)
                                 ->active()
                                 ->ordered()
                                 ->get();

            $data = [
                'payment' => $payment,
                'packagePrices' => $packagePrices,
                'isAdmin' => true // Flag untuk template admin
            ];

            Log::info('Generating admin PDF for payment: ' . $paymentCode);

            // Gunakan view yang sama atau khusus admin
            $viewPath = 'dashboard.calon_santri.payments.invoice-pdf';

            if (!view()->exists($viewPath)) {
                Log::error('View not found: ' . $viewPath);
                throw new \Exception('Template invoice tidak ditemukan.');
            }

            // Konfigurasi PDF
            $pdfOptions = [
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
            ];

            // Generate PDF
            $pdf = \PDF::loadView($viewPath, $data)
                       ->setPaper('a4', 'portrait')
                       ->setOptions($pdfOptions);

            $filename = "Invoice-{$payment->payment_code}.pdf";

            Log::info('Admin PDF generated successfully for: ' . $paymentCode);

            // Return download response
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('=== ADMIN PDF GENERATION FAILED ===');
            Log::error('Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal generate invoice. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display invoice page (HTML) untuk admin
     */
    public function downloadInvoice($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)
                         ->with([
                             'user',
                             'registration',
                             'registration.package'
                         ])
                         ->firstOrFail();

        // Validasi hanya admin yang bisa akses
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Load package prices for detailed breakdown
        $packagePrices = Price::where('package_id', $payment->registration->package_id)
                             ->active()
                             ->ordered()
                             ->get();

        return view('dashboard.calon_santri.payments.invoice', compact('payment', 'packagePrices'));
    }

    /**
     * Manual sync payment status untuk admin
     */
    public function manualSync($paymentCode)
    {
        try {
            Log::info('=== ADMIN MANUAL SYNC STARTED ===');
            Log::info('Payment Code: ' . $paymentCode);
            Log::info('Admin ID: ' . auth()->id());

            $payment = Payment::where('payment_code', $paymentCode)->first();

            if (!$payment) {
                Log::error('Payment not found for code: ' . $paymentCode);
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ], 404);
            }

            // Validasi hanya admin yang bisa akses
            if (!auth()->user()->isAdmin()) {
                Log::warning('Unauthorized admin sync attempt', [
                    'current_user_id' => auth()->id()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $statusUpdated = false;
            $oldStatus = $payment->status;
            $message = '';

            // Jika payment via xendit, check status di xendit
            if ($payment->payment_method === 'xendit' && $payment->xendit_id) {
                try {
                    $xendit = app('xendit');
                    Log::info('Admin checking Xendit status for invoice: ' . $payment->xendit_id);

                    $invoiceResult = $xendit->getInvoice($payment->xendit_id);

                    if ($invoiceResult['success']) {
                        $invoice = $invoiceResult['data'];
                        $newStatus = $this->mapXenditStatus($invoice['status']);

                        Log::info('Xendit status received', [
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                            'xendit_status' => $invoice['status']
                        ]);

                        if ($newStatus !== $payment->status) {
                            DB::beginTransaction();
                            try {
                                $updateData = [
                                    'status' => $newStatus,
                                    'xendit_response' => array_merge($payment->xendit_response ?? [], $invoice)
                                ];

                                // Set paid_at jika status success
                                if ($newStatus === 'success') {
                                    $updateData['paid_at'] = isset($invoice['paid_at'])
                                        ? \Carbon\Carbon::parse($invoice['paid_at'])
                                        : now();

                                    // Update registration status
                                    $payment->registration->update([
                                        'status_pendaftaran' => 'diterima'
                                    ]);
                                }

                                $payment->update($updateData);

                                $statusUpdated = true;
                                $message = 'Status pembayaran berhasil diperbarui';

                                Log::info('Payment status updated by admin', [
                                    'payment_id' => $payment->id,
                                    'old_status' => $oldStatus,
                                    'new_status' => $newStatus
                                ]);

                                // Kirim notifikasi jika status berubah menjadi success
                                if ($newStatus === 'success') {
                                    $fonnte = app('fonnte');

                                    // Ambil data program unggulan
                                    $programUnggulanName = 'Tidak ada program unggulan';
                                    if ($payment->registration->program_unggulan_id) {
                                        $programUnggulan = ContentSetting::find($payment->registration->program_unggulan_id);
                                        if ($programUnggulan) {
                                            $programUnggulanName = $programUnggulan->judul ?? 'Program Unggulan';
                                        }
                                    }

                                    // Kirim bukti pembayaran sukses
                                    $fonnte->sendPaymentSuccess(
                                        $payment->user->getFormattedPhoneNumber(),
                                        $payment->user->name,
                                        $payment->payment_code,
                                        number_format($payment->amount, 0, ',', '.'),
                                        $payment->registration->package->name ?? 'Paket Pendaftaran',
                                        $programUnggulanName
                                    );

                                    $message .= ' - Pembayaran berhasil!';
                                }

                                DB::commit();

                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('Error updating payment status: ' . $e->getMessage());
                                Log::error('Stack trace: ' . $e->getTraceAsString());
                                throw new \Exception('Gagal update status pembayaran: ' . $e->getMessage());
                            }
                        } else {
                            $message = 'Status pembayaran sudah up-to-date';
                            Log::info('Payment status already up-to-date');
                        }
                    } else {
                        Log::error('Xendit API error', ['error' => $invoiceResult['message']]);
                        throw new \Exception('Gagal mengambil data dari Xendit: ' . $invoiceResult['message']);
                    }
                } catch (\Exception $e) {
                    Log::error('Xendit check error: ' . $e->getMessage());
                    throw new \Exception('Error checking Xendit: ' . $e->getMessage());
                }
            } else {
                $message = 'Tidak dapat sinkronisasi: Metode pembayaran bukan Xendit atau invoice ID tidak tersedia';
                Log::info('Cannot sync non-xendit payment', [
                    'method' => $payment->payment_method,
                    'has_xendit_id' => !empty($payment->xendit_id)
                ]);
            }

            Log::info('=== ADMIN MANUAL SYNC COMPLETED ===');

            return response()->json([
                'success' => true,
                'status_updated' => $statusUpdated,
                'message' => $message,
                'payment' => [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'status' => $payment->status,
                    'status_label' => $payment->status_label,
                    'status_color' => $payment->status_color,
                    'paid_at' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : null,
                    'formatted_amount' => $payment->formatted_amount,
                    'is_paid' => $payment->isPaid()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Admin manual sync error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manual sync untuk POST request (dari form) untuk admin
     */
    public function manualSyncPost(Request $request, $paymentCode)
    {
        try {
            Log::info('Admin manual sync POST request for payment: ' . $paymentCode);

            // Lakukan sinkronisasi
            $result = $this->manualSync($paymentCode);

            // Jika request dari form, redirect back dengan message
            if ($request->ajax()) {
                return $result;
            } else {
                $data = json_decode($result->getContent(), true);

                if ($data['success']) {
                    return back()->with('success', $data['message']);
                } else {
                    return back()->with('error', $data['message']);
                }
            }

        } catch (\Exception $e) {
            Log::error('Admin manual sync POST error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            } else {
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    /**
     * Map Xendit status to our status
     */
    private function mapXenditStatus($xenditStatus)
    {
        $statusMap = [
            'PENDING' => 'waiting_payment',
            'PAID' => 'success',
            'SETTLED' => 'success',
            'COMPLETED' => 'success',
            'EXPIRED' => 'expired',
            'FAILED' => 'failed',
            'CANCELLED' => 'failed'
        ];

        return $statusMap[$xenditStatus] ?? 'failed';
    }
}
