<?php

namespace App\Http\Controllers\Biodata;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Price;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BiodataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registration = Registration::with('package.activePrices')
            ->where('user_id', $user->id)
            ->first();
        $packages = Package::with('activePrices')->where('is_active', true)->get();

        return view('dashboard.calon_santri.biodata.biodata', compact('registration', 'packages'));
    }

    public function getPackagePrices($packageId)
    {
        try {
            $prices = Price::where('package_id', $packageId)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

            $total = $prices->sum('amount');

            return response()->json([
                'success' => true,
                'prices' => $prices,
                'total' => $total,
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data harga: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $existingRegistration = Registration::where('user_id', $user->id)->first();

        $validated = $this->validateRequest($request, $existingRegistration);

        DB::beginTransaction();
        try {
            if ($existingRegistration) {
                $existingRegistration->update(array_merge($validated, [
                    'status_pendaftaran' => 'telah_mengisi'
                ]));
                $registration = $existingRegistration;
                $message = 'Biodata berhasil diperbarui';
            } else {
                $registration = Registration::create(array_merge($validated, [
                    'user_id' => $user->id,
                    'status_pendaftaran' => 'telah_mengisi'
                ]));
                $message = 'Biodata berhasil disimpan';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateRequest(Request $request, $existingRegistration = null)
    {
        $rules = [
            'package_id' => 'required|exists:packages,id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => ['required', 'digits:16', Rule::unique('registrations')->ignore($existingRegistration?->id)],
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:-5 years',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat_tinggal' => 'required|string|max:500',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kode_pos' => 'required|digits:5',
            'nis_nisn_nsp' => 'nullable|string|max:50',
            'nama_ibu_kandung' => 'required|string|max:255',
            'nama_ayah_kandung' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'alergi_obat' => 'nullable|string|max:255',
            'penghasilan_ayah' => 'nullable|numeric|min:0',
            'penghasilan_ibu' => 'nullable|numeric|min:0',
            'nomor_telpon_orang_tua' => 'required|string|max:15',
            'agama' => 'required|in:islam,kristen,katolik,hindu,buddha,konghucu',
            'status_orang_tua' => 'required|in:lengkap,cerai_hidup,cerai_mati',
            'status_pernikahan' => 'required|in:menikah,belum_menikah',
            'jenjang_pendidikan_terakhir' => 'required|string|max:255',
            'nama_sekolah_terakhir' => 'required|string|max:255',
            'alamat_sekolah_terakhir' => 'required|string|max:500',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'kebangsaan' => 'required|in:WNI,WNA',
            'penyakit_kronis' => 'nullable|string|max:500',
            'nama_wali' => 'required|string|max:255',
            'alamat_wali' => 'required|string|max:500',
            'rt_wali' => 'required|string|max:3',
            'rw_wali' => 'required|string|max:3',
            'kecamatan_wali' => 'required|string|max:255',
            'kelurahan_wali' => 'required|string|max:255',
            'kota_wali' => 'required|string|max:255',
            'kode_pos_wali' => 'required|digits:5',
            'nomor_telpon_wali' => 'required|string|max:15',
        ];

        return $request->validate($rules);
    }
}
