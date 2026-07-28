<?php
// config/upload_rules.php
// Règle unique de validation des fichiers de facturation, partagée par les contrôleurs.

const FACTURE_ALLOWED_MIMES = [
    'application/pdf' => 'pdf',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    'application/vnd.ms-excel' => 'xls',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
];

const FACTURE_MAX_SIZE = 10 * 1024 * 1024; // 10 Mo

/**
 * Valide un fichier uploadé ($_FILES['xxx']) selon la whitelist de factures.
 * Retourne ['ok' => bool, 'extension' => string|null, 'error' => string|null].
 */
function validateFactureUpload(array $file): array
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'extension' => null, 'error' => "Aucun fichier reçu ou erreur pendant l'upload."];
    }

    if ($file['size'] > FACTURE_MAX_SIZE) {
        return ['ok' => false, 'extension' => null, 'error' => 'Fichier trop volumineux (10 Mo maximum).'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $realMime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(FACTURE_ALLOWED_MIMES[$realMime])) {
        return ['ok' => false, 'extension' => null, 'error' => 'Format non autorisé. Utilise PDF, DOC, DOCX, XLS ou XLSX.'];
    }

    return ['ok' => true, 'extension' => FACTURE_ALLOWED_MIMES[$realMime], 'error' => null];
}
