<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            // Kirim notifikasi jika status berubah menjadi lunas
            if ($request->status === 'lunas' && $oldStatus !== 'lunas') {
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
}
