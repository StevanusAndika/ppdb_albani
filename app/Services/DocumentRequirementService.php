<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\ProgramUnggulan;

class DocumentRequirementService
{
    /**
     * Get all required documents for a registration based on package and program unggulan
     */
    public function getRequiredDocuments(Registration $registration)
    {
        $requiredDocs = [];

        // Documents from package
        if ($registration->package && $registration->package->required_documents) {
            $requiredDocs = array_merge($requiredDocs, $registration->package->required_documents);
        }

        // Additional documents from program unggulan
        if ($registration->programUnggulan && $registration->programUnggulan->dokumen_tambahan) {
            $requiredDocs = array_merge($requiredDocs, $registration->programUnggulan->dokumen_tambahan);
        }

        return array_unique($requiredDocs);
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
     * Check if all required documents are uploaded
     */
    public function areAllDocumentsComplete(Registration $registration)
    {
        $required = $this->getRequiredDocuments($registration);
        
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
            if (!in_array($docType, $normalizedUploaded)) {
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
        $required = $this->getRequiredDocuments($registration);
        
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
            if (in_array($docType, $normalizedUploaded)) {
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
        return count($this->getRequiredDocuments($registration));
    }

    /**
     * Get missing documents
     */
    public function getMissingDocuments(Registration $registration)
    {
        $required = $this->getRequiredDocuments($registration);
        
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
        foreach ($required as $index => $docType) {
            $normalizedType = $normalizedRequired[$index];
            if (!in_array($normalizedType, $normalizedUploaded)) {
                $missing[] = $docType; // Return original label for display
            }
        }

        return $missing;
    }

    /**
     * Get document label in Indonesian
     */
    public function getDocumentLabel($documentType)
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

        return $labels[$documentType] ?? $documentType;
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
