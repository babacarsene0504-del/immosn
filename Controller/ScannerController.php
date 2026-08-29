<?php

namespace Controller;

use Model\QrCode;

class ScannerController
{
    /** GET /scanner — page avec activation caméra */
    public function showScanner(): void
    {
        require __DIR__ . '/../View/scanner/index.php';
    }

    /** GET /scan/:token — résout le token et redirige vers la fiche du bien */
    public function scan(string $token): void
    {
        $qr = QrCode::findByToken($token);

        if (!$qr) {
            http_response_code(404);
            require __DIR__ . '/../View/scanner/invalide.php';
            return;
        }

        QrCode::registerScan($token);

        header('Location: /biens/' . $qr['bien_id']);
        exit;
    }
}
