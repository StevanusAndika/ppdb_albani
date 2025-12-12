<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramUnggulan extends Model
{
    use HasFactory;

    protected $table = 'programs_unggulan';

    protected $fillable = [
        'nama_program',
        'potongan',
        'perlu_verifikasi',
        'dokumen_tambahan'
    ];

    protected $casts = [
        'potongan' => 'decimal:2',
        'dokumen_tambahan' => 'array'
    ];

    /**
     * Get registrations for this program
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all required documents for this program (combined with package)
     */
    public function getAllRequiredDocuments($package = null)
    {
        $docs = $this->dokumen_tambahan ?? [];
        
        if ($package && $package->required_documents) {
            $docs = array_merge($docs, $package->required_documents);
        }
        
        return array_unique($docs);
    }

    /**
     * Scope active programs
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('nama_program');
    }
}
