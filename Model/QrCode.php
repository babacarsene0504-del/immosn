<?php

namespace Model;

use Database\Database;

class QrCode
{
    public static function create(string $bienId, string $token, string $filePath): string
    {
        $stmt = Database::getInstance()->query(
            'INSERT INTO qr_codes (bien_id, token, file_path)
             VALUES (?, ?, ?)
             RETURNING id',
            [$bienId, $token, $filePath]
        );
        return $stmt->fetchColumn();
    }

    public static function findByToken(string $token): ?array
    {
        $stmt = Database::getInstance()->query(
            'SELECT qc.*, b.titre, b.statut
             FROM qr_codes qc
             JOIN biens b ON b.id = qc.bien_id
             WHERE qc.token = ?',
            [$token]
        );
        $qr = $stmt->fetch();
        return $qr ?: null;
    }

    public static function findByBienId(string $bienId): ?array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM qr_codes WHERE bien_id = ?',
            [$bienId]
        );
        $qr = $stmt->fetch();
        return $qr ?: null;
    }

    public static function registerScan(string $token): void
    {
        Database::getInstance()->query(
            'UPDATE qr_codes SET nb_scans = COALESCE(nb_scans, 0) + 1, last_scan_at = NOW() WHERE token = ?',
            [$token]
        );
    }
}
