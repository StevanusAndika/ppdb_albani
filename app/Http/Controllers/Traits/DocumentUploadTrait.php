<?php

namespace App\Http\Controllers\Traits;

use App\Models\RegistrationDocument;
use App\Services\DocumentRequirementService;

trait DocumentUploadTrait
{
    protected ?DocumentRequirementService $documentRequirementService = null;

    public function setDocumentRequirementService(DocumentRequirementService $service)
    {
        $this->documentRequirementService = $service;
        return $this;
    }

    /**
     * Initialize document requirement service
     */
    protected function initDocumentService()
    {
        if (!$this->documentRequirementService) {
            $this->documentRequirementService = app(DocumentRequirementService::class);
        }
    }

    /**
     * Check if all required documents are complete
     */
    protected function checkAllDocumentsComplete($registration)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->areAllDocumentsComplete($registration);
    }

    /**
     * Get count of uploaded documents
     */
    protected function getUploadedDocumentsCount($registration)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->getUploadedDocumentsCount($registration);
    }

    /**
     * Get required documents count
     */
    protected function getRequiredDocumentsCount($registration)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->getRequiredDocumentsCount($registration);
    }

    /**
     * Get required documents
     */
    protected function getRequiredDocuments($registration)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->getRequiredDocuments($registration);
    }

    /**
     * Get missing documents
     */
    protected function getMissingDocuments($registration)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->getMissingDocuments($registration);
    }

    /**
     * Get document label
     */
    protected function getDocumentLabel($documentType)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->getDocumentLabel($documentType);
    }

    /**
     * Save document to registration_documents table
     */
    protected function saveDocument($idPendaftaran, $tipeDocumen, $filePath)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->saveDocument($idPendaftaran, $tipeDocumen, $filePath);
    }

    /**
     * Delete document
     */
    protected function deleteDocument($idPendaftaran, $tipeDocumen)
    {
        $this->initDocumentService();
        return $this->documentRequirementService->deleteDocument($idPendaftaran, $tipeDocumen);
    }

    /**
     * Get uploaded documents
     */
    protected function getUploadedDocuments($registration)
    {
        return RegistrationDocument::byRegistration($registration->id_pendaftaran)
            ->get()
            ->keyBy('tipe_dokumen');
    }

    /**
     * Validate document type against required documents
     */
    protected function isValidDocumentType($registration, $documentType)
    {
        $required = $this->getRequiredDocuments($registration);
        return in_array($documentType, $required);
    }
}
