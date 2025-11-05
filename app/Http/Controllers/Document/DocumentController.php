<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    private $allowedMimes = ['pdf', 'jpeg', 'jpg', 'png'];
    private $maxFileSize = 5120; // 5MB in KB

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

        $validated = $request->validate([
            'file' => 'required|file|mimes:' . implode(',', $this->allowedMimes) . '|max:' . $this->maxFileSize
        ]);

        try {
            $file = $request->file('file');
            $fileName = $this->generateFileName($file, $documentType);
            $folderPath = $this->getFolderPath($user->name, $documentType);

            // Hapus file lama jika ada
            $this->deleteOldFile($registration, $documentType);

            // Simpan file baru
            $filePath = $file->storeAs($folderPath, $fileName, 'public');

            // Update path di database
            $registration->update([$this->getDocumentColumn($documentType) => $filePath]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah.',
                'file_path' => $filePath,
                'file_name' => $fileName
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateFileName($file, $documentType)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');
        $randomString = Str::random(8);

        $documentNames = [
            'kartu_keluarga' => 'kartu-keluaga',
            'ijazah' => 'ijazah',
            'akta_kelahiran' => 'akta-kelahiran',
            'pas_foto' => 'pas-foto'
        ];

        $baseName = $documentNames[$documentType] ?? $documentType;

        return "{$baseName}_{$timestamp}_{$randomString}.{$extension}";
    }

    private function getFolderPath($username, $documentType)
    {
        $folderNames = [
            'kartu_keluarga' => 'Kartu Keluarga',
            'ijazah' => 'Ijazah',
            'akta_kelahiran' => 'Akta Kelahiran',
            'pas_foto' => 'Pas Foto'
        ];

        $folderName = $folderNames[$documentType] ?? ucfirst($documentType);
        $cleanUsername = preg_replace('/[^a-zA-Z0-9]/', '_', $username);

        return "Uploads/{$cleanUsername}/{$folderName}";
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

        if ($oldFilePath && Storage::disk('public/Documents')->exists($oldFilePath)) {
            Storage::disk('public/Documents')->delete($oldFilePath);
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

        try {
            $this->deleteOldFile($registration, $documentType);

            $column = $this->getDocumentColumn($documentType);
            $registration->update([$column => null]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
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
            abort(404);
        }

        $column = $this->getDocumentColumn($documentType);
        $filePath = $registration->$column;

        if (!$filePath || !Storage::disk('public/Documents')->exists($filePath)) {
            abort(404);
        }

        return response()->file(Storage::disk('public/Documents')->path($filePath));
    }
}
