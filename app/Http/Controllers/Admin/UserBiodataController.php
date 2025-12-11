<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserBiodataController extends Controller
{
    /**
     * Tampilkan halaman biodata user
     */
    public function show(User $user)
    {
        // Ambil data registrasi user terbaru
        $registration = $user->registrations()->latest()->first();

        return view('dashboard.admin.manage-user.biodata.show', [
            'user' => $user,
            'registration' => $registration,
        ]);
    }

    /**
     * Tampilkan halaman upload dokumen untuk user
     */
    public function editDocuments(User $user)
    {
        // Ambil data registrasi user
        $registration = $user->registrations()->latest()->first();

        if (!$registration) {
            abort(404, 'User tidak memiliki data registrasi');
        }

        $documentTypes = [
            'kartu_keluaga_path' => 'Kartu Keluarga',
            'ijazah_path' => 'Ijazah',
            'akta_kelahiran_path' => 'Akta Kelahiran',
            'pas_foto_path' => 'Pas Foto',
        ];

        return view('dashboard.admin.manage-user.biodata.upload-documents-new', [
            'user' => $user,
            'registration' => $registration,
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Upload dokumen untuk registration user
     */
    public function uploadDocument(Request $request, User $user)
    {
        try {
            $registration = $user->registrations()->latest()->first();
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi user tidak ditemukan',
                ], 404);
            }

            $validated = $request->validate([
                'document_type' => 'required|in:kartu_keluaga_path,ijazah_path,akta_kelahiran_path,pas_foto_path',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
            ], [
                'file.required' => 'File harus dipilih',
                'file.mimes' => 'File harus berupa PDF, JPG, atau PNG',
                'file.max' => 'Ukuran file tidak boleh lebih dari 10MB',
            ]);

            // Hapus file lama jika ada
            $oldPath = $registration->{$validated['document_type']};
            if ($oldPath && Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }

            // Simpan file baru
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            
            // Path: registrations/{registration_id}/{document_type}/{filename}
            $filePath = $file->storeAs(
                "registrations/{$registration->id}/{$validated['document_type']}",
                time() . '_' . $fileName,
                'public'
            );

            // Update registration dengan path file baru
            $registration->update([
                $validated['document_type'] => $filePath
            ]);

            // Hitung jumlah dokumen yang sudah diupload
            $uploadedCount = 0;
            if ($registration->kartu_keluaga_path) $uploadedCount++;
            if ($registration->ijazah_path) $uploadedCount++;
            if ($registration->akta_kelahiran_path) $uploadedCount++;
            if ($registration->pas_foto_path) $uploadedCount++;

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil di-upload',
                'file_name' => $fileName,
                'uploaded_count' => $uploadedCount,
                'all_documents_complete' => $uploadedCount === 4,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus dokumen dari registration
     */
    public function deleteDocument(User $user, $documentType)
    {
        try {
            $registration = $user->registrations()->latest()->first();
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi tidak ditemukan',
                ], 404);
            }

            $validTypes = ['kartu_keluaga_path', 'ijazah_path', 'akta_kelahiran_path', 'pas_foto_path'];
            if (!in_array($documentType, $validTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe dokumen tidak valid',
                ], 422);
            }

            // Hapus file dari storage
            $filePath = $registration->{$documentType};
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Update registration
            $registration->update([
                $documentType => null
            ]);

            // Hitung jumlah dokumen yang tersisa
            $uploadedCount = 0;
            if ($registration->kartu_keluaga_path) $uploadedCount++;
            if ($registration->ijazah_path) $uploadedCount++;
            if ($registration->akta_kelahiran_path) $uploadedCount++;
            if ($registration->pas_foto_path) $uploadedCount++;

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus',
                'uploaded_count' => $uploadedCount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download dokumen
     */
    public function downloadDocument(User $user, $documentType)
    {
        $registration = $user->registrations()->latest()->first();

        if (!$registration) {
            abort(404, 'Data registrasi tidak ditemukan');
        }

        $validTypes = ['kartu_keluaga_path', 'ijazah_path', 'akta_kelahiran_path', 'pas_foto_path'];
        if (!in_array($documentType, $validTypes)) {
            abort(422, 'Tipe dokumen tidak valid');
        }

        $filePath = $registration->{$documentType};

        if (!$filePath || !Storage::exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($filePath);
    }

    /**
     * Tampilkan form edit/buat data pendaftaran
     */
    public function editRegistration(User $user)
    {
        $registration = $user->registrations()->latest()->first();
        $packages = \App\Models\Package::where('is_active', true)->get();

        return view('dashboard.admin.manage-user.biodata.edit-registration', [
            'user' => $user,
            'registration' => $registration,
            'packages' => $packages,
        ]);
    }

    /**
     * Simpan/update data pendaftaran
     */
    public function saveRegistration(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|max:20|unique:registrations,nik,' . ($user->registrations()->latest()->first()?->id ?? 'NULL'),
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:laki-laki,perempuan',
                'agama' => 'required|string|max:255',
                'alamat_tinggal' => 'required|string',
                'rt' => 'required|string|max:10',
                'rw' => 'required|string|max:10',
                'kelurahan' => 'required|string|max:255',
                'kecamatan' => 'required|string|max:255',
                'kota' => 'required|string|max:255',
                'program_pendidikan' => 'required|in:tahfidz,umum,plus',
                'program_unggulan_id' => 'nullable|exists:packages,id',
                'nama_sekolah_terakhir' => 'required|string|max:255',
                'status_pendaftaran' => 'required|in:proses,diterima,ditolak',
                'package_id' => 'nullable|exists:packages,id',
            ]);

            $registration = $user->registrations()->latest()->first();

            if ($registration) {
                // Update existing registration
                $registration->update($validated);
                $message = 'Data pendaftaran user berhasil diperbarui';
            } else {
                // Create new registration
                $validated['user_id'] = $user->id;
                Registration::create($validated);
                $message = 'Data pendaftaran user berhasil dibuat';
            }

            return redirect()->route('admin.manage-users.biodata.show', $user)
                            ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->errors())
                            ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                            ->withInput();
        }
    }
}
