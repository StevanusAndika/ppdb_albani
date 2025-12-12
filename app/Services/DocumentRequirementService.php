<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\RegistrationDocument;
class DocumentRequirementService
{
    /**
     * Get all required documents for a registration based on package and program unggulan
     */
    public function getRequiredDocuments(Registration $registration)
    {
        return array_keys($this->getDocumentDefinitions($registration));
    }

    /**
     * Get all uploaded documents for a registration
     */
    public function getUploadedDocuments(Registration $registration)
    {
        return RegistrationDocument::byRegistration($registration->id_pendaftaran)
            ->get()
            ->keyBy('tipe_dokumen');
    }

    /**
     * Normalize document type/label to standard type
     */
    protected function normalizeDocumentType($docType)
    {
        if (is_null($docType)) {
            return null;
        }

        if (is_array($docType) || is_object($docType)) {
            $docType = $this->extractDocumentTypeFromArray((array) $docType);
        }

        if (!$docType) {
            return null;
        }

        // Map labels to types
        $labelToTypeMap = [
            'Kartu Keluarga' => 'kartu_keluarga',
            'Ijazah' => 'ijazah',
            'Ijazah SMP' => 'ijazah',
            'Ijazah SMA' => 'ijazah',
            'Akta Kelahiran' => 'akta_kelahiran',
            'Pas Foto' => 'pas_foto',
            'SKU' => 'sku',
            'Sertifikat Hafiz' => 'sertifikat_hafiz',
            'Surat Rekomendasi' => 'surat_rekomendasi',
            'Dokumen Kesehatan' => 'dokumen_kesehatan',
        ];

        // If already a type (has underscore), return as is
        if (strpos($docType, '_') !== false) {
            return $docType;
        }

        // Check exact match
        if (isset($labelToTypeMap[$docType])) {
            return $labelToTypeMap[$docType];
        }

        // Check partial match (e.g., "Ijazah SMP" contains "Ijazah")
        foreach ($labelToTypeMap as $label => $type) {
            if (stripos($docType, $label) !== false || stripos($label, $docType) !== false) {
                return $type;
            }
        }

        // Fallback: convert to snake_case
        return strtolower(str_replace(' ', '_', $docType));
    }

    /**
     * Extract document type from a structured array/object definition
     */
    protected function extractDocumentTypeFromArray(array $definition)
    {
        $candidateKeys = ['type', 'document_type', 'tipe_dokumen', 'slug', 'key', 'kode', 'name', 'nama', 'label'];

        foreach ($candidateKeys as $key) {
            if (isset($definition[$key]) && $definition[$key]) {
                return $definition[$key];
            }
        }

        return null;
    }

    /**
     * Build normalized document definition (type + label)
     */
    protected function normalizeDocumentEntry($entry)
    {
        if (is_null($entry)) {
            return null;
        }

        // Convert object to array for consistent handling
        if (is_object($entry)) {
            $entry = (array) $entry;
        }

        $rawType = is_array($entry) ? $this->extractDocumentTypeFromArray($entry) : $entry;
        // Fallback for arrays that are just value lists (e.g., ["raport"])
        if (!$rawType && is_array($entry)) {
            foreach ($entry as $value) {
                if (is_string($value) && trim($value) !== '') {
                    $rawType = $value;
                    break;
                }
            }
        }
        $normalizedType = $this->normalizeDocumentType($rawType);

        if (!$normalizedType) {
            return null;
        }

        $rawLabel = is_array($entry)
            ? ($entry['label'] ?? $entry['nama'] ?? $entry['name'] ?? $rawType)
            : $rawType;

        return [
            'type' => $normalizedType,
            'label' => $this->getDocumentLabel($normalizedType, $rawLabel),
        ];
    }

    /**
     * Return associative definitions keyed by normalized type
     */
    public function getDocumentDefinitions(Registration $registration): array
    {
        $definitions = [];

        $collect = function ($items, string $source) use (&$definitions) {
            if (empty($items)) {
                return;
            }

            // Flatten nested arrays/objects so structures like {"dokumen":["raport"]} are parsed
            $flatItems = [];
            $itemsArray = (array) $items; // avoid passing temporary to array_walk_recursive
            array_walk_recursive($itemsArray, function ($value) use (&$flatItems) {
                $flatItems[] = $value;
            });

            foreach ($flatItems as $item) {
                $normalized = $this->normalizeDocumentEntry($item);
                if (!$normalized) {
                    continue;
                }

                $definitions[$normalized['type']] = [
                    'label' => $normalized['label'],
                    'source' => $source,
                ];
            }
        };

        // Documents from package
        $collect($registration->package?->required_documents, 'package');

        return $definitions;
    }

