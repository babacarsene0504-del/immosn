<?php

namespace Model;

use Database\Database;

class Photo
{
    public static function create(string $bienId, string $filePath, bool $isPrincipal = false): void
    {
        Database::getInstance()->query(
            'INSERT INTO photos (bien_id, file_path, is_principal) VALUES (?, ?, ?)',
            [$bienId, $filePath, $isPrincipal]
        );
    }

    public static function getByBien(string $bienId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM photos WHERE bien_id = ? ORDER BY is_principal DESC, created_at ASC',
            [$bienId]
        );
        return $stmt->fetchAll();
    }
}
