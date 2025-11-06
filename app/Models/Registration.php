<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pendaftaran',
        'user_id',
        'package_id',
        'nama_lengkap',
        'nik',
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
        'dilihat_pada'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'penghasilan_ayah' => 'decimal:2',
        'penghasilan_ibu' => 'decimal:2',
        'dilihat_pada' => 'datetime',
    ];

    protected $appends = ['status_label', 'total_biaya', 'formatted_total_biaya'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->id_pendaftaran)) {
                $registration->id_pendaftaran = 'PSB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });

        static::deleting(function ($registration) {
            $registration->deleteAllDocuments();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'belum_mendaftar' => 'Belum Mendaftar',
            'telah_mengisi' => 'Telah Mengisi',
            'telah_dilihat' => 'Telah Dilihat',
            'menunggu_diverifikasi' => 'Menunggu Diverifikasi',
            'ditolak' => 'Ditolak',
            'diterima' => 'Diterima'
        ];

        return $statuses[$this->status_pendaftaran] ?? $this->status_pendaftaran;
    }

    public function getTotalBiayaAttribute()
    {
        return $this->package->total_amount ?? 0;
    }

    public function getFormattedTotalBiayaAttribute()
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
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
        $this->update(['status_pendaftaran' => 'menunggu_diverifikasi']);
    }

    public function markAsRejected($catatan = null)
    {
        $this->update([
            'status_pendaftaran' => 'ditolak',
            'catatan_admin' => $catatan
        ]);
    }

    public function markAsApproved()
    {
        $this->update(['status_pendaftaran' => 'diterima']);
    }

    public function hasAllDocuments()
    {
        return !empty($this->kartu_keluaga_path) &&
               !empty($this->ijazah_path) &&
               !empty($this->akta_kelahiran_path) &&
               !empty($this->pas_foto_path);
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
}
