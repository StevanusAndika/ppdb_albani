<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\DocumentRequirementService;
use DNS2D;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pendaftaran',
        'user_id',
        'package_id',
        'nama_lengkap',
        'nik',
        'program_unggulan_id',
        'program_pendidikan', // TAMBAHKAN INI
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_tinggal',
        'rt',
        'rw',
        'kecamatan',
        'kelurahan',
        'kota',
        'kode_pos',
        'nis_nisn_nsp',
        'nama_ibu_kandung',
        'nama_ayah_kandung',
        'pekerjaan_ibu',
        'pekerjaan_ayah',
        'alergi_obat',
        'penghasilan_ayah',
        'penghasilan_ibu',
        'nomor_telpon_orang_tua',
        'agama',
        'status_orang_tua',
        'status_pernikahan',
        'jenjang_pendidikan_terakhir',
        'nama_sekolah_terakhir',
        'alamat_sekolah_terakhir',
        'golongan_darah',
        'kebangsaan',
        'penyakit_kronis',
        'nama_wali',
        'alamat_wali',
        'rt_wali',
        'rw_wali',
        'kecamatan_wali',
        'kelurahan_wali',
        'kota_wali',
        'kode_pos_wali',
        'nomor_telpon_wali',
        'kartu_keluaga_path',
        'ijazah_path',
        'akta_kelahiran_path',
        'pas_foto_path',
        'status_pendaftaran',
        'status_seleksi',
        'catatan_admin',
        'dilihat_pada',
        'ditolak_pada',
        'diperbarui_setelah_ditolak'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'penghasilan_ayah' => 'decimal:2',
        'penghasilan_ibu' => 'decimal:2',
        'dilihat_pada' => 'datetime',
        'ditolak_pada' => 'datetime',
        'diperbarui_setelah_ditolak' => 'boolean',
        'program_pendidikan' => 'string',
    ];

    protected $appends = [
        'status_label',
        'status_seleksi_label',
        'total_biaya',
        'formatted_total_biaya',
        'qr_code_url',
        'program_unggulan_name',
        'is_biodata_complete',
        'is_documents_complete',
        'has_successful_payment',
        'uploaded_documents_count',
        'needs_review',
        'usia', // TAMBAHKAN INI
        'program_pendidikan_label', // TAMBAHKAN INI
        'is_eligible_for_takhassus' // TAMBAHKAN INI
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->id_pendaftaran)) {
                $registration->id_pendaftaran = 'PSB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Set default status_seleksi jika tidak diisi
            if (empty($registration->status_seleksi)) {
                $registration->status_seleksi = 'belum_mengikuti_seleksi';
            }
        });

        static::created(function ($registration) {
            $registration->generateQrCode();
        });

        static::updating(function ($registration) {
            // Jika status sebelumnya ditolak dan sedang diupdate data penting, tandai sebagai diperbarui
            if ($registration->isDirty() && $registration->getOriginal('status_pendaftaran') === 'ditolak') {
                $importantFields = [
                    'nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                    'alamat_tinggal', 'kecamatan', 'kelurahan', 'kota', 'kode_pos',
                    'nama_ibu_kandung', 'nama_ayah_kandung', 'nomor_telpon_orang_tua', 'agama',
                    'kartu_keluaga_path', 'ijazah_path', 'akta_kelahiran_path', 'pas_foto_path',
                    'program_pendidikan' // TAMBAHKAN INI
                ];

                foreach ($importantFields as $field) {
                    if ($registration->isDirty($field)) {
                        $registration->diperbarui_setelah_ditolak = true;
                        break;
                    }
                }
            }
        });

        static::deleting(function ($registration) {
            $registration->deleteAllDocuments();
            $registration->deleteQrCode();
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasSuccessfulPayment()
    {
        return $this->payments()
            ->whereIn('status', ['success', 'lunas'])
            ->exists();
    }

    public function getHasSuccessfulPaymentAttribute()
    {
        return $this->hasSuccessfulPayment();
    }

    public function hasPendingPayment()
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'waiting_payment', 'processing'])
            ->exists();
    }

    public function getSuccessfulPaymentAttribute()
    {
        return $this->payments()
            ->whereIn('status', ['success', 'lunas'])
            ->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function programUnggulan()
    {
        return $this->belongsTo(ProgramUnggulan::class, 'program_unggulan_id');
    }

    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'belum_mendaftar' => 'Belum Mendaftar',
            'telah_mengisi' => 'Telah Mengisi',
            'telah_dilihat' => 'Telah Dilihat',
            'menunggu_diverifikasi' => 'Menunggu Diverifikasi',
            'ditolak' => 'Ditolak',
            'diterima' => 'Diterima',
            'perlu_review' => 'Perlu Review Ulang'
        ];

        return $statuses[$this->status_pendaftaran] ?? $this->status_pendaftaran;
    }

    /**
     * Get status seleksi label
     */
    public function getStatusSeleksiLabelAttribute()
    {
        $statuses = [
            'sudah_mengikuti_seleksi' => 'Sudah Mengikuti Seleksi',
            'belum_mengikuti_seleksi' => 'Belum Mengikuti Seleksi'
        ];

        return $statuses[$this->status_seleksi] ?? $this->status_seleksi;
    }

    public function getTotalBiayaAttribute()
    {
        if ($this->relationLoaded('package') && $this->package) {
            if (isset($this->package->total_amount) && $this->package->total_amount > 0) {
                return $this->package->total_amount;
            }

            if ($this->package->relationLoaded('prices')) {
                return $this->package->prices->where('is_active', true)->sum('amount');
            } else {
                return $this->package->prices()->active()->sum('amount');
            }
        }

        if ($this->package_id) {
            $package = Package::with(['prices' => function($query) {
                $query->active();
            }])->find($this->package_id);

            if ($package) {
                if (isset($package->total_amount) && $package->total_amount > 0) {
                    return $package->total_amount;
                }
                return $package->prices->sum('amount');
            }
        }

        return 0;
    }

    public function getFormattedTotalBiayaAttribute()
    {
        $total = $this->total_biaya;
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function markAsSeen()
    {
        $this->update([
            'status_pendaftaran' => 'telah_dilihat',
            'dilihat_pada' => now()
        ]);
    }

    public function markAsPending()
    {
        $this->update([
            'status_pendaftaran' => 'menunggu_diverifikasi',
            'diperbarui_setelah_ditolak' => false
        ]);
    }

    public function markAsRejected($catatan = null)
    {
        $this->update([
            'status_pendaftaran' => 'ditolak',
            'catatan_admin' => $catatan,
            'ditolak_pada' => now(),
            'diperbarui_setelah_ditolak' => false
        ]);
    }

    public function markAsApproved()
    {
        $this->update([
            'status_pendaftaran' => 'diterima',
            'diperbarui_setelah_ditolak' => false
        ]);
    }

    public function markAsNeedsReview()
    {
        $this->update([
            'status_pendaftaran' => 'perlu_review',
            'diperbarui_setelah_ditolak' => false
        ]);
    }

    /**
     * Update status seleksi - Sudah Mengikuti Seleksi
     */
    public function markAsSudahSeleksi()
    {
        $this->update([
            'status_seleksi' => 'sudah_mengikuti_seleksi'
        ]);
    }

    /**
     * Update status seleksi - Belum Mengikuti Seleksi
     */
    public function markAsBelumSeleksi()
    {
        $this->update([
            'status_seleksi' => 'belum_mengikuti_seleksi'
        ]);
    }

    /**
     * Update status seleksi dengan custom value
     */
    public function updateStatusSeleksi($status)
    {
        if (in_array($status, ['sudah_mengikuti_seleksi', 'belum_mengikuti_seleksi'])) {
            $this->update([
                'status_seleksi' => $status
            ]);
            return true;
        }
        return false;
    }

    /**
     * Check if all required documents are uploaded
     * Uses DocumentRequirementService to check based on package and program unggulan
     */
    public function hasAllDocuments()
    {
        $service = app(DocumentRequirementService::class);
        return $service->areAllDocumentsComplete($this);
    }

    public function getIsDocumentsCompleteAttribute()
    {
      return $this->uploaded_documents_count === 4;
    }

    /**
     * Hitung jumlah dokumen yang sudah diupload
     */
    public function getUploadedDocumentsCountAttribute()
    {
        $count = 0;
        if (!empty($this->kartu_keluaga_path)) $count++;
        if (!empty($this->ijazah_path)) $count++;
        if (!empty($this->akta_kelahiran_path)) $count++;
        if (!empty($this->pas_foto_path)) $count++;
        return $count;
    }

    /**
     * Cek apakah perlu review ulang (status ditolak tapi data diperbarui)
     */
    public function getNeedsReReviewAttribute()
    {
        return $this->status_pendaftaran === 'ditolak' && $this->diperbarui_setelah_ditolak;
    }

    /**
     * Cek kelengkapan biodata dengan field opsional
     */
    public function isBiodataComplete()
    {
        $requiredFields = [
            'nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'alamat_tinggal', 'kecamatan', 'kelurahan', 'kota', 'kode_pos',
            'nama_ibu_kandung', 'nama_ayah_kandung', 'nomor_telpon_orang_tua', 'agama',
            'program_pendidikan', // TAMBAHKAN INI
            'jenjang_pendidikan_terakhir' // TAMBAHKAN INI
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    public function getIsBiodataCompleteAttribute()
    {
        return $this->isBiodataComplete();
    }

    /**
     * Get program unggulan dari ContentSetting dengan perbaikan
     */
    public function getProgramUnggulanAttribute()
    {
        if (!$this->program_unggulan_id) {
            return null;
        }

        try {
            $contentSetting = ContentSetting::first();
            if (!$contentSetting) {
                return null;
            }

            $programs = $contentSetting->program_unggulan ?? [];

            if (is_string($programs)) {
                $programs = json_decode($programs, true) ?? [];
            }

            foreach ($programs as $program) {
                if (isset($program['id']) && $program['id'] == $this->program_unggulan_id) {
                    return $program;
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error getting program unggulan: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get program unggulan name dengan fallback
     */
    public function getProgramUnggulanNameAttribute()
    {
        try {
            $program = $this->program_unggulan;

            if ($program && isset($program['nama_program'])) {
                return $program['nama_program'];
            }

            if ($this->program_unggulan_id) {
                return " " . $this->program_unggulan_id;
            }

            return 'Belum memilih program';
        } catch (\Exception $e) {
            \Log::error('Error getting program name: ' . $e->getMessage());
            return 'Error loading program';
        }
    }

    /**
     * Get program unggulan description
     */
    public function getProgramUnggulanDescriptionAttribute()
    {
        $program = $this->program_unggulan;
        return $program['deskripsi'] ?? '';
    }

    /**
     * METODE BARU: VALIDASI PROGRAM PENDIDIKAN
     */

    /**
     * Hitung usia berdasarkan tanggal lahir
     */
    public function calculateAge()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }

        return now()->diffInYears($this->tanggal_lahir) * -1;
    }

    /**
     * Get usia saat ini
     */
    public function getUsiaAttribute()
    {
        return $this->calculateAge();
    }

    /**
     * Cek apakah usia minimal 17 tahun untuk program Takhassus Al-Quran
     */
    public function isEligibleForTakhassus()
    {
        if ($this->program_pendidikan !== 'Takhassus Al-Quran') {
            return true;
        }

        return $this->calculateAge() >= 17;
    }

    /**
     * Get status kelayakan untuk Takhassus Al-Quran
     */
    public function getIsEligibleForTakhassusAttribute()
    {
        return $this->isEligibleForTakhassus();
    }

    /**
     * Get label program pendidikan
     */
    public function getProgramPendidikanLabelAttribute()
    {
        $labels = [
            'MTS Bani Syahid' => 'MTS Bani Syahid',
            'MA Bani Syahid' => 'MA Bani Syahid',
            'Takhassus Al-Quran' => 'Takhassus Al-Quran'
        ];

        return $labels[$this->program_pendidikan] ?? $this->program_pendidikan;
    }

    /**
     * Get pesan validasi untuk program Takhassus Al-Quran
     */
    public function getTakhassusValidationMessageAttribute()
    {
        if ($this->program_pendidikan !== 'Takhassus Al-Quran') {
            return null;
        }

        $usia = $this->calculateAge();
        if ($usia < 17) {
            return "Usia calon santri atas nama {$this->nama_lengkap} belum memenuhi untuk program Pendidikan Takhassus Al-Quran. Usia saat ini: {$usia} tahun (minimal 17 tahun).";
        }

        return "Usia calon santri atas nama {$this->nama_lengkap} memenuhi syarat untuk program Takhassus Al-Quran. Usia saat ini: {$usia} tahun.";
    }

    /**
     * Validasi usia untuk Takhassus Al-Quran
     * @throws \Exception jika usia tidak memenuhi syarat
     */
    public function validateTakhassusAge()
    {
        if ($this->program_pendidikan === 'Takhassus Al-Quran') {
            $usia = $this->calculateAge();
            if ($usia < 17) {
                throw new \Exception("Usia calon santri atas nama {$this->nama_lengkap} belum memenuhi untuk program Pendidikan Takhassus Al-Quran. Usia saat ini: {$usia} tahun (minimal 17 tahun).");
            }
        }
        return true;
    }

    /**
     * Scope untuk mencari registrasi berdasarkan program pendidikan
     */
    public function scopeByProgramPendidikan($query, $program)
    {
        return $query->where('program_pendidikan', $program);
    }

    /**
     * Scope untuk mencari registrasi yang tidak memenuhi syarat Takhassus
     */
    public function scopeNotEligibleForTakhassus($query)
    {
        return $query->where('program_pendidikan', 'Takhassus Al-Quran')
                     ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 17');
    }

    /**
     * Scope untuk mencari registrasi yang memenuhi syarat Takhassus
     */
    public function scopeEligibleForTakhassus($query)
    {
        return $query->where('program_pendidikan', 'Takhassus Al-Quran')
                     ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 17');
    }

    /**
     * Get opsi program pendidikan untuk dropdown
     */
    public static function getProgramPendidikanOptions()
    {
        return [
            'MTS Bani Syahid' => 'MTS Bani Syahid',
            'MA Bani Syahid' => 'MA Bani Syahid',
            'Takhassus Al-Quran' => 'Takhassus Al-Quran'
        ];
    }

    /**
     * Scope untuk dokumen yang akan expired dalam 3-4 tahun
     */
    public function scopeExpiringDocuments($query)
    {
        $threeYearsAgo = now()->subYears(3);
        $fourYearsAgo = now()->subYears(4);

        return $query->where(function($q) use ($threeYearsAgo, $fourYearsAgo) {
            $q->where('created_at', '<=', $threeYearsAgo)
              ->where('created_at', '>=', $fourYearsAgo);
        })->where(function($q) {
            $q->whereNotNull('kartu_keluaga_path')
              ->orWhereNotNull('ijazah_path')
              ->orWhereNotNull('akta_kelahiran_path')
              ->orWhereNotNull('pas_foto_path');
        });
    }
    /**
 * Cek apakah perlu review (accessor untuk needs_review)
 */
    public function getNeedsReviewAttribute()
    {
        return $this->status_pendaftaran === 'perlu_review';
    }
    /**
     * Hapus semua dokumen yang terkait
     */
    public function deleteAllDocuments()
    {
        $documents = [
            'kartu_keluaga_path',
            'ijazah_path',
            'akta_kelahiran_path',
            'pas_foto_path'
        ];

        foreach ($documents as $document) {
            if ($this->$document && Storage::disk('public')->exists($this->$document)) {
                Storage::disk('public')->delete($this->$document);
                $this->$document = null;
            }
        }

        $this->save();
    }

    /**
     * Hapus dokumen tertentu
     */
    public function deleteDocument($documentType)
    {
        $documentFields = [
            'kartu_keluarga' => 'kartu_keluaga_path',
            'ijazah' => 'ijazah_path',
            'akta_kelahiran' => 'akta_kelahiran_path',
            'pas_foto' => 'pas_foto_path'
        ];

        if (isset($documentFields[$documentType])) {
            $field = $documentFields[$documentType];

            if ($this->$field && Storage::disk('public')->exists($this->$field)) {
                Storage::disk('public')->delete($this->$field);
                $this->$field = null;
                $this->save();
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah dokumen sudah expired (lebih dari 3 tahun)
     */
    public function isDocumentsExpired()
    {
        return $this->created_at->diffInYears(now()) >= 3;
    }

    /**
     * Get document upload progress
     */
    public function getUploadProgressAttribute()
    {
        $uploaded = 0;
        $total = 4;

        if (!empty($this->kartu_keluaga_path)) $uploaded++;
        if (!empty($this->ijazah_path)) $uploaded++;
        if (!empty($this->akta_kelahiran_path)) $uploaded++;
        if (!empty($this->pas_foto_path)) $uploaded++;

        return [
            'uploaded' => $uploaded,
            'total' => $total,
            'percentage' => ($uploaded / $total) * 100
        ];
    }

    /**
     * Generate QR Code untuk id_pendaftaran
     */
    public function generateQrCode()
    {
        try {
            if (!Storage::disk('public')->exists('qr-codes')) {
                Storage::disk('public')->makeDirectory('qr-codes');
            }

            $qrCodeUrl = route('barcode.show', $this->id_pendaftaran);

            $qrCode = DNS2D::getBarcodePNG(
                $qrCodeUrl,
                'QRCODE',
                8,
                8,
                array(0,0,0),
                true
            );

            $filename = 'qr-codes/' . $this->id_pendaftaran . '.png';
            Storage::disk('public')->put($filename, base64_decode($qrCode));

            return $filename;
        } catch (\Exception $e) {
            \Log::error('Error generating QR Code: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get QR Code URL
     */
    public function getQrCodeUrlAttribute()
    {
        $filename = 'qr-codes/' . $this->id_pendaftaran . '.png';

        if (Storage::disk('public')->exists($filename)) {
            return Storage::disk('public')->url($filename);
        }

        $generated = $this->generateQrCode();
        return $generated ? Storage::disk('public')->url($generated) : null;
    }

    /**
     * Get QR Code path for download
     */
    public function getQrCodePathAttribute()
    {
        $filename = 'qr-codes/' . $this->id_pendaftaran . '.png';
        return Storage::disk('public')->path($filename);
    }

    /**
     * Check if QR Code exists
     */
    public function hasQrCode()
    {
        $filename = 'qr-codes/' . $this->id_pendaftaran . '.png';
        return Storage::disk('public')->exists($filename);
    }

    /**
     * Delete QR Code file
     */
    public function deleteQrCode()
    {
        $filename = 'qr-codes/' . $this->id_pendaftaran . '.png';
        if (Storage::disk('public')->exists($filename)) {
            Storage::disk('public')->delete($filename);
        }
    }

    /**
     * Get QR Code information URL
     */
    public function getQrCodeInfoUrlAttribute()
    {
        return route('barcode.show', $this->id_pendaftaran);
    }

    /**
     * ALIAS METHOD untuk kompatibilitas
     */
    public function hasBarcode()
    {
        return $this->hasQrCode();
    }

    /**
     * Scope untuk registrasi dengan status seleksi tertentu
     */
    public function scopeStatusSeleksi($query, $status)
    {
        return $query->where('status_seleksi', $status);
    }

    /**
     * Scope untuk registrasi yang sudah mengikuti seleksi
     */
    public function scopeSudahSeleksi($query)
    {
        return $query->where('status_seleksi', 'sudah_mengikuti_seleksi');
    }

    /**
     * Scope untuk registrasi yang belum mengikuti seleksi
     */
    public function scopeBelumSeleksi($query)
    {
        return $query->where('status_seleksi', 'belum_mengikuti_seleksi');
    }

    /**
     * Cek apakah sudah mengikuti seleksi
     */
    public function isSudahSeleksi()
    {
        return $this->status_seleksi === 'sudah_mengikuti_seleksi';
    }

    /**
     * Cek apakah belum mengikuti seleksi
     */
    public function isBelumSeleksi()
    {
        return $this->status_seleksi === 'belum_mengikuti_seleksi';
    }

    /**
     * Get status seleksi options untuk dropdown
     */
    public static function getStatusSeleksiOptions()
    {
        return [
            'belum_mengikuti_seleksi' => 'Belum Mengikuti Seleksi',
            'sudah_mengikuti_seleksi' => 'Sudah Mengikuti Seleksi'
        ];
    }

    /**
     * Get status pendaftaran options untuk dropdown
     */
    public static function getStatusPendaftaranOptions()
    {
        return [
            'belum_mendaftar' => 'Belum Mendaftar',
            'telah_mengisi' => 'Telah Mengisi',
            'telah_dilihat' => 'Telah Dilihat',
            'menunggu_diverifikasi' => 'Menunggu Diverifikasi',
            'ditolak' => 'Ditolak',
            'diterima' => 'Diterima',
            'perlu_review' => 'Perlu Review Ulang'
        ];
    }

    /**
     * Get all document information
     */
    public function getDocumentInfo()
    {
        $documents = [
            'kartu_keluarga' => [
                'name' => 'Kartu Keluarga',
                'path' => $this->kartu_keluaga_path,
                'icon' => 'fas fa-id-card',
                'field' => 'kartu_keluaga_path',
                'exists' => !empty($this->kartu_keluaga_path) && Storage::disk('public')->exists($this->kartu_keluaga_path)
            ],
            'ijazah' => [
                'name' => 'Ijazah',
                'path' => $this->ijazah_path,
                'icon' => 'fas fa-graduation-cap',
                'field' => 'ijazah_path',
                'exists' => !empty($this->ijazah_path) && Storage::disk('public')->exists($this->ijazah_path)
            ],
            'akta_kelahiran' => [
                'name' => 'Akta Kelahiran',
                'path' => $this->akta_kelahiran_path,
                'icon' => 'fas fa-birthday-cake',
                'field' => 'akta_kelahiran_path',
                'exists' => !empty($this->akta_kelahiran_path) && Storage::disk('public')->exists($this->akta_kelahiran_path)
            ],
            'pas_foto' => [
                'name' => 'Pas Foto',
                'path' => $this->pas_foto_path,
                'icon' => 'fas fa-camera',
                'field' => 'pas_foto_path',
                'exists' => !empty($this->pas_foto_path) && Storage::disk('public')->exists($this->pas_foto_path)
            ]
        ];

        return $documents;
    }

    /**
     * Get registration statistics termasuk statistik program pendidikan
     */
    public static function getStatistics()
    {
        $total = self::count();
        $menunggu = self::where('status_pendaftaran', 'menunggu_diverifikasi')->count();
        $diterima = self::where('status_pendaftaran', 'diterima')->count();
        $ditolak = self::where('status_pendaftaran', 'ditolak')->count();
        $perlu_review = self::where('status_pendaftaran', 'perlu_review')->count();
        $sudah_seleksi = self::where('status_seleksi', 'sudah_mengikuti_seleksi')->count();
        $belum_seleksi = self::where('status_seleksi', 'belum_mengikuti_seleksi')->count();

        // Statistik program pendidikan
        $mts = self::where('program_pendidikan', 'MTS Bani Syahid')->count();
        $ma = self::where('program_pendidikan', 'MA Bani Syahid')->count();
        $takhassus = self::where('program_pendidikan', 'Takhassus Al-Quran')->count();

        // Statistik kelayakan Takhassus
        $takhassus_eligible = self::where('program_pendidikan', 'Takhassus Al-Quran')
            ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 17')
            ->count();
        $takhassus_not_eligible = self::where('program_pendidikan', 'Takhassus Al-Quran')
            ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 17')
            ->count();

        return [
            'total' => $total,
            'menunggu' => $menunggu,
            'diterima' => $diterima,
            'ditolak' => $ditolak,
            'perlu_review' => $perlu_review,
            'sudah_seleksi' => $sudah_seleksi,
            'belum_seleksi' => $belum_seleksi,
            'program_pendidikan' => [
                'mts' => $mts,
                'ma' => $ma,
                'takhassus' => $takhassus,
                'takhassus_eligible' => $takhassus_eligible,
                'takhassus_not_eligible' => $takhassus_not_eligible
            ]
        ];
    }

    /**
     * Get detail informasi program pendidikan
     */
    public function getProgramPendidikanInfoAttribute()
    {
        $info = [
            'MTS Bani Syahid' => [
                'nama' => 'MTS Bani Syahid',
                'deskripsi' => 'Madrasah Tsanawiyah untuk pendidikan menengah pertama',
                'usia_minimal' => 12,
                'usia_maksimal' => 15,
                'jenjang' => 'SMP/MTs'
            ],
            'MA Bani Syahid' => [
                'nama' => 'MA Bani Syahid',
                'deskripsi' => 'Madrasah Aliyah untuk pendidikan menengah atas',
                'usia_minimal' => 15,
                'usia_maksimal' => 18,
                'jenjang' => 'SMA/MA'
            ],
            'Takhassus Al-Quran' => [
                'nama' => 'Takhassus Al-Quran',
                'deskripsi' => 'Program khusus tahfizh dan pendalaman Al-Quran',
                'usia_minimal' => 17,
                'usia_maksimal' => null,
                'jenjang' => 'Khusus'
            ]
        ];

        return $info[$this->program_pendidikan] ?? null;
    }

    /**
     * Validasi kelayakan berdasarkan program pendidikan
     */
    public function validateProgramPendidikan()
    {
        if (!$this->program_pendidikan || !$this->tanggal_lahir) {
            return true; // Tidak validasi jika data tidak lengkap
        }

        $usia = $this->calculateAge();

        switch ($this->program_pendidikan) {
            case 'MTS Bani Syahid':
                if ($usia < 12 || $usia > 15) {
                    throw new \Exception("Usia {$usia} tahun tidak sesuai untuk program MTS Bani Syahid (12-15 tahun).");
                }
                break;

            case 'MA Bani Syahid':
                if ($usia < 15 || $usia > 18) {
                    throw new \Exception("Usia {$usia} tahun tidak sesuai untuk program MA Bani Syahid (15-18 tahun).");
                }
                break;

            case 'Takhassus Al-Quran':
                if ($usia < 17) {
                    throw new \Exception("Usia calon santri atas nama {$this->nama_lengkap} belum memenuhi untuk program Pendidikan Takhassus Al-Quran. Usia saat ini: {$usia} tahun (minimal 17 tahun).");
                }
                break;
        }

        return true;
    }
}
