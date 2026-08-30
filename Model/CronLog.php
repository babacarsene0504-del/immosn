<?php

namespace Model;

use Database\Database;

class CronLog
{
    public static function create(
        string $script,
        string $status,
        int $itemsProcessed = 0,
        ?int $durationMs = null,
        ?string $errorMessage = null
    ): void {
        Database::getInstance()->query(
            'INSERT INTO cron_logs (script, status, items_processed, duration_ms, error_message)
             VALUES (?, ?, ?, ?, ?)',
            [$script, $status, $itemsProcessed, $durationMs, $errorMessage]
        );
    }

    public static function recent(int $limit = 50): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM cron_logs ORDER BY executed_at DESC LIMIT ?',
            [$limit]
        );
        return $stmt->fetchAll();
    }
}
