<?php

namespace App\Http\Controllers\Biodata;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Price;
use App\Models\Registration;
use App\Models\ContentSetting;
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

        // Cek jika status pendaftaran = diterima
        if ($registration && $registration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengisi atau mengedit biodata karena status pendaftaran sudah DITERIMA.');
        }

        // Ambil packages dengan total amount
        $packages = Package::with('activePrices')
            ->active()
            ->get()
            ->map(function($package) {
                // Jika total_amount ada di database, gunakan itu
                // Jika tidak, hitung dari activePrices
                if ($package->total_amount && $package->total_amount > 0) {
                    $package->totalAmount = $package->total_amount;
                } else {
                    $package->totalAmount = $package->activePrices->sum('amount');
                }
                return $package;
            });

        // Ambil data program unggulan dari ContentSetting
        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? $contentSettings->program_unggulan : [];

        // Data jenjang pendidikan untuk dropdown (hanya TK, SD, SMP, SMA)
        $jenjangPendidikan = [
            'TK/RA',
            'SD/MI',
            'SMP/MTs',
            'SMA/MA'
        ];

        return view('dashboard.calon_santri.biodata.biodata', compact(
            'registration',
            'packages',
            'programUnggulan',
            'jenjangPendidikan'
        ));
    }

    public function getPackagePrices($packageId)
    {
        try {
            // Ambil package dengan prices yang aktif dan terurut
            $package = Package::with(['activePrices' => function($query) {
                $query->orderBy('order');
            }])->findOrFail($packageId);

            $prices = $package->activePrices;

            // Gunakan total_amount dari database jika ada, jika tidak hitung dari prices
            if ($package->total_amount && $package->total_amount > 0) {
                $total = $package->total_amount;
            } else {
                $total = $prices->sum('amount');
            }

            // Format each price individually dengan item_name yang benar
            $formattedPrices = $prices->map(function($price) {
                return [
                    'id' => $price->id,
                    'item_name' => $price->item_name,
                    'name' => $price->item_name,
                    'description' => $price->description,
                    'amount' => $price->amount,
                    'formatted_amount' => 'Rp ' . number_format($price->amount, 0, ',', '.'),
                    'order' => $price->order,
                    'is_active' => $price->is_active
                ];
            });

            return response()->json([
                'success' => true,
                'package_name' => $package->name,
                'prices' => $formattedPrices,
                'total' => $total,
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
                'package_total_amount' => $package->total_amount // Untuk debugging
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching package prices: ' . $e->getMessage());

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

        // Cek jika status pendaftaran = diterima
        if ($existingRegistration && $existingRegistration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengisi atau mengedit biodata karena status pendaftaran sudah DITERIMA.');
        }

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

            // Generate barcode setelah registrasi berhasil
            if (!$registration->hasQrCode()) {
                $registration->generateQrCode();
            }

            DB::commit();

            return redirect()->route('santri.dashboard')
                ->with('success', $message)
                ->with('barcode_generated', true);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function validateRequest(Request $request, $existingRegistration = null)
    {
        $rules = [
            'package_id' => 'required|exists:packages,id',
            'program_unggulan_id' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => ['required', 'digits:16', Rule::unique('registrations')->ignore($existingRegistration?->id)],
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:-5 years',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat_tinggal' => 'required|string|max:500',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
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
            'jenjang_pendidikan_terakhir' => 'required|in:TK/RA,SD/MI,SMP/MTs,SMA/MA',
            'nama_sekolah_terakhir' => 'required|string|max:255',
            'alamat_sekolah_terakhir' => 'required|string|max:500',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'kebangsaan' => 'required|in:WNI,WNA',
            'penyakit_kronis' => 'nullable|string|max:500',
            'nama_wali' => 'required|string|max:255',
            'alamat_wali' => 'required|string|max:500',
            'rt_wali' => 'nullable|string|max:3',
            'rw_wali' => 'nullable|string|max:3',
            'kecamatan_wali' => 'required|string|max:255',
            'kelurahan_wali' => 'required|string|max:255',
            'kota_wali' => 'required|string|max:255',
            'kode_pos_wali' => 'required|digits:5',
            'nomor_telpon_wali' => 'required|string|max:15',
        ];

        return $request->validate($rules);
    }

    /**
     * Show biodata details
     */
    public function show()
    {
        $user = Auth::user();
        $registration = Registration::with(['package', 'user'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Ambil data program unggulan untuk menampilkan detail
        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? $contentSettings->program_unggulan : [];

        return view('dashboard.calon_santri.biodata.show', compact(
            'registration',
            'programUnggulan'
        ));
    }

    /**
     * Edit biodata form
     */
    public function edit()
    {
        $user = Auth::user();
        $registration = Registration::with('package.activePrices')
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Cek jika status pendaftaran = diterima
        if ($registration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengisi atau mengedit biodata karena status pendaftaran sudah DITERIMA.');
        }

        $packages = Package::with('activePrices')
            ->active()
            ->get()
            ->map(function($package) {
                if ($package->total_amount && $package->total_amount > 0) {
                    $package->totalAmount = $package->total_amount;
                } else {
                    $package->totalAmount = $package->activePrices->sum('amount');
                }
                return $package;
            });

        // Ambil data program unggulan dari ContentSetting
        $contentSettings = ContentSetting::first();
        $programUnggulan = $contentSettings ? $contentSettings->program_unggulan : [];

        // Data jenjang pendidikan untuk dropdown (hanya TK, SD, SMP, SMA)
        $jenjangPendidikan = [
            'TK/RA',
            'SD/MI',
            'SMP/MTs',
            'SMA/MA'
        ];

        return view('dashboard.calon_santri.biodata.edit', compact(
            'registration',
            'packages',
            'programUnggulan',
            'jenjangPendidikan'
        ));
    }

    /**
     * Update biodata
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->firstOrFail();

        // Cek jika status pendaftaran = diterima
        if ($registration->status_pendaftaran === 'diterima') {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Anda tidak dapat mengisi atau mengedit biodata karena status pendaftaran sudah DITERIMA.');
        }

        $validated = $this->validateRequest($request, $registration);

        DB::beginTransaction();
        try {
            $registration->update(array_merge($validated, [
                'status_pendaftaran' => 'telah_mengisi'
            ]));

            DB::commit();

            return redirect()->route('santri.biodata.show')
                ->with('success', 'Biodata berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
