<?php

namespace Core;

use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Model\QrCode;

class QrGenerator
{
    /**
     * Génère le QR Code d'un bien : crée un token unique, l'encode en PNG,
     * l'enregistre sur disque et crée la ligne correspondante dans qr_codes.
     *
     * Le QR Code encode l'URL /scan/{token}, que le ScannerController résoudra
     * ensuite vers la fiche du bien (Sprint 4).
     */
    public static function generateForBien(string $bienId, string $baseUrl): array
    {
        $token = bin2hex(random_bytes(16)); // 32 caractères, imprévisible

        $qrCode = new EndroidQrCode(
            data: rtrim($baseUrl, '/') . '/scan/' . $token,
            size: 400,
            margin: 16,
            foregroundColor: new Color(43, 36, 32),   // #2B2420, cohérent avec la charte
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $storageDir = __DIR__ . '/../public/storage/qrcodes';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $fileName = $bienId . '.png';
        $absolutePath = $storageDir . '/' . $fileName;
        $result->saveToFile($absolutePath);

        // Chemin relatif tel que servi publiquement (à adapter selon votre config Apache/Nginx
        // si storage/ n'est pas directement accessible — voir note ci-dessous)
        $publicPath = '/storage/qrcodes/' . $fileName;

        QrCode::create($bienId, $token, $publicPath);

        return ['token' => $token, 'file_path' => $publicPath];
    }
}
