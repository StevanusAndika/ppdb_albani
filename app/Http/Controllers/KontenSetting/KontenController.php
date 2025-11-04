<?php

namespace App\Http\Controllers\KontenSetting;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KontenController extends Controller
{
    public function index()
    {
        $settings = ContentSetting::getSettings();
        return view('dashboard.admin.konten.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',

            'visi_judul' => 'nullable|string|max:255',
            'visi_deskripsi' => 'nullable|string',
            'misi_judul' => 'nullable|string|max:255',
            'misi_deskripsi' => 'nullable|string',

            // Program Unggulan - array validation
            'program_unggulan' => 'nullable|array',
            'program_unggulan.*.nama' => 'nullable|string|max:255',
            'program_unggulan.*.target' => 'nullable|string',
            'program_unggulan.*.metode' => 'nullable|string',
            'program_unggulan.*.evaluasi' => 'nullable|string',

            'alur_pendaftaran_judul' => 'nullable|string|max:255',
            'alur_pendaftaran_deskripsi' => 'nullable|string',

            'persyaratan_dokumen_judul' => 'nullable|string|max:255',
            'persyaratan_dokumen_deskripsi' => 'nullable|string',

            'akte_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'formulir_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ijazah_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kk_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pasfoto_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $settings = ContentSetting::getSettings();
            $data = $request->only([
                'judul', 'deskripsi', 'tagline',
                'visi_judul', 'visi_deskripsi', 'misi_judul', 'misi_deskripsi',
                'alur_pendaftaran_judul', 'alur_pendaftaran_deskripsi',
                'persyaratan_dokumen_judul', 'persyaratan_dokumen_deskripsi',
            ]);

            // Handle program unggulan data
            if ($request->has('program_unggulan')) {
                $programs = [];
                foreach ($request->program_unggulan as $program) {
                    if (!empty($program['nama']) || !empty($program['target']) || !empty($program['metode']) || !empty($program['evaluasi'])) {
                        $programs[] = [
                            'nama' => $program['nama'] ?? '',
                            'target' => $program['target'] ?? '',
                            'metode' => $program['metode'] ?? '',
                            'evaluasi' => $program['evaluasi'] ?? '',
                        ];
                    }
                }
                $data['program_unggulan_data'] = $programs;
            }

            // Handle file uploads
            $fileFields = ['akte', 'formulir', 'ijazah', 'kk', 'pasfoto'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field . '_file')) {
                    $file = $request->file($field . '_file');
                    $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('image/uploads', $filename, 'public');

                    // Delete old file if exists
                    if ($settings->{$field . '_path'}) {
                        Storage::disk('public')->delete($settings->{$field . '_path'});
                    }

                    $data[$field . '_path'] = $path;
                }
            }

            $settings->update($data);
            DB::commit();

            return redirect()->route('admin.content.index')
                ->with('success', 'Pengaturan konten berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteFile($fileType)
    {
        $allowedTypes = ['akte', 'formulir', 'ijazah', 'kk', 'pasfoto'];

        if (!in_array($fileType, $allowedTypes)) {
            return redirect()->back()->with('error', 'Jenis file tidak valid.');
        }

        try {
            $settings = ContentSetting::getSettings();
            $filePath = $settings->{$fileType . '_path'};

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $settings->update([$fileType . '_path' => null]);

            return redirect()->back()->with('success', 'File berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    /**
     * Add new program unggulan via AJAX
     */
    public function addProgram(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'target' => 'nullable|string',
            'metode' => 'nullable|string',
            'evaluasi' => 'nullable|string',
        ]);

        try {
            $settings = ContentSetting::getSettings();
            $settings->addProgramUnggulan($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Program unggulan berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete program unggulan via AJAX
     */
    public function deleteProgram($index)
    {
        try {
            $settings = ContentSetting::getSettings();
            $success = $settings->deleteProgramUnggulan((int)$index);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program unggulan berhasil dihapus.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Program tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus program: ' . $e->getMessage()
            ], 500);
        }
    }
}
