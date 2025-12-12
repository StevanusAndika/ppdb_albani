<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDocument extends Model
{
    use HasFactory;

    protected $table = 'registration_documents';

    protected $fillable = [
        'id_pendaftaran',
        'tipe_dokumen',
        'file_path'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get registration by id_pendaftaran
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    /**
     * Scope by registration
     */
    public function scopeByRegistration($query, $idPendaftaran)
    {
        return $query->where('id_pendaftaran', $idPendaftaran);
    }

    /**
     * Scope by document type
     */
    public function scopeByDocumentType($query, $tipeDocumen)
    {
        return $query->where('tipe_dokumen', $tipeDocumen);
    }

    /**
     * Get file path URL
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Check if file exists
     */
    public function fileExists()
    {
        return \Storage::exists($this->file_path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile()
    {
        if ($this->fileExists()) {
            \Storage::delete($this->file_path);
        }
    }
}
