<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    const JENIS_FOTO = 'foto';
    const JENIS_KTP = 'ktp';
    const JENIS_KK = 'kk';
    const JENIS_AKTA_KELAHIRAN = 'akta_kelahiran';
    const JENIS_RAPOR = 'rapor';
    const JENIS_LAINNYA = 'lainnya';

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'registration_id',
        'jenis_dokumen',
        'nama_file_asli',
        'path_penyimpanan',
        'status_verifikasi',
        'alasan_penolakan',
        'waktu_upload',
    ];

    protected $casts = [
        'waktu_upload' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function getJenisDokumenTextAttribute(): string
    {
        return match($this->jenis_dokumen) {
            self::JENIS_FOTO => 'Foto',
            self::JENIS_KTP => 'KTP',
            self::JENIS_KK => 'Kartu Keluarga',
            self::JENIS_AKTA_KELAHIRAN => 'Akta Kelahiran',
            self::JENIS_RAPOR => 'Rapor',
            self::JENIS_LAINNYA => 'Lainnya',
            default => $this->jenis_dokumen,
        };
    }

    public function getStatusVerifikasiColorAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_VERIFIED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    public function getStatusVerifikasiTextAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak',
            default => $this->status_verifikasi,
        };
    }

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status_verifikasi', self::STATUS_REJECTED);
    }

    public function isPending(): bool
    {
        return $this->status_verifikasi === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status_verifikasi === self::STATUS_VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->status_verifikasi === self::STATUS_REJECTED;
    }
}
