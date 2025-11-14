<?php

namespace App\Http\Controllers\QRAdmin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRAdminController extends Controller
{
    /**
     * Menampilkan halaman scan QR
     */
    public function index()
    {
        return view('dashboard.admin.useQR.index');
    }

    /**
     * Memproses hasil scan QR dari camera dan redirect ke detail data calon santri
     */
    public function processScan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = $request->qr_data;

            // Bersihkan data QR code jika perlu
            $qrData = $this->cleanQRData($qrData);

            // Cari data pendaftaran berdasarkan ID pendaftaran dari QR code
            $registration = Registration::where('id_pendaftaran', $qrData)
                ->with(['user', 'package'])
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data calon santri tidak ditemukan untuk ID: ' . $qrData
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan',
                'redirect_url' => route('admin.registrations.show', $registration->id),
                'registration_data' => [
                    'id' => $registration->id,
                    'id_pendaftaran' => $registration->id_pendaftaran,
                    'nama_lengkap' => $registration->nama_lengkap,
                    'status_pendaftaran' => $registration->status_pendaftaran,
                    'status_label' => $registration->status_label
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memproses QR code dari gambar/upload menggunakan library
     */
    public function processImage(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Simpan gambar sementara
            $imagePath = $request->file('qr_image')->store('temp-qr', 'public');
            $fullImagePath = storage_path('app/public/' . $imagePath);

            // Decode QR code dari gambar menggunakan library
            $decodedText = $this->decodeQRFromImage($fullImagePath);

            // Hapus file temporary
            Storage::disk('public')->delete($imagePath);

            if (!$decodedText) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat membaca QR code dari gambar. Pastikan gambar jelas dan mengandung QR code yang valid.'
                ], 400);
            }

            // Bersihkan data hasil decode
            $decodedText = $this->cleanQRData($decodedText);

            // Cari data pendaftaran
            $registration = Registration::where('id_pendaftaran', $decodedText)
                ->with(['user', 'package'])
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data calon santri tidak ditemukan untuk ID: ' . $decodedText
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan dari gambar',
                'redirect_url' => route('admin.registrations.show', $registration->id),
                'registration_data' => [
                    'id' => $registration->id,
                    'id_pendaftaran' => $registration->id_pendaftaran,
                    'nama_lengkap' => $registration->nama_lengkap,
                    'status_pendaftaran' => $registration->status_pendaftaran,
                    'status_label' => $registration->status_label
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manual lookup by ID pendaftaran (fallback)
     */
    public function manualLookup(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|string'
        ]);

        try {
            $idPendaftaran = $request->id_pendaftaran;

            $registration = Registration::where('id_pendaftaran', $idPendaftaran)
                ->with(['user', 'package'])
                ->first();

            if (!$registration) {
                return back()->with('error', 'Data calon santri tidak ditemukan untuk ID: ' . $idPendaftaran);
            }

            return redirect()->route('admin.registrations.show', $registration->id);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Decode QR code dari gambar menggunakan library
     */
    private function decodeQRFromImage($imagePath)
    {
        try {
            // SimpleSoftwareIO QR Code decoder
            // Note: Library ini terutama untuk generate QR, untuk decode kita butuh reader
            // Untuk sementara kita simulasi, nanti bisa diintegrasikan dengan library decoder

            return $this->simulateQRDecode($imagePath);

        } catch (\Exception $e) {
            \Log::error('QR Decode Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simulasi decode QR code dari gambar
     * Di production, ganti dengan library decoder sesungguhnya
     */
    private function simulateQRDecode($imagePath)
    {
        // Untuk simulasi, extract dari nama file
        $filename = basename($imagePath);

        // Cari pattern ID pendaftaran dalam nama file
        if (preg_match('/PPDB-\d{4}-\d+/', $filename, $matches)) {
            return $matches[0];
        }

        // Fallback patterns
        $patterns = [
            '/PPDB_\d{4}_\d+/',
            '/ppdb-\d{4}-\d+/',
            '/ID-\d{4}-\d+/',
            '/\b\d{4}-\d{3}\b/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $filename, $matches)) {
                // Format ke standard PPDB-YYYY-NNN
                $match = $matches[0];
                if (preg_match('/(\d{4})-(\d{3})/', $match, $numbers)) {
                    return "PPDB-{$numbers[1]}-{$numbers[2]}";
                }
                return $match;
            }
        }

        return null;
    }

    /**
     * Bersihkan dan format data QR code
     */
    private function cleanQRData($qrData)
    {
        // Hapus whitespace
        $qrData = trim($qrData);

        // Decode URL encoded characters
        $qrData = urldecode($qrData);

        // Cek dan format ke pattern standar
        if (preg_match('/(PPDB|ppdb)[_\-\s]*(\d{4})[_\-\s]*(\d+)/i', $qrData, $matches)) {
            return "PPDB-{$matches[2]}-" . str_pad($matches[3], 3, '0', STR_PAD_LEFT);
        }

        // Cek pattern angka saja
        if (preg_match('/(\d{4})[_\-\s]*(\d+)/', $qrData, $matches)) {
            return "PPDB-{$matches[1]}-" . str_pad($matches[2], 3, '0', STR_PAD_LEFT);
        }

        return $qrData;
    }

    /**
     * Generate QR code untuk testing (optional)
     */
    public function generateTestQR($idPendaftaran)
    {
        try {
            // Generate QR code
            $qrCode = QrCode::size(300)
                ->format('png')
                ->generate($idPendaftaran);

            return response($qrCode)
                ->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }
}
