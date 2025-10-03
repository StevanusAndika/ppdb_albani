<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestScore extends Model
{
    use HasFactory;

    const JENIS_TULIS = 'tulis';
    const JENIS_WAWANCARA = 'wawancara';
    const JENIS_BACA_QURAN = 'baca_quran';

    protected $fillable = [
        'registration_id',
        'jenis_tes',
        'nilai_tes',
        'penguji_id',
        'catatan',
        'tanggal_tes',
    ];

    protected $casts = [
        'nilai_tes' => 'decimal:2',
        'tanggal_tes' => 'date',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public function getJenisTesTextAttribute(): string
    {
        return match($this->jenis_tes) {
            self::JENIS_TULIS => 'Tes Tulis',
            self::JENIS_WAWANCARA => 'Wawancara',
            self::JENIS_BACA_QURAN => 'Baca Quran',
            default => $this->jenis_tes,
        };
    }

    public function getNilaiFormatAttribute(): string
    {
        return number_format($this->nilai_tes, 2);
    }

    public function getKeteranganAttribute(): string
    {
        if ($this->nilai_tes >= 80) return 'Sangat Baik';
        if ($this->nilai_tes >= 70) return 'Baik';
        if ($this->nilai_tes >= 60) return 'Cukup';
        return 'Kurang';
    }

    public function scopeJenisTes($query, $jenis)
    {
        return $query->where('jenis_tes', $jenis);
    }

    public function scopeTulis($query)
    {
        return $query->where('jenis_tes', self::JENIS_TULIS);
    }

    public function scopeWawancara($query)
    {
        return $query->where('jenis_tes', self::JENIS_WAWANCARA);
    }

    public function scopeBacaQuran($query)
    {
        return $query->where('jenis_tes', self::JENIS_BACA_QURAN);
    }
}
