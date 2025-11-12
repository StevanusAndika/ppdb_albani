<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Package;
use App\Models\Price;
use App\Models\ContentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display payment list for calon santri
     */
    public function index()
    {
        $user = auth()->user();
        $payments = Payment::where('user_id', $user->id)
                          ->with(['registration', 'registration.package', 'registration.programUnggulan'])
                          ->latest()
                          ->get();

        $registration = Registration::where('user_id', $user->id)
                                   ->with(['package', 'programUnggulan'])
                                   ->first();

        $hasSuccessfulPayment = $registration ? $registration->hasSuccessfulPayment() : false;

        return view('dashboard.calon_santri.payments.index', compact('payments', 'hasSuccessfulPayment', 'registration'));
    }

    /**
     * Display payment form for calon santri
     */
    public function create()
    {
        $user = auth()->user();
        $registration = Registration::where('user_id', $user->id)
                                   ->with(['package', 'programUnggulan'])
                                   ->first();

        // Validasi status registrasi
        if (!$registration || $registration->status_pendaftaran === 'belum_mendaftar') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda belum menyelesaikan pendaftaran. Silakan lengkapi pendaftaran terlebih dahulu.');
        }

        // Validasi kelengkapan dokumen
        if (!$registration->hasAllDocuments()) {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda belum melengkapi semua dokumen. Silakan upload semua dokumen terlebih dahulu sebelum melakukan pembayaran.');
        }

        // Cek apakah sudah ada pembayaran yang berhasil
        if ($registration->hasSuccessfulPayment()) {
            return redirect()->route('santri.payments.index')
                ->with('info', 'Anda sudah memiliki pembayaran yang berhasil. Tidak perlu melakukan pembayaran lagi.');
        }

        // Cek apakah ada pembayaran pending
        $pendingPayment = Payment::where('registration_id', $registration->id)
            ->whereIn('status', ['pending', 'waiting_payment', 'processing'])
            ->first();

        if ($pendingPayment) {
            if ($pendingPayment->payment_method === 'xendit' && $pendingPayment->xendit_response) {
                return redirect($pendingPayment->xendit_response['invoice_url'])
                    ->with('info', 'Anda memiliki pembayaran yang belum diselesaikan. Silakan lanjutkan pembayaran tersebut.');
            }
            return redirect()->route('santri.payments.index')
                ->with('info', 'Anda sudah memiliki pembayaran yang sedang diproses.');
        }

        // Ambil rincian harga dari paket yang dipilih
        $packagePrices = Price::where('package_id', $registration->package_id)
                            ->active()
                            ->ordered()
                            ->get();

        // Ambil nama program unggulan dengan benar
        $programUnggulanName = 'Tidak ada program unggulan';
        if ($registration->program_unggulan_id) {
            $programUnggulan = ContentSetting::find($registration->program_unggulan_id);
            if ($programUnggulan) {
                $programUnggulanName = $programUnggulan->judul ?? 'Program Unggulan';
            }
        }

        return view('dashboard.calon_santri.payments.create', compact(
            'registration',
            'packagePrices',
            'programUnggulanName'
        ));
    }

    /**
     * Process payment
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $registration = Registration::where('user_id', $user->id)
                                   ->with(['package', 'programUnggulan'])
                                   ->first();

        // Validasi
        if (!$registration || !$registration->hasAllDocuments()) {
            return back()->with('error', 'Anda belum melengkapi semua persyaratan.');
        }

        // Cek apakah sudah ada pembayaran berhasil
        if ($registration->hasSuccessfulPayment()) {
            return back()->with('error', 'Anda sudah memiliki pembayaran yang berhasil. Tidak perlu melakukan pembayaran lagi.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,xendit'
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = $registration->total_biaya;

            // Validasi amount tidak boleh null atau 0
            if (is_null($totalAmount) || $totalAmount <= 0) {
                // Cek data package dan prices untuk debugging
                $package = Package::with(['prices' => function($query) {
                    $query->active();
                }])->find($registration->package_id);

                Log::error('Invalid payment amount:', [
                    'registration_id' => $registration->id,
                    'package_id' => $registration->package_id,
                    'package_name' => $package->name ?? 'No package',
                    'package_total_amount' => $package->total_amount ?? 0,
                    'prices_count' => $package->prices->count() ?? 0,
                    'prices_total' => $package->prices->sum('amount') ?? 0,
                    'calculated_total' => $totalAmount
                ]);

                throw new \Exception('Jumlah pembayaran tidak valid. Silakan hubungi admin. Package: ' . ($package->name ?? 'Unknown'));
            }

            // Generate payment code
            $paymentCode = 'PAY-' . date('YmdHis') . '-' . strtoupper(uniqid());

            // Create payment record
            $payment = Payment::create([
                'registration_id' => $registration->id,
                'user_id' => $user->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_code' => $paymentCode
            ]);

            Log::info('Payment created:', [
                'payment_id' => $payment->id,
                'amount' => $totalAmount,
                'method' => $request->payment_method,
                'payment_code' => $paymentCode
            ]);

            if ($request->payment_method === 'cash') {
                $payment->update([
                    'status' => 'waiting_payment',
                    'admin_notes' => 'Menunggu pembayaran cash di pesantren'
                ]);

                // Kirim notifikasi WhatsApp
                $fonnte = app('fonnte');
                $fonnte->sendCashPaymentInstruction(
                    $user->getFormattedPhoneNumber(),
                    $user->name,
                    $payment->payment_code,
                    number_format($totalAmount, 0, ',', '.')
                );

                DB::commit();

                return redirect()->route('santri.payments.index')
                    ->with('success', 'Silahkan datang ke Pesantren Al-Qur\'an Bani Syahid untuk melakukan pembayaran kepada admin.');

            } else { // xendit
                $xendit = app('xendit');

                // Ambil nama program unggulan untuk description
                $programUnggulanName = 'Tidak ada program unggulan';
                if ($registration->program_unggulan_id) {
                    $programUnggulan = ContentSetting::find($registration->program_unggulan_id);
                    if ($programUnggulan) {
                        $programUnggulanName = $programUnggulan->judul ?? 'Program Unggulan';
                    }
                }

                // Data untuk Xendit
                $xenditData = [
                    'external_id' => $payment->payment_code,
                    'amount' => $totalAmount,
                    'description' => 'Pembayaran Pendaftaran Santri - ' . $registration->id_pendaftaran . ' - Program: ' . $programUnggulanName,
                    'payer_email' => $user->email,
                    'customer' => [
                        'given_names' => $user->name,
                        'email' => $user->email,
                        'mobile_number' => $user->phone_number ? $user->getFormattedPhoneNumber() : null
                    ],
                    'items' => [
                        [
                            'name' => 'Pendaftaran Santri - ' . $registration->package->name,
                            'quantity' => 1,
                            'price' => $totalAmount,
                            'category' => 'Education'
                        ]
                    ]
                ];

                Log::info('Creating Xendit invoice with data:', $xenditData);

                $xenditResult = $xendit->createInvoice($xenditData);

                if ($xenditResult['success']) {
                    $invoice = $xenditResult['data'];

                    $payment->update([
                        'xendit_id' => $invoice['id'],
                        'xendit_external_id' => $invoice['external_id'],
                        'xendit_response' => $invoice,
                        'status' => 'waiting_payment',
                        'expired_at' => $invoice['expiry_date']
                    ]);

                    // Kirim notifikasi WhatsApp
                    $fonnte = app('fonnte');
                    $fonnte->sendInvoiceCreated(
                        $user->getFormattedPhoneNumber(),
                        $user->name,
                        $payment->payment_code,
                        number_format($totalAmount, 0, ',', '.'),
                        \Carbon\Carbon::parse($invoice['expiry_date'])->translatedFormat('d F Y H:i'),
                        $invoice['invoice_url']
                    );

                    DB::commit();

                    return redirect($invoice['invoice_url']);

                } else {
                    DB::rollBack();
                    Log::error('Xendit invoice creation failed:', [
                        'error' => $xenditResult['message'],
                        'payment_id' => $payment->id
                    ]);
                    return back()->with('error', 'Gagal membuat pembayaran: ' . $xenditResult['message']);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment store error: ' . $e->getMessage());
            Log::error('Payment store trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display payment success page
     */
    public function success(Request $request)
    {
        $user = auth()->user();
        $registration = Registration::where('user_id', $user->id)
                                   ->with(['package', 'programUnggulan'])
                                   ->first();
        $latestPayment = $registration ? $registration->payments()->latest()->first() : null;

        return view('dashboard.calon_santri.payments.success', compact('latestPayment', 'registration'));
    }

    /**
     * Display payment failed page
     */
    public function failed(Request $request)
    {
        return view('dashboard.calon_santri.payments.failed');
    }

    /**
     * Download invoice as PDF
     */
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

        // Validasi ownership untuk santri
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$payment->isPaid()) {
            return back()->with('error', 'Invoice hanya tersedia untuk pembayaran yang berhasil.');
        }

        // Load package prices for detailed breakdown
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

    /**
     * Display invoice page (HTML) - DIPERBAIKI dengan null safety
     */
    public function downloadInvoice($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)
                         ->with([
                             'user',
                             'registration',
                             'registration.package',
                             'registration.programUnggulan'
                         ])
                         ->firstOrFail();

        // Validasi ownership untuk santri
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$payment->isPaid()) {
            return back()->with('error', 'Invoice hanya tersedia untuk pembayaran yang berhasil.');
        }

        // Load package prices for detailed breakdown
        $packagePrices = Price::where('package_id', $payment->registration->package_id)
                             ->active()
                             ->ordered()
                             ->get();

        return view('dashboard.calon_santri.payments.invoice', compact('payment', 'packagePrices'));
    }

    /**
     * Get payment detail for AJAX - DIPERBAIKI dengan null safety
     */
    public function detail($id)
    {
        $payment = Payment::with([
            'user',
            'registration',
            'registration.package',
            'registration.programUnggulan'
        ])->where('id', $id)->firstOrFail();

        // Validasi ownership
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        // Ambil rincian harga
        $packagePrices = Price::where('package_id', $payment->registration->package_id)
                            ->active()
                            ->ordered()
                            ->get();

        $html = view('dashboard.calon_santri.payments.partials.detail', compact('payment', 'packagePrices'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Xendit webhook handler - DIPERBAIKI dengan kirim bukti pembayaran
     */
    public function webhook(Request $request)
    {
        Log::info('=== XENDIT WEBHOOK RECEIVED ===');
        Log::info('Webhook Headers:', $request->headers->all());
        Log::info('Webhook Payload:', $request->all());

        // Validasi webhook signature
        $callbackToken = $request->header('x-callback-token');
        $verificationToken = config('xendit.verification_token');

        Log::info('Token Verification:', [
            'received' => $callbackToken ? substr($callbackToken, 0, 10) . '...' : 'NULL',
            'expected' => $verificationToken ? substr($verificationToken, 0, 10) . '...' : 'NULL'
        ]);

        if ($verificationToken && $callbackToken !== $verificationToken) {
            Log::error('Xendit webhook token mismatch', [
                'received' => $callbackToken,
                'expected' => $verificationToken
            ]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi payload wajib
        if (!$request->has(['id', 'external_id', 'status'])) {
            Log::error('Xendit webhook missing required fields', [
                'payload' => $request->all()
            ]);
            return response()->json(['message' => 'Missing required fields'], 400);
        }

        $xenditId = $request->id;
        $externalId = $request->external_id;
        $status = $request->status;

        Log::info('Processing webhook:', [
            'xendit_id' => $xenditId,
            'external_id' => $externalId,
            'status' => $status
        ]);

        // Cari payment berdasarkan xendit_id atau external_id
        $payment = Payment::with(['user', 'registration', 'registration.package', 'registration.programUnggulan'])
                         ->where('xendit_id', $xenditId)
                         ->orWhere('xendit_external_id', $externalId)
                         ->orWhere('payment_code', $externalId)
                         ->first();

        if (!$payment) {
            Log::error('Payment not found for webhook', [
                'xendit_id' => $xenditId,
                'external_id' => $externalId,
                'available_payments' => Payment::select('payment_code', 'xendit_id', 'xendit_external_id')->get()->toArray()
            ]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        Log::info('Payment found:', [
            'payment_id' => $payment->id,
            'payment_code' => $payment->payment_code,
            'current_status' => $payment->status,
            'new_status' => $status
        ]);

        // Jika payment sudah sukses, jangan proses lagi
        if ($payment->isPaid()) {
            Log::info('Payment already paid, skipping webhook', [
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);
            return response()->json(['message' => 'Payment already processed']);
        }

        DB::beginTransaction();
        try {
            $newStatus = $this->mapXenditStatus($status);

            $updateData = [
                'status' => $newStatus,
                'xendit_response' => array_merge($payment->xendit_response ?? [], $request->all())
            ];

            // Set paid_at jika status success
            if ($newStatus === 'success') {
                $updateData['paid_at'] = $request->paid_at ? \Carbon\Carbon::parse($request->paid_at) : now();
            }

            $payment->update($updateData);

            Log::info('Payment updated successfully', [
                'payment_id' => $payment->id,
                'old_status' => $payment->getOriginal('status'),
                'new_status' => $newStatus
            ]);

            // Kirim notifikasi WhatsApp berdasarkan status
            $fonnte = app('fonnte');
            $user = $payment->user;

            switch ($newStatus) {
                case 'success':
                    // Ambil data program unggulan dengan benar
                    $programUnggulanName = 'Tidak ada program unggulan';
                    if ($payment->registration->program_unggulan_id) {
                        $programUnggulan = ContentSetting::find($payment->registration->program_unggulan_id);
                        if ($programUnggulan) {
                            $programUnggulanName = $programUnggulan->judul ?? 'Program Unggulan';
                        }
                    }

                    // Kirim bukti pembayaran sukses
                    $fonnte->sendPaymentSuccess(
                        $user->getFormattedPhoneNumber(),
                        $user->name,
                        $payment->payment_code,
                        number_format($payment->amount, 0, ',', '.'),
                        $payment->registration->package->name ?? 'Paket Pendaftaran',
                        $programUnggulanName
                    );

                    // Update status pendaftaran
                    $payment->registration->update([
                        'status_pendaftaran' => 'diterima'
                    ]);

                    Log::info('Registration status updated to diterima', [
                        'registration_id' => $payment->registration->id
                    ]);
                    break;

                case 'failed':
                    $failureReason = $request->failure_reason ?? 'Tidak diketahui';
                    $fonnte->sendPaymentFailed(
                        $user->getFormattedPhoneNumber(),
                        $user->name,
                        $payment->payment_code,
                        number_format($payment->amount, 0, ',', '.'),
                        $failureReason
                    );
                    break;

                case 'expired':
                    $fonnte->sendPaymentExpired(
                        $user->getFormattedPhoneNumber(),
                        $user->name,
                        $payment->payment_code,
                        number_format($payment->amount, 0, ',', '.')
                    );
                    break;
            }

            DB::commit();

            Log::info('Webhook processed successfully', [
                'payment_id' => $payment->id,
                'status' => $newStatus
            ]);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Error processing webhook: ' . $e->getMessage()], 500);
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

    /**
     * Check payment status manually - DIPERBAIKI untuk auto sync
     */
    public function checkStatus($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();

        // Validasi ownership
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $statusUpdated = false;

        // Jika payment via xendit dan masih pending, check status di xendit
        if ($payment->payment_method === 'xendit' && $payment->isPending() && $payment->xendit_id) {
            try {
                $xendit = app('xendit');
                $invoiceResult = $xendit->getInvoice($payment->xendit_id);

                if ($invoiceResult['success']) {
                    $invoice = $invoiceResult['data'];
                    $newStatus = $this->mapXenditStatus($invoice['status']);

                    if ($newStatus !== $payment->status) {
                        $payment->update([
                            'status' => $newStatus,
                            'xendit_response' => array_merge($payment->xendit_response ?? [], $invoice)
                        ]);

                        $statusUpdated = true;

                        Log::info('Payment status updated manually', [
                            'payment_id' => $payment->id,
                            'old_status' => $payment->getOriginal('status'),
                            'new_status' => $newStatus
                        ]);

                        // Jika status berubah menjadi success, kirim notifikasi
                        if ($newStatus === 'success') {
                            $fonnte = app('fonnte');
                            $user = $payment->user;

                            // Ambil data program unggulan
                            $programUnggulanName = 'Tidak ada program unggulan';
                            if ($payment->registration->program_unggulan_id) {
                                $programUnggulan = ContentSetting::find($payment->registration->program_unggulan_id);
                                if ($programUnggulan) {
                                    $programUnggulanName = $programUnggulan->judul ?? 'Program Unggulan';
                                }
                            }

                            $fonnte->sendPaymentSuccess(
                                $user->getFormattedPhoneNumber(),
                                $user->name,
                                $payment->payment_code,
                                number_format($payment->amount, 0, ',', '.'),
                                $payment->registration->package->name ?? 'Paket Pendaftaran',
                                $programUnggulanName
                            );

                            // Update status pendaftaran
                            $payment->registration->update([
                                'status_pendaftaran' => 'diterima'
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'status_updated' => $statusUpdated,
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
    }

    /**
     * Retry expired payment
     */
    public function retryPayment($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();

        // Validasi ownership
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        // Hanya bisa retry payment yang expired
        if ($payment->status !== 'expired') {
            return back()->with('error', 'Hanya pembayaran yang kadaluarsa yang bisa diulang.');
        }

        // Cek apakah sudah ada payment berhasil untuk registrasi ini
        if ($payment->registration->hasSuccessfulPayment()) {
            return back()->with('error', 'Anda sudah memiliki pembayaran yang berhasil.');
        }

        // Redirect ke create payment
        return redirect()->route('santri.payments.create');
    }

    /**
     * Manual sync payment status
     */
    public function manualSync($paymentCode)
    {
        $payment = Payment::where('payment_code', $paymentCode)->firstOrFail();

        // Validasi ownership
        if (auth()->user()->isCalonSantri() && $payment->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($payment->payment_method === 'xendit' && $payment->xendit_id) {
            try {
                $xendit = app('xendit');
                $invoiceResult = $xendit->getInvoice($payment->xendit_id);

                if ($invoiceResult['success']) {
                    $invoice = $invoiceResult['data'];
                    $newStatus = $this->mapXenditStatus($invoice['status']);

                    if ($newStatus !== $payment->status) {
                        $payment->update([
                            'status' => $newStatus,
                            'xendit_response' => array_merge($payment->xendit_response ?? [], $invoice)
                        ]);

                        return back()->with('success', 'Status pembayaran berhasil disinkronisasi.');
                    } else {
                        return back()->with('info', 'Status pembayaran sudah up-to-date.');
                    }
                } else {
                    return back()->with('error', 'Gagal mengambil data dari Xendit: ' . $invoiceResult['message']);
                }
            } catch (\Exception $e) {
                Log::error('Manual sync error: ' . $e->getMessage());
                return back()->with('error', 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
            }
        }

        return back()->with('info', 'Tidak ada pembaruan status.');
    }
}
