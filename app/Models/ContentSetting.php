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
        'faq_data',
        'kegiatan_pesantren_data',
    ];

    protected $casts = [
        'program_unggulan_data' => 'array',
        'faq_data' => 'array',
        'kegiatan_pesantren_data' => 'array',
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

        if (is_string($programs)) {
            $programs = json_decode($programs, true) ?? [];
        }

        return $programs;
    }

    /**
     * Get FAQ data dengan format yang konsisten
     */
    public function getFaqAttribute(): array
    {
        $faqs = $this->faq_data ?? [];

        if (is_string($faqs)) {
            $faqs = json_decode($faqs, true) ?? [];
        }

        return $faqs;
    }

    /**
     * Get kegiatan pesantren data dengan format yang konsisten
     */
    public function getKegiatanPesantrenAttribute(): array
    {
        $kegiatan = $this->kegiatan_pesantren_data ?? [];

        if (is_string($kegiatan)) {
            $kegiatan = json_decode($kegiatan, true) ?? [];
        }

        return $kegiatan;
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
     * Add new FAQ
     */
    public function addFaq(array $faqData): void
    {
        $faqs = $this->getFaqAttribute();
        $faqs[] = $faqData;
        $this->update(['faq_data' => $faqs]);
    }

    /**
     * Delete FAQ by index
     */
    public function deleteFaq(int $index): bool
    {
        $faqs = $this->getFaqAttribute();

        if (isset($faqs[$index])) {
            unset($faqs[$index]);
            $this->update(['faq_data' => array_values($faqs)]);
            return true;
        }

        return false;
    }

    /**
     * Add new kegiatan pesantren
     */
    public function addKegiatanPesantren(array $kegiatanData): void
    {
        $kegiatan = $this->getKegiatanPesantrenAttribute();
        $kegiatan[] = $kegiatanData;
        $this->update(['kegiatan_pesantren_data' => $kegiatan]);
    }

    /**
     * Update kegiatan pesantren by index
     */
    public function updateKegiatanPesantren(int $index, array $kegiatanData): bool
    {
        $kegiatan = $this->getKegiatanPesantrenAttribute();

        if (isset($kegiatan[$index])) {
            $kegiatan[$index] = $kegiatanData;
            $this->update(['kegiatan_pesantren_data' => $kegiatan]);
            return true;
        }

        return false;
    }

    /**
     * Delete kegiatan pesantren by index
     */
    public function deleteKegiatanPesantren(int $index): bool
    {
        $kegiatan = $this->getKegiatanPesantrenAttribute();

        if (isset($kegiatan[$index])) {
            unset($kegiatan[$index]);
            $this->update(['kegiatan_pesantren_data' => array_values($kegiatan)]);
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
