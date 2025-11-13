<?php

namespace App\Http\Controllers\Quota;

use App\Http\Controllers\Controller;
use App\Models\Quota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotaController extends Controller
{
    public function index()
    {
        $quotas = Quota::orderBy('tahun_akademik', 'desc')->get();
        return view('dashboard.admin.quota.index', compact('quotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_akademik' => 'required|regex:/^\d{4}-\d{4}$/|unique:quotas,tahun_akademik',
            'kuota' => 'required|integer|min:1',
        ], [
            'tahun_akademik.regex' => 'Format tahun akademik harus: 2024-2025',
            'tahun_akademik.unique' => 'Tahun akademik sudah ada',
        ]);

        try {
            DB::beginTransaction();

            Quota::create($validated);

            DB::commit();

            return redirect()->route('admin.quota.index')
                ->with('success', 'Kuota berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Quota $quota)
    {
        $validated = $request->validate([
            'tahun_akademik' => 'required|regex:/^\d{4}-\d{4}$/|unique:quotas,tahun_akademik,' . $quota->id,
            'kuota' => 'required|integer|min:1',
        ], [
            'tahun_akademik.regex' => 'Format tahun akademik harus: 2024-2025',
            'tahun_akademik.unique' => 'Tahun akademik sudah ada',
        ]);

        // Validasi kuota tidak boleh kurang dari yang sudah terpakai
        if ($validated['kuota'] < $quota->terpakai) {
            return redirect()->back()
                ->with('error', 'Kuota tidak boleh kurang dari jumlah yang sudah terpakai (' . $quota->terpakai . ')')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $quota->update($validated);

            DB::commit();

            return redirect()->route('admin.quota.index')
                ->with('success', 'Kuota berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Quota $quota)
    {
        // Cek jika kuota sudah terpakai
        if ($quota->terpakai > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kuota yang sudah terpakai');
        }

        try {
            DB::beginTransaction();

            $quota->delete();

            DB::commit();

            return redirect()->route('admin.quota.index')
                ->with('success', 'Kuota berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function activate(Quota $quota)
    {
        try {
            DB::beginTransaction();

            // Nonaktifkan semua quota terlebih dahulu
            Quota::where('is_active', true)->update(['is_active' => false]);

            // Aktifkan quota yang dipilih
            $quota->update(['is_active' => true]);

            DB::commit();

            return redirect()->route('admin.quota.index')
                ->with('success', 'Kuota ' . $quota->tahun_akademik . ' berhasil diaktifkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset(Quota $quota)
    {
        try {
            DB::beginTransaction();

            $quota->update(['terpakai' => 0]);

            DB::commit();

            return redirect()->route('admin.quota.index')
                ->with('success', 'Kuota terpakai berhasil direset ke 0');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
