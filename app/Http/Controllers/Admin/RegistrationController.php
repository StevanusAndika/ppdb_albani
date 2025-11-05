<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function index()
    {
        // Gunakan pagination untuk menghindari error hasPages()
        $registrations = Registration::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 10 item per halaman

        return view('dashboard.admin.registrations.registrations', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['user', 'package', 'package.prices']);

        // Tandai sebagai telah dilihat
        if ($registration->status_pendaftaran === 'telah_mengisi') {
            $registration->markAsSeen();
        }

        return view('dashboard.admin.registrations.registration-detail', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:telah_dilihat,menunggu_diverifikasi,ditolak,diterima',
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $oldStatus = $registration->status_pendaftaran;
            $newStatus = $request->status;

            switch ($newStatus) {
                case 'telah_dilihat':
                    $registration->markAsSeen();
                    break;
                case 'menunggu_diverifikasi':
                    $registration->markAsPending();
                    break;
                case 'ditolak':
                    $registration->markAsRejected($request->catatan);

                    // Kirim notifikasi WhatsApp
                    $this->sendRejectionNotification($registration);
                    break;
                case 'diterima':
                    $registration->markAsApproved();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diupdate.',
                'status_label' => $registration->status_label
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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

    private function sendRejectionNotification(Registration $registration)
    {
        $phone = $registration->nomor_telpon_orang_tua;
        $namaSantri = $registration->nama_lengkap;
        $alasan = $registration->catatan_admin ?? 'Data yang diisi tidak lengkap atau tidak memenuhi persyaratan.';

        return $this->fonnteService->sendRegistrationRejection($phone, $namaSantri, $alasan);
    }
}
