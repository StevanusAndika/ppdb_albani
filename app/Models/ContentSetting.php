<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'tagline',
        'visi_judul',
        'visi_deskripsi',
        'misi_judul',
        'misi_deskripsi',
        'program_unggulan_data',
        'alur_pendaftaran_judul',
        'alur_pendaftaran_deskripsi',
        'persyaratan_dokumen_judul',
        'persyaratan_dokumen_deskripsi',
        'akte_path',
        'formulir_path',
        'ijazah_path',
        'kk_path',
        'pasfoto_path',
    ];

    protected $casts = [
        'program_unggulan_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get default file paths
     */
    public function getDefaultFilePaths(): array
    {
        return [
            'akte' => 'images/default/akte.png',
            'formulir' => 'images/default/formulir.png',
            'ijazah' => 'images/default/ijazah.png',
            'kk' => 'images/default/kk.png',
            'pasfoto' => 'images/default/pasfoto.png',
        ];
    }

    /**
     * Get file path with fallback to default
     */
    public function getFilePath($type): string
    {
        $path = $this->{$type . '_path'};
        $defaults = $this->getDefaultFilePaths();

        if ($path && file_exists(public_path($path))) {
            return $path;
        }

        return $defaults[$type] ?? 'images/default/document.png';
    }

    /**
     * Get program unggulan data dengan format yang konsisten
     */
    public function getProgramUnggulanAttribute(): array
    {
        $programs = $this->program_unggulan_data ?? [];

        // Jika masih menggunakan format lama (string), konversi ke array
        if (is_string($programs)) {
            $programs = json_decode($programs, true) ?? [];
        }

        return $programs;
    }

    /**
     * Add new program unggulan
     */
    public function addProgramUnggulan(array $programData): void
    {
        $programs = $this->getProgramUnggulanAttribute();
        $programs[] = $programData;
        $this->update(['program_unggulan_data' => $programs]);
    }

    /**
     * Update program unggulan by index
     */
    public function updateProgramUnggulan(int $index, array $programData): bool
    {
        $programs = $this->getProgramUnggulanAttribute();

        if (isset($programs[$index])) {
            $programs[$index] = $programData;
            $this->update(['program_unggulan_data' => $programs]);
            return true;
        }

        return false;
    }

    /**
     * Delete program unggulan by index
     */
    public function deleteProgramUnggulan(int $index): bool
    {
        $programs = $this->getProgramUnggulanAttribute();

        if (isset($programs[$index])) {
            unset($programs[$index]);
            $this->update(['program_unggulan_data' => array_values($programs)]);
            return true;
        }

        return false;
    }

    /**
     * Get singleton instance
     */
    public static function getSettings()
    {
        return self::firstOrCreate([], []);
    }
}
