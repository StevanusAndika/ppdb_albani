<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Registration;
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
        // Initialize ImageManager dengan GD driver
        $this->imageManager = new ImageManager(new Driver());
    }

    public function index()
    {
        $user = Auth::user();
        $registration = Registration::with('package')->where('user_id', $user->id)->first();

        if (!$registration) {
            return redirect()->route('santri.biodata.index')
                ->with('error', 'Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.');
        }

        return view('dashboard.calon_santri.dokumen.dokumen', compact('registration'));
    }

    public function upload(Request $request, $documentType)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi biodata terlebih dahulu.'
            ], 400);
        }

        // Validasi document type
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak valid.'
            ], 400);
        }

        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:' . implode(',', $this->allowedMimes) . '|max:' . $this->maxFileSize
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors()['file'])
            ], 422);
        }

        try {
            $file = $request->file('file');
            $fileName = $this->generateFileName($file, $documentType);
            $folderPath = $this->getFolderPath($user, $documentType);

            // Hapus file lama jika ada
            $this->deleteOldFile($registration, $documentType);

            // Konversi ke PDF jika file gambar
            if (in_array(strtolower($file->getClientOriginalExtension()), ['jpeg', 'jpg', 'png'])) {
                $filePath = $this->processImage($file, $folderPath, $fileName);
            } else {
                // Simpan file PDF langsung
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
            }

            // Update path di database
            $registration->update([$this->getDocumentColumn($documentType) => $filePath]);

            // Update status pendaftaran jika semua dokumen lengkap
            if ($registration->hasAllDocuments()) {
                $registration->markAsPending();
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah.',
                'file_path' => $filePath,
                'file_name' => $fileName,
                'document_type' => $documentType
            ]);

        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'document_type' => $documentType,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processImage($file, $folderPath, $fileName)
    {
        // Ganti extension menjadi .pdf untuk konsistensi
        $pdfFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.pdf';

        // Buat folder jika belum ada
        $fullPath = "public/{$folderPath}";
        if (!Storage::exists($fullPath)) {
            Storage::makeDirectory($fullPath, 0755, true);
        }

        try {
            // Process dengan Intervention Image v3
            $image = $this->imageManager->read($file->getRealPath());

            // Optimalkan gambar - resize maintaining aspect ratio
            $image->scaleDown(1200);

            // Simpan sebagai JPEG dengan kualitas baik
            $imagePath = "{$folderPath}/{$pdfFileName}";

            // Encode berdasarkan tipe file asli - TANPA NAMED PARAMETERS
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'png') {
                // Untuk PNG, gunakan PngEncoder tanpa parameter quality
                $encodedImage = $image->encode(new PngEncoder());
            } else {
                // Untuk JPEG, gunakan JpegEncoder dengan quality sebagai argument biasa
                $encodedImage = $image->encode(new JpegEncoder(85));
            }

            Storage::disk('public')->put($imagePath, $encodedImage->toString());

            return $imagePath;

        } catch (\Exception $e) {
            \Log::warning('Intervention Image v3 failed, using GD fallback: ' . $e->getMessage());
            return $this->processImageWithGD($file, $folderPath, $pdfFileName);
        }
    }

    private function processImageWithGD($file, $folderPath, $fileName)
    {
        $sourcePath = $file->getRealPath();
        $destinationPath = storage_path("app/public/{$folderPath}/{$fileName}");

        // Get image info
        $imageInfo = getimagesize($sourcePath);
        $mimeType = $imageInfo['mime'];

        // Create image from source
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }

        if (!$sourceImage) {
            throw new \Exception('Failed to create image from source');
        }

        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calculate new dimensions (max width 1200px, maintain aspect ratio)
        $newWidth = min($originalWidth, 1200);
        $newHeight = (int) ($originalHeight * ($newWidth / $originalWidth));

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        } else {
            // Add white background for JPEG
            $white = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $white);
        }

        // Resize image
        imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Save image
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($newImage, $destinationPath, 85);
                break;
            case 'image/png':
                imagepng($newImage, $destinationPath, 8);
                break;
            case 'image/gif':
                imagegif($newImage, $destinationPath);
                break;
        }

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return "{$folderPath}/{$fileName}";
    }

    private function generateFileName($file, $documentType)
    {
        $timestamp = now()->format('Ymd_His');
        $randomString = Str::random(8);
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);

        $documentNames = [
            'kartu_keluarga' => 'kartu-keluarga',
            'ijazah' => 'ijazah',
            'akta_kelahiran' => 'akta-kelahiran',
            'pas_foto' => 'pas-foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;

        return "{$baseName}_{$sanitizedName}_{$timestamp}_{$randomString}.pdf";
    }

    private function getFolderPath($user, $documentType)
    {
        $folderNames = [
            'kartu_keluarga' => 'kartu-keluarga',
            'ijazah' => 'ijazah',
            'akta_kelahiran' => 'akta-kelahiran',
            'pas_foto' => 'pas-foto'
        ];

        $folderName = $folderNames[$documentType] ?? $documentType;

        // Gunakan nama user untuk folder
        $userName = $this->sanitizeFileName($user->name);
        $userId = $user->id;

        return "documents/{$userName}_{$userId}/{$folderName}";
    }

    private function sanitizeFileName($name)
    {
        // Hapus karakter khusus dan ganti spasi dengan underscore
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        // Hapus multiple underscores
        $sanitized = preg_replace('/_{2,}/', '_', $sanitized);
        // Hapus underscore di awal dan akhir
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

    private function deleteOldFile($registration, $documentType)
    {
        $column = $this->getDocumentColumn($documentType);
        $oldFilePath = $registration->$column;

        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }
    }

    public function delete($documentType)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Data registrasi tidak ditemukan.'
            ], 404);
        }

        // Validasi document type
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak valid.'
            ], 400);
        }

        try {
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
                'user_id' => $user->id,
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

        // Validasi document type
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

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
    }

    /**
     * Download document file
     */
    public function download($documentType)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Data registrasi tidak ditemukan.'
            ], 404);
        }

        // Validasi document type
        if (!in_array($documentType, ['kartu_keluarga', 'ijazah', 'akta_kelahiran', 'pas_foto'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak valid.'
            ], 400);
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }

        try {
            $downloadName = $this->getDownloadFileName($documentType, $user->name);
            return Storage::disk('public')->download($filePath, $downloadName);
        } catch (\Exception $e) {
            \Log::error('Download error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'document_type' => $documentType,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendownload file: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDownloadFileName($documentType, $userName)
    {
        $documentNames = [
            'kartu_keluarga' => 'Kartu-Keluarga',
            'ijazah' => 'Ijazah',
            'akta_kelahiran' => 'Akta-Kelahiran',
            'pas_foto' => 'Pas-Foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;
        $sanitizedName = $this->sanitizeFileName($userName);

        return "{$baseName}_{$sanitizedName}.pdf";
    }

    public function completeRegistration()
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Data registrasi tidak ditemukan.'
            ], 404);
        }

        if (!$registration->hasAllDocuments()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua dokumen harus diunggah sebelum menyelesaikan pendaftaran.'
            ], 400);
        }

        try {
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
                'user_id' => $user->id,
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
    }
    // Tambahkan di DocumentController.php

/**
 * Download all documents as ZIP
 */
public function downloadAll()
{
    $user = Auth::user();
    $registration = Registration::where('user_id', $user->id)->first();

    if (!$registration) {
        return response()->json([
            'success' => false,
            'message' => 'Data registrasi tidak ditemukan.'
        ], 404);
    }

    try {
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
                    $fileName = $documentNames[$type] . '_' . $user->name . '.pdf';
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
            'user_id' => $user->id,
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
    /**
     * Test Intervention Image functionality
     */
    public function testImage()
    {
        try {
            // Test Intervention Image v3
            $image = $this->imageManager->create(100, 100);
            $image->place('ff0000'); // Fill with red

            return response()->json([
                'success' => true,
                'message' => 'Intervention Image v3 is working correctly',
                'image_info' => [
                    'width' => $image->width(),
                    'height' => $image->height(),
                    'version' => 'v3.11'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Intervention Image v3 error: ' . $e->getMessage()
            ], 500);
        }
    }
}
