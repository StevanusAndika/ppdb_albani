<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat_lengkap',
        'sekolah_asal',
        'nama_ayah',
        'nama_ibu',
        'telepon_orang_tua',
        'pekerjaan_orang_tua',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function getJenisKelaminTextAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getUmurAttribute(): int
    {
        return now()->diffInYears($this->tanggal_lahir);
    }
}