    /**
     * Check if all required documents are uploaded
     */
    public function areAllDocumentsComplete(Registration $registration)
    {
        $definitions = $this->getDocumentDefinitions($registration);
        $required = array_keys($definitions);
        
        if (empty($required)) {
            return true;
        }

        $uploaded = RegistrationDocument::byRegistration($registration->id_pendaftaran)
            ->pluck('tipe_dokumen')
            ->toArray();

        // Normalize both required and uploaded to ensure matching
        $normalizedRequired = array_map([$this, 'normalizeDocumentType'], $required);
        $normalizedUploaded = array_map([$this, 'normalizeDocumentType'], $uploaded);

        foreach ($normalizedRequired as $docType) {
            if ($docType && !in_array($docType, $normalizedUploaded)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get count of uploaded documents that match required documents
     */
    public function getUploadedDocumentsCount(Registration $registration)
    {
        $definitions = $this->getDocumentDefinitions($registration);
        $required = array_keys($definitions);
        
        if (empty($required)) {
            return 0;
        }

        $uploaded = RegistrationDocument::byRegistration($registration->id_pendaftaran)
            ->pluck('tipe_dokumen')
            ->toArray();

        // Normalize both required and uploaded to ensure matching
        $normalizedRequired = array_map([$this, 'normalizeDocumentType'], $required);
        $normalizedUploaded = array_map([$this, 'normalizeDocumentType'], $uploaded);

        // Count how many required documents are uploaded
        $count = 0;
        foreach ($normalizedRequired as $docType) {
            if ($docType && in_array($docType, $normalizedUploaded)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get required documents count
     */
    public function getRequiredDocumentsCount(Registration $registration)
    {
        return count($this->getDocumentDefinitions($registration));
    }

    /**
     * Get missing documents
     */
    public function getMissingDocuments(Registration $registration)
    {
        $definitions = $this->getDocumentDefinitions($registration);
        $required = array_keys($definitions);
        
        if (empty($required)) {
            return [];
        }

        $uploaded = RegistrationDocument::byRegistration($registration->id_pendaftaran)
            ->pluck('tipe_dokumen')
            ->toArray();

        // Normalize both required and uploaded to ensure matching
        $normalizedRequired = array_map([$this, 'normalizeDocumentType'], $required);
        $normalizedUploaded = array_map([$this, 'normalizeDocumentType'], $uploaded);

        $missing = [];
        foreach ($normalizedRequired as $index => $docType) {
            if ($docType && !in_array($docType, $normalizedUploaded)) {
                $missing[] = $docType;
            }
        }

        return $missing;
    }

    /**
     * Get document label in Indonesian
     */
    public function getDocumentLabel($documentType, $fallbackLabel = null)
    {
        $labels = [
            'kartu_keluarga' => 'Kartu Keluarga',
            'ijazah' => 'Ijazah',
            'akta_kelahiran' => 'Akta Kelahiran',
            'pas_foto' => 'Pas Foto',
            'sku' => 'SKU (Surat Keterangan Ujian)',
            'sertifikat_hafiz' => 'Sertifikat Hafiz (jika ada)',
            'surat_rekomendasi' => 'Surat Rekomendasi',
            'dokumen_kesehatan' => 'Dokumen Kesehatan'
        ];

        if (isset($labels[$documentType])) {
            return $labels[$documentType];
        }

        if ($fallbackLabel) {
            return $fallbackLabel;
        }

        if ($documentType) {
            return ucwords(str_replace('_', ' ', $documentType));
        }

        return 'Dokumen';
    }

    /**
     * Save document
     */
    public function saveDocument($idPendaftaran, $tipeDocumen, $filePath)
    {
        // Delete existing document if any
        RegistrationDocument::byRegistration($idPendaftaran)
            ->byDocumentType($tipeDocumen)
            ->delete();

        return RegistrationDocument::create([
            'id_pendaftaran' => $idPendaftaran,
            'tipe_dokumen' => $tipeDocumen,
            'file_path' => $filePath
        ]);
    }

    /**
     * Delete document
     */
    public function deleteDocument($idPendaftaran, $tipeDocumen)
    {
        $document = RegistrationDocument::byRegistration($idPendaftaran)
            ->byDocumentType($tipeDocumen)
            ->first();

        if ($document) {
            $document->deleteFile();
            $document->delete();
            return true;
        }

        return false;
    }
}
