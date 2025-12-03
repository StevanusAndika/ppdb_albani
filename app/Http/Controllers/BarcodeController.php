<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BarcodeController extends Controller
{
    /**
     * Display QR Code information page (public access)
     */
    public function show($id_pendaftaran)
    {
        try {
            $registration = Registration::where('id_pendaftaran', $id_pendaftaran)
                ->with([
                    'package',
                    'user',
                    'payments' => function($query) {
                        // Ambil payment yang terbaru
                        $query->latest()->limit(1);
                    }
                ])
                ->firstOrFail();

            // Generate QR Code jika belum ada
            if (!$registration->hasQrCode()) {
                $registration->generateQrCode();
            }

            // CEK JIKA USER LOGIN SEBAGAI ADMIN, REDIRECT LANGSUNG KE ADMIN
            if (Auth::check() && Auth::user()->isAdmin()) {
                return redirect()->route('admin.registrations.show', $registration->id);
            }

            return view('barcode.info', compact('registration'));
        } catch (\Exception $e) {
            abort(404, 'Data registrasi tidak ditemukan');
        }
    }

    /**
     * Download QR Code image
     */
    public function download($id_pendaftaran)
    {
        try {
            $registration = Registration::where('id_pendaftaran', $id_pendaftaran)
                ->firstOrFail();

            // Generate QR Code jika belum ada
            if (!$registration->hasQrCode()) {
                $registration->generateQrCode();
            }

            $filename = 'qr-codes/' . $id_pendaftaran . '.png';

            if (!Storage::disk('public')->exists($filename)) {
                return redirect()->back()->with('error', 'QR Code tidak ditemukan');
            }

            $path = Storage::disk('public')->path($filename);

            return Response::download($path, 'qr-code-' . $id_pendaftaran . '.png', [
                'Content-Type' => 'image/png',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mendownload QR Code: ' . $e->getMessage());
        }
    }

    /**
     * Get QR Code image
     */
    public function getQrCode($id_pendaftaran)
    {
        try {
            $filename = 'qr-codes/' . $id_pendaftaran . '.png';

            if (!Storage::disk('public')->exists($filename)) {
                $registration = Registration::where('id_pendaftaran', $id_pendaftaran)->first();
                if ($registration) {
                    $registration->generateQrCode();
                } else {
                    abort(404);
                }
            }

            $file = Storage::disk('public')->get($filename);
            return response($file, 200)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Scan QR Code and redirect based on user role - REDIRECT LANGSUNG
     */
    public function scan($id_pendaftaran)
    {
        try {
            // Cek apakah pendaftaran exist
            $registration = Registration::where('id_pendaftaran', $id_pendaftaran)->firstOrFail();

            // Jika user tidak login, redirect ke public info page
            if (!Auth::check()) {
                return redirect()->route('barcode.show', $id_pendaftaran);
            }

            // Cek role user yang login
            $user = Auth::user();

            if ($user->isAdmin()) {
                // Jika admin, redirect LANGSUNG ke halaman detail pendaftaran di admin
                return redirect()->route('admin.registrations.show', $registration->id);
            } else {
                // Jika calon_santri atau role lainnya, redirect ke public info page
                return redirect()->route('barcode.show', $id_pendaftaran);
            }

        } catch (\Exception $e) {
            // Fallback jika ada error, redirect ke public info page
            return redirect()->route('barcode.show', $id_pendaftaran);
        }
    }

    /**
     * Generate QR Code for all registrations (for maintenance)
     */
    public function generateAll()
    {
        try {
            $registrations = Registration::all();
            $generated = 0;
            $errors = 0;

            foreach ($registrations as $registration) {
                if (!$registration->hasQrCode()) {
                    $result = $registration->generateQrCode();
                    if ($result) {
                        $generated++;
                    } else {
                        $errors++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Generated $generated QR Codes, $errors errors",
                'generated' => $generated,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
