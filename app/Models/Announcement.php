<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    const TARGET_SEMUA = 'semua';
    const TARGET_CALON_SANTRI = 'calon_santri';
    const TARGET_LULUS = 'lulus';
    const TARGET_TIDAK_LULUS = 'tidak_lulus';
    const TARGET_ADMIN = 'admin';
    const TARGET_PANITIA = 'panitia';

    protected $fillable = [
        'judul_pengumuman',
        'isi_pengumuman',
        'penulis_id',
        'target_audience',
        'status_publikasi',
        'waktu_publikasi',
    ];

    protected $casts = [
        'status_publikasi' => 'boolean',
        'waktu_publikasi' => 'datetime',
    ];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    public function getTargetAudienceTextAttribute(): string
    {
        return match($this->target_audience) {
            self::TARGET_SEMUA => 'Semua Pengguna',
            self::TARGET_CALON_SANTRI => 'Calon Santri',
            self::TARGET_LULUS => 'Santri Lulus',
            self::TARGET_TIDAK_LULUS => 'Tidak Lulus',
            self::TARGET_ADMIN => 'Admin',
            self::TARGET_PANITIA => 'Panitia',
            default => $this->target_audience,
        };
    }

    public function getStatusPublikasiTextAttribute(): string
    {
        return $this->status_publikasi ? 'Terpublikasi' : 'Draft';
    }

    public function getStatusPublikasiColorAttribute(): string
    {
        return $this->status_publikasi ? 'green' : 'yellow';
    }

    public function getIsiSingkatAttribute(): string
    {
        return substr(strip_tags($this->isi_pengumuman), 0, 100) . '...';
    }

    public function scopePublished($query)
    {
        return $query->where('status_publikasi', true)
                    ->whereNotNull('waktu_publikasi')
                    ->where('waktu_publikasi', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status_publikasi', false);
    }

    public function scopeForTarget($query, $target)
    {
        return $query->whereIn('target_audience', ['semua', $target]);
    }

    public function isPublished(): bool
    {
        return $this->status_publikasi &&
               $this->waktu_publikasi &&
               $this->waktu_publikasi <= now();
    }

    public function publish(): void
    {
        $this->update([
            'status_publikasi' => true,
            'waktu_publikasi' => now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'status_publikasi' => false,
        ]);
    }
}
