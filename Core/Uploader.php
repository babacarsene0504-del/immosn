<?php

namespace Core;

class Uploader
{
    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE = 3 * 1024 * 1024; // 3 Mo, conforme au cahier des charges

    /**
     * Valide et déplace un fichier uploadé. Retourne le nom de fichier généré (UUID)
     * en cas de succès, lève une exception sinon.
     */
    public static function upload(array $file, string $destDir): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Erreur lors de l\'upload du fichier.');
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new \RuntimeException('Fichier trop volumineux (3 Mo maximum).');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            throw new \RuntimeException('Format non autorisé (JPEG, PNG ou WebP uniquement).');
        }

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        };

        // Nom de fichier UUID : jamais le nom d'origine, pour éviter tout risque
        // d'écrasement ou d'injection de chemin via un nom de fichier malveillant.
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $destPath = $destDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new \RuntimeException('Impossible d\'enregistrer le fichier.');
        }

        return $filename;
    }
}
