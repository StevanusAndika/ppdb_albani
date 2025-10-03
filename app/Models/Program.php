<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_program',
        'deskripsi',
        'kuota_penerimaan',
        'biaya_pendaftaran',
        'durasi_program',
        'status_aktif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
        'status_aktif' => 'boolean',
        'kuota_penerimaan' => 'integer',
    ];

    /**
     * Scope untuk program aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope untuk program tidak aktif
     */
    public function scopeTidakAktif($query)
    {
        return $query->where('status_aktif', false);
    }

    /**
     * Accessor untuk menampilkan status sebagai teks
     */
    public function getStatusTextAttribute()
    {
        return $this->status_aktif ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Accessor untuk format biaya dengan Rupiah
     */
    public function getBiayaFormatAttribute()
    {
        return 'Rp ' . number_format($this->biaya_pendaftaran, 0, ',', '.');
    }

    /**
     * Check jika program masih memiliki kuota
     */
    public function memilikiKuota(): bool
    {
        return $this->kuota_penerimaan > 0;
    }
  
        public function registrations()
        {
            return $this->hasMany(Registration::class);
        }
}
