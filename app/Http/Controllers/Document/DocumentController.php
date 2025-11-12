<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;

class DocumentController extends Controller
{
    private $allowedMimes = ['pdf', 'jpeg', 'jpg', 'png'];
    private $maxFileSize = 5120; // 5MB in KB
    private $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function index()
    {
        $user = Auth::user();

        // Load registration dengan package dan programUnggulan
        $registration = Registration::with(['package', 'programUnggulan'])
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return redirect()->route('santri.biodata.index')
                ->with('error', 'Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.');
        }

        // Cek jika status pendaftaran = diterima
        if ($registration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengunggah atau mengedit dokumen karena status pendaftaran sudah DITERIMA.');
        }

        // Pastikan package terload dengan benar
        if ($registration->package_id && !$registration->relationLoaded('package')) {
            $package = Package::find($registration->package_id);
            $registration->setRelation('package', $package);
        }

        $totalBiaya = $registration->total_biaya;
        $programUnggulanId = $registration->program_unggulan_id;

        // Handle program unggulan name
        $programUnggulanName = 'Belum dipilih';
        if ($registration->programUnggulan) {
            $programUnggulanName = $registration->programUnggulan->name ?? "Program #{$programUnggulanId}";
        } elseif ($programUnggulanId) {
            $programUnggulanName = "Program #{$programUnggulanId}";
        }

