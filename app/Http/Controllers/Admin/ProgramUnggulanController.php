<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramUnggulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramUnggulanController extends Controller
{
    /**
     * Display a listing of the programs
     */
    public function index()
    {
        $programs = ProgramUnggulan::paginate(15);
        return view('admin.program-unggulan.index', compact('programs'));
    }

    /**
     * Show the form for creating a new program
     */
    public function create()
    {
        return view('admin.program-unggulan.create');
    }

    /**
     * Store a newly created program
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|unique:programs_unggulan|max:255',
            'potongan' => 'required|numeric|min:0|max:100',
            'perlu_verifikasi' => 'required|in:yes,no',
            'dokumen_tambahan' => 'nullable|json'
        ]);

        try {
            DB::beginTransaction();

            // Parse dokumen_tambahan if it's string
            if (isset($validated['dokumen_tambahan']) && is_string($validated['dokumen_tambahan'])) {
                $validated['dokumen_tambahan'] = json_decode($validated['dokumen_tambahan'], true);
            }

            ProgramUnggulan::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.program-unggulan.index')
                ->with('success', 'Program unggulan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating program unggulan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan program unggulan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified program
     */
    public function edit(ProgramUnggulan $program)
    {
        return view('admin.program-unggulan.edit', compact('program'));
    }

    /**
     * Update the specified program
     */
    public function update(Request $request, ProgramUnggulan $program)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:255|unique:programs_unggulan,nama_program,' . $program->id,
            'potongan' => 'required|numeric|min:0|max:100',
            'perlu_verifikasi' => 'required|in:yes,no',
            'dokumen_tambahan' => 'nullable|json'
        ]);

        try {
            DB::beginTransaction();

            // Parse dokumen_tambahan if it's string
            if (isset($validated['dokumen_tambahan']) && is_string($validated['dokumen_tambahan'])) {
                $validated['dokumen_tambahan'] = json_decode($validated['dokumen_tambahan'], true);
            }

            $program->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.program-unggulan.index')
                ->with('success', 'Program unggulan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating program unggulan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui program unggulan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified program
     */
    public function destroy(ProgramUnggulan $program)
    {
        try {
            DB::beginTransaction();

            // Check if program is being used in registrations
            $registrationCount = \DB::table('registrations')
                ->where('program_unggulan_id', $program->id)
                ->count();

            if ($registrationCount > 0) {
                return redirect()
                    ->route('admin.program-unggulan.index')
                    ->with('error', "Tidak dapat menghapus program karena masih digunakan oleh {$registrationCount} pendaftaran");
            }

            $program->delete();

            DB::commit();

            return redirect()
                ->route('admin.program-unggulan.index')
                ->with('success', 'Program unggulan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting program unggulan: ' . $e->getMessage());

            return redirect()
                ->route('admin.program-unggulan.index')
                ->with('error', 'Gagal menghapus program unggulan: ' . $e->getMessage());
        }
    }

    /**
     * Get programs as JSON (for AJAX)
     */
    public function getJson()
    {
        try {
            $programs = ProgramUnggulan::all();

            return response()->json([
                'success' => true,
                'programs' => $programs->map(function($program) {
                    return [
                        'id' => $program->id,
                        'nama_program' => $program->nama_program,
                        'potongan' => $program->potongan,
                        'perlu_verifikasi' => $program->perlu_verifikasi,
                        'dokumen_tambahan' => $program->dokumen_tambahan ?? []
                    ];
                })
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching programs: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data program: ' . $e->getMessage()
            ], 500);
        }
    }
}
