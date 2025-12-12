<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DocumentUploadTrait;
use App\Models\Registration;
use App\Models\Package;
use App\Models\Quota;
use App\Models\RegistrationDocument;
use App\Services\DocumentRequirementService;
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
    use DocumentUploadTrait;

    private $allowedMimes = ['pdf', 'jpeg', 'jpg', 'png'];
    private $maxFileSize = 5120;
    private $imageManager;

    public function __construct(DocumentRequirementService $documentRequirementService)
    {
        $this->imageManager = new ImageManager(new Driver());
        $this->documentRequirementService = $documentRequirementService;
    }

    public function index()
    {
        $user = Auth::user();

        $registration = Registration::with(['package', 'documents'])
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return redirect()->route('santri.biodata.index')
                ->with('error', 'Silakan isi biodata terlebih dahulu sebelum mengunggah dokumen.');
        }

        if ($registration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengunggah atau mengedit dokumen karena status pendaftaran sudah DITERIMA.');
        }

        if ($registration->package_id && !$registration->relationLoaded('package')) {
            $package = Package::find($registration->package_id);
            $registration->setRelation('package', $package);
        }

        $totalBiaya = $registration->total_biaya;

        // Get required documents based on package and program (normalized + labeled)
        $documentDefinitions = $this->documentRequirementService->getDocumentDefinitions($registration);
        $requiredDocuments = array_keys($documentDefinitions);
        $uploadedDocuments = $this->documentRequirementService->getUploadedDocuments($registration);
        $missingDocuments = $this->documentRequirementService->getMissingDocuments($registration);
        $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
        $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);

        // Get document labels for all required documents
        $documentLabels = array_map(fn ($definition) => $definition['label'], $documentDefinitions);

        // Get labels for missing documents
        $missingDocumentLabels = [];
        foreach ($missingDocuments as $docType) {
            $missingDocumentLabels[$docType] = $documentDefinitions[$docType]['label']
                ?? $this->documentRequirementService->getDocumentLabel($docType);
        }

        return view('dashboard.calon_santri.dokumen.dokumen-new', compact(
            'registration',
            'totalBiaya',
            'requiredDocuments',
            'uploadedDocuments',
            'missingDocuments',
            'uploadedCount',
            'requiredCount',
            'documentLabels',
            'missingDocumentLabels',
            'documentDefinitions'
        ));
    }

    public function upload(Request $request, $documentType)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi.'
                ], 401);
            }

            $registration = Registration::with('package')->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan isi biodata terlebih dahulu.'
                ], 400);
            }

            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengunggah atau mengedit dokumen karena status pendaftaran sudah DITERIMA.'
                ], 403);
            }

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

            // Delete old file if exists
            $oldDocument = RegistrationDocument::byRegistration($registration->id_pendaftaran)
                ->byDocumentType($documentType)
                ->first();
            
            if ($oldDocument) {
                $oldDocument->deleteFile();
            }

            $filePath = $this->uploadFile($file, $documentType, $user, $registration);

            // Save to registration_documents table
            $this->documentRequirementService->saveDocument($registration->id_pendaftaran, $documentType, $filePath);

            $allComplete = $this->documentRequirementService->areAllDocumentsComplete($registration);
            $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
            $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);

            if ($allComplete) {
                $registration->markAsPending();
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah.',
                'file_path' => $filePath,
                'file_name' => basename($filePath),
                'document_type' => $documentType,
                'document_label' => $this->getDocumentLabel($documentType),
                'all_documents_complete' => $allComplete,
                'uploaded_count' => $uploadedCount,
                'required_count' => $requiredCount,
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
     * Check if all documents are complete via API
     */
    public function checkAllDocumentsCompleteApi()
    {
        try {
            $user = Auth::user();
            $registration = Registration::with(['package'])->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.',
                    'all_complete' => false
                ], 404);
            }

            $allComplete = $this->documentRequirementService->areAllDocumentsComplete($registration);
            $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
            $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);
            $missingDocuments = $this->documentRequirementService->getMissingDocuments($registration);

            return response()->json([
                'success' => true,
                'all_complete' => $allComplete,
                'uploaded_count' => $uploadedCount,
                'required_count' => $requiredCount,
                'missing_documents' => array_map(function($doc) {
                    return [
                        'type' => $doc,
                        'label' => $this->getDocumentLabel($doc)
                    ];
                }, $missingDocuments),
                'message' => $allComplete ?
                    'Semua dokumen telah lengkap!' :
                    "Masih ada " . count($missingDocuments) . " dokumen yang belum diunggah."
            ]);

        } catch (\Exception $e) {
            \Log::error('Check all documents complete error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa kelengkapan dokumen: ' . $e->getMessage(),
                'all_complete' => false
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

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath, 0755, true);
        }

        $fullPath = $folderPath . '/' . $fileName;

        if (in_array($originalExtension, ['jpeg', 'jpg', 'png'])) {
            try {
                $image = $this->imageManager->read($file->getRealPath());
                $image->scaleDown(1200);

                if ($originalExtension === 'png') {
                    $encodedImage = $image->encode(new PngEncoder());
                } else {
                    $encodedImage = $image->encode(new JpegEncoder(85));
                }

                Storage::disk('public')->put($fullPath, $encodedImage->toString());
                return $fullPath;

            } catch (\Exception $e) {
                \Log::warning('Image processing failed, using direct upload: ' . $e->getMessage());
                $file->storeAs($folderPath, $fileName, 'public');
                return $fullPath;
            }
        } else {
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
                Storage::disk('public')->delete($oldFilePath);
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

        if (strpos($directory, 'documents/') === 0) {
            try {
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
            $registration = Registration::with(['package'])->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus dokumen karena status pendaftaran sudah DITERIMA.'
                ], 403);
            }

            $this->documentRequirementService->deleteDocument($registration->id_pendaftaran, $documentType);

            $allComplete = $this->documentRequirementService->areAllDocumentsComplete($registration);
            $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
            $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.',
                'document_type' => $documentType,
                'document_label' => $this->getDocumentLabel($documentType),
                'all_documents_complete' => $allComplete,
                'uploaded_count' => $uploadedCount,
                'required_count' => $requiredCount
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

    /**
     * Check quota availability for delete all documents button
     */
    public function checkQuotaForDeleteAll()
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.',
                    'show_delete_all' => false
                ], 404);
            }

            $activeQuota = Quota::where('is_active', true)->first();

            if (!$activeQuota) {
                return response()->json([
                    'success' => true,
                    'show_delete_all' => true,
                    'quota_available' => true
                ]);
            }

            $usedQuota = Registration::where('status_pendaftaran', 'diterima')
                ->orWhere('status_pendaftaran', 'menunggu_diverifikasi')
                ->count();

            $availableQuota = $activeQuota->quota - $usedQuota;

            $showDeleteAll = $availableQuota <= 0;

            return response()->json([
                'success' => true,
                'show_delete_all' => $showDeleteAll,
                'quota_available' => $availableQuota > 0,
                'available_quota' => $availableQuota,
                'message' => $showDeleteAll ?
                    'Kuota telah penuh. Anda dapat menghapus semua dokumen.' :
                    'Masih tersedia kuota. Tidak dapat menghapus semua dokumen.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Check quota error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa kuota: ' . $e->getMessage(),
                'show_delete_all' => false
            ], 500);
        }
    }

    /**
     * Delete all documents
     */
    public function deleteAllDocuments()
    {
        try {
            $user = Auth::user();
            $registration = Registration::with('documents')->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus dokumen karena status pendaftaran sudah DITERIMA.'
                ], 403);
            }

            $deletedDocuments = [];
            foreach ($registration->documents as $document) {
                $document->deleteFile();
                $document->delete();
                $deletedDocuments[] = $document->tipe_dokumen;
            }

            $this->cleanupAllEmptyFolders($registration);

            \Log::info('All documents deleted', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'deleted_documents' => $deletedDocuments
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Semua dokumen berhasil dihapus.',
                'deleted_count' => count($deletedDocuments),
                'deleted_documents' => $deletedDocuments,
                'all_documents_complete' => false,
                'uploaded_count' => 0,
                'refresh_dashboard' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Delete all documents error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up all empty folders for registration
     */
    private function cleanupAllEmptyFolders($registration)
    {
        try {
            $user = Auth::user();
            $folderPath = $this->getEfficientFolderPath($user, $registration);

            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);

                \Log::info('Document folder deleted', [
                    'folder_path' => $folderPath,
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Error cleaning up document folders: ' . $e->getMessage());
        }
    }

    public function getFile($documentType)
    {
        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                abort(404, 'Data registrasi tidak ditemukan.');
            }

            $document = RegistrationDocument::byRegistration($registration->id_pendaftaran)
                ->byDocumentType($documentType)
                ->first();

            if (!$document || !Storage::disk('public')->exists($document->file_path)) {
                abort(404, 'File tidak ditemukan.');
            }

            $file = Storage::disk('public')->get($document->file_path);
            $mimeType = Storage::disk('public')->mimeType($document->file_path);
            $fileName = basename($document->file_path);

            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            \Log::error('Get file error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'document_type' => $documentType,
                'exception' => $e
            ]);
            abort(500, 'Gagal mengambil file: ' . $e->getMessage());
        }
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

            $document = RegistrationDocument::byRegistration($registration->id_pendaftaran)
                ->byDocumentType($documentType)
                ->first();

            if (!$document || !Storage::disk('public')->exists($document->file_path)) {
                return back()->with('error', 'File tidak ditemukan. Silakan upload file terlebih dahulu.');
            }

            $downloadName = $this->getDownloadFileName($documentType, $user->name, $document->file_path);
            return Storage::disk('public')->download($document->file_path, $downloadName);

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
            $registration = Registration::with(['package', 'documents'])->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            if ($registration->status_pendaftaran === 'diterima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menyelesaikan pendaftaran karena status sudah DITERIMA.'
                ], 403);
            }

            if (!$this->documentRequirementService->areAllDocumentsComplete($registration)) {
                $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
                $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);
                return response()->json([
                    'success' => false,
                    'message' => "Semua dokumen harus diunggah sebelum menyelesaikan pendaftaran. ({$uploadedCount}/{$requiredCount} dokumen)"
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
            $registration = Registration::with(['package'])->where('user_id', $user->id)->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
            $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);
            $allComplete = $this->documentRequirementService->areAllDocumentsComplete($registration);
            $percentage = $requiredCount > 0 ? ($uploadedCount / $requiredCount) * 100 : 0;

            return response()->json([
                'success' => true,
                'progress' => [
                    'uploaded_count' => $uploadedCount,
                    'required_count' => $requiredCount,
                    'percentage' => $percentage,
                    'all_complete' => $allComplete
                ]
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
            $registration = Registration::with('documents')
                ->where('user_id', $user->id)
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan.'
                ], 404);
            }

            if (!$this->checkAllDocumentsComplete($registration)) {
                $uploadedCount = $this->documentRequirementService->getUploadedDocumentsCount($registration);
                $requiredCount = $this->documentRequirementService->getRequiredDocumentsCount($registration);
                return response()->json([
                    'success' => false,
                    'message' => "Semua dokumen harus diunggah sebelum dapat didownload. ({$uploadedCount}/{$requiredCount} dokumen)"
                ], 400);
            }

            $zipFileName = "Dokumen_Pendaftaran_{$user->name}_" . now()->format('Ymd_His') . ".zip";
            $zipPath = storage_path("app/temp/{$zipFileName}");

            if (!file_exists(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $documentNames = [
                    'kartu_keluarga' => 'Kartu-Keluarga',
                    'ijazah' => 'Ijazah',
                    'akta_kelahiran' => 'Akta-Kelahiran',
                    'pas_foto' => 'Pas-Foto',
                    'sku' => 'SKU',
                    'sertifikat_hafiz' => 'Sertifikat-Hafiz',
                    'surat_rekomendasi' => 'Surat-Rekomendasi',
                    'dokumen_kesehatan' => 'Dokumen-Kesehatan',
                ];

                foreach ($registration->documents as $document) {
                    if (Storage::disk('public')->exists($document->file_path)) {
                        $fileContent = Storage::disk('public')->get($document->file_path);
                        $docName = $documentNames[$document->tipe_dokumen] ?? $document->tipe_dokumen;
                        $fileName = $docName . '_' . $user->name . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $zip->addFromString($fileName, $fileContent);
                    }
                }

                $zip->close();

                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }

            throw new \Exception('Gagal membuat file ZIP.');
        } catch (\Exception $e) {
            \Log::error('Download all documents error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);

            if (isset($zipPath) && file_exists($zipPath)) {
                unlink($zipPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendownload semua dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }
}