        return view('dashboard.calon_santri.dokumen.dokumen', compact(
            'registration',
            'totalBiaya',
            'programUnggulanId',
            'programUnggulanName'
        ));
    }

    public function upload(Request $request, $documentType)
    {
        // Pastikan response selalu JSON
        $response = response()->json([], 200, [], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }

            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan isi biodata terlebih dahulu.'
                ], 400);
            }

            // Cek jika status pendaftaran = diterima
            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengunggah atau mengedit dokumen karena status pendaftaran sudah DITERIMA.'
                ], 403);
            }

            if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis dokumen tidak valid.'
                ], 400);
            }

            // Validasi file
            $validator = validator($request->all(), [
                'file' => 'required|file|mimes:' . implode(',', $this->allowedMimes) . '|max:' . $this->maxFileSize
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $file = $request->file('file');

            // Hapus file lama jika ada
            $this->deleteOldFile($registration, $documentType);

            // Upload file baru
            $filePath = $this->uploadFile($file, $documentType, $user, $registration);

            // Update path di database
            $column = $this->getDocumentColumn($documentType);
            $registration->update([$column => $filePath]);

            // Refresh model untuk mendapatkan data terbaru
            $registration->refresh();

            // Check jika semua dokumen sudah lengkap
            $allComplete = $registration->hasAllDocuments();
            if ($allComplete) {
                $registration->markAsPending();
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah.' . ($allComplete ? ' Semua dokumen telah lengkap!' : ''),
                'file_path' => $filePath,
                'file_name' => basename($filePath),
                'document_type' => $documentType,
                'all_documents_complete' => $allComplete,
                'refresh_required' => $allComplete
            ]);

        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'document_type' => $documentType,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file dengan struktur folder yang efisien
     */
    private function uploadFile($file, $documentType, $user, $registration)
    {
        $originalExtension = strtolower($file->getClientOriginalExtension());
        $fileName = $this->generateFileName($file, $documentType, $originalExtension);
        $folderPath = $this->getEfficientFolderPath($user, $registration);

        // Buat folder jika belum ada
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath, 0755, true);
        }

        $fullPath = $folderPath . '/' . $fileName;

        // Untuk file gambar, optimalkan tanpa mengubah format
        if (in_array($originalExtension, ['jpeg', 'jpg', 'png'])) {
            try {
                $image = $this->imageManager->read($file->getRealPath());
                $image->scaleDown(1200); // Optimasi ukuran

                if ($originalExtension === 'png') {
                    $encodedImage = $image->encode(new PngEncoder());
                } else {
                    $encodedImage = $image->encode(new JpegEncoder(85));
                }

                Storage::disk('public')->put($fullPath, $encodedImage->toString());
                return $fullPath;

            } catch (\Exception $e) {
                \Log::warning('Image processing failed, using direct upload: ' . $e->getMessage());
                // Fallback: upload langsung
                $file->storeAs($folderPath, $fileName, 'public');
                return $fullPath;
            }
        } else {
            // Untuk PDF, upload langsung
            $file->storeAs($folderPath, $fileName, 'public');
            return $fullPath;
        }
    }

    /**
     * Struktur folder yang efisien: documents/{user_id}_{sanitized_name}/
     */
    private function getEfficientFolderPath($user, $registration)
    {
        $userName = $this->sanitizeFileName($user->name);
        $userId = $user->id;

        // Gunakan ID pendaftaran untuk konsistensi
        $registrationId = $registration->id_pendaftaran ?? 'user_' . $userId;

        return "documents/{$registrationId}_{$userName}";
    }

    private function generateFileName($file, $documentType, $originalExtension)
    {
        $timestamp = now()->format('Ymd_His');
        $randomString = Str::random(6);
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);

        $documentNames = [
            'kartu_keluarga' => 'kartu-keluarga',
            'ijazah' => 'ijazah',
            'akta_kelahiran' => 'akta-kelahiran',
            'pas_foto' => 'pas-foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;

        return "{$baseName}_{$sanitizedName}_{$timestamp}_{$randomString}.{$originalExtension}";
    }

    private function sanitizeFileName($name)
    {
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $sanitized = preg_replace('/_{2,}/', '_', $sanitized);
        $sanitized = trim($sanitized, '_');
        return $sanitized ?: 'user';
    }

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
     * Hapus file lama dengan pembersihan yang proper
     */
    private function deleteOldFile($registration, $documentType)
    {
        $column = $this->getDocumentColumn($documentType);
        $oldFilePath = $registration->$column;

        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            try {
                // Hapus file
                Storage::disk('public')->delete($oldFilePath);

                // Cek dan hapus folder jika kosong
                $this->cleanupEmptyFolders($oldFilePath);

            } catch (\Exception $e) {
                \Log::error('Error deleting old file: ' . $e->getMessage(), [
                    'file_path' => $oldFilePath,
                    'document_type' => $documentType
                ]);
            }
        }
    }

    /**
     * Bersihkan folder kosong setelah menghapus file
     */
    private function cleanupEmptyFolders($filePath)
    {
        $directory = dirname($filePath);

        // Cek jika directory adalah folder documents/...
        if (strpos($directory, 'documents/') === 0) {
            try {
                // Cek jika folder kosong
                $filesInDirectory = Storage::disk('public')->files($directory);
                $subdirectories = Storage::disk('public')->directories($directory);

                if (empty($filesInDirectory) && empty($subdirectories)) {
                    Storage::disk('public')->deleteDirectory($directory);
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking empty directory: ' . $e->getMessage());
            }
        }
    }

    public function delete($documentType)
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            // Cek jika status pendaftaran = diterima
            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus dokumen karena status pendaftaran sudah DITERIMA.'
                ], 403);
            }

            if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis dokumen tidak valid.'
                ], 400);
            }

            $this->deleteOldFile($registration, $documentType);

            $column = $this->getDocumentColumn($documentType);
            $registration->update([$column => null]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.',
                'document_type' => $documentType
            ]);

        } catch (\Exception $e) {
            \Log::error('Delete document error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'document_type' => $documentType,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFile($documentType)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            abort(404, 'Data registrasi tidak ditemukan.');
        }

        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            abort(404, 'Jenis dokumen tidak valid.');
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        $fileName = basename($filePath);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    /**
     * Download document file
     */
    public function download($documentType)
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return back()->with('error', 'Data registrasi tidak ditemukan.');
            }

            if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
                return back()->with('error', 'Jenis dokumen tidak valid.');
            }

            $column = $this->getDocumentColumn($documentType);
            $filePath = $registration->$column;

            if (!$filePath || !Storage::disk('public')->exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan. Silakan upload file terlebih dahulu.');
            }

            $downloadName = $this->getDownloadFileName($documentType, $user->name, $filePath);
            return Storage::disk('public')->download($filePath, $downloadName);

        } catch (\Exception $e) {
            \Log::error('Download error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'document_type' => $documentType,
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal mendownload file: ' . $e->getMessage());
        }
    }

    private function getDownloadFileName($documentType, $userName, $filePath)
    {
        $documentNames = [
            'kartu_keluarga' => 'Kartu-Keluarga',
            'ijazah' => 'Ijazah',
            'akta_kelahiran' => 'Akta-Kelahiran',
            'pas_foto' => 'Pas-Foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;
        $sanitizedName = $this->sanitizeFileName($userName);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        return "{$baseName}_{$sanitizedName}.{$fileExtension}";
    }

    public function completeRegistration()
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            // Cek jika status pendaftaran = diterima
            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menyelesaikan pendaftaran karena status sudah DITERIMA.'
                ], 403);
            }

            if (!$registration->hasAllDocuments()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua dokumen harus diunggah sebelum menyelesaikan pendaftaran.'
                ], 400);
            }

            $registration->markAsPending();

            \Log::info('Registration completed', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'id_pendaftaran' => $registration->id_pendaftaran
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil diselesaikan! Data Anda sedang diverifikasi oleh admin.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Complete registration error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upload progress
     */
    public function getProgress()
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'progress' => $registration->upload_progress
            ]);

        } catch (\Exception $e) {
            \Log::error('Get progress error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download all documents as ZIP
     */
    public function downloadAll()
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            // Check if all documents are uploaded
            if (!$registration->hasAllDocuments()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua dokumen harus diunggah sebelum dapat didownload.'
                ], 400);
            }

            // Create ZIP file
            $zipFileName = "Dokumen_Pendaftaran_{$user->name}.zip";
            $zipPath = storage_path("app/temp/{$zipFileName}");

            // Ensure temp directory exists
            if (!file_exists(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                // Add each document to ZIP
                $documents = [
                    'kartu_keluarga' => $registration->kartu_keluaga_path,
                    'ijazah' => $registration->ijazah_path,
                    'akta_kelahiran' => $registration->akta_kelahiran_path,
                    'pas_foto' => $registration->pas_foto_path
                ];

                $documentNames = [
                    'kartu_keluarga' => 'Kartu-Keluarga',
                    'ijazah' => 'Ijazah',
                    'akta_kelahiran' => 'Akta-Kelahiran',
                    'pas_foto' => 'Pas-Foto'
                ];

                foreach ($documents as $type => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        $fileContent = Storage::disk('public')->get($filePath);
                        $fileName = $documentNames[$type] . '_' . $user->name . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                        $zip->addFromString($fileName, $fileContent);
                    }
                }

                $zip->close();

                // Return ZIP file as download
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);

            } else {
                throw new \Exception('Gagal membuat file ZIP.');
            }

        } catch (\Exception $e) {
            \Log::error('Download all documents error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            // Clean up temp file if exists
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendownload semua dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
