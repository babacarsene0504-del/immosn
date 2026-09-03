<?php

namespace Model;

use Database\Database;

class AlerteLog
{
    public static function create(string $alerteId, string $bienId, string $userId): void
    {
        Database::getInstance()->query(
            'INSERT INTO alerte_logs (alerte_id, bien_id, user_id) VALUES (?, ?, ?)',
            [$alerteId, $bienId, $userId]
        );
    }
}
