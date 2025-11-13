<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'diperbarui_setelah_ditolak' => 'boolean'
    ];

    protected $appends = [
        'status_label',
        'total_biaya',
        'formatted_total_biaya',
        'qr_code_url',
        'program_unggulan_name',
        'is_biodata_complete',
        'is_documents_complete',
        'has_successful_payment',
        'uploaded_documents_count',
        'needs_re_review'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->id_pendaftaran)) {
                $registration->id_pendaftaran = 'PSB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
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
                    'kartu_keluaga_path', 'ijazah_path', 'akta_kelahiran_path', 'pas_foto_path'
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

    public function hasAllDocuments()
    {
        return !empty($this->kartu_keluaga_path) &&
               !empty($this->ijazah_path) &&
               !empty($this->akta_kelahiran_path) &&
               !empty($this->pas_foto_path);
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
            'nama_ibu_kandung', 'nama_ayah_kandung', 'nomor_telpon_orang_tua', 'agama'
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

    public function programUnggulan()
    {
        return $this->belongsTo(ContentSetting::class, 'program_unggulan_id');
    }
}
