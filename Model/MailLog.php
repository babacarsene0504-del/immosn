<?php

namespace Model;

use Database\Database;

class MailLog
{
    public static function create(
        ?string $userId,
        string $emailTo,
        string $subject,
        string $template,
        string $status,
        ?string $error = null
    ): void {
        Database::getInstance()->query(
            'INSERT INTO mail_logs (user_id, email_to, subject, template, status, error)
             VALUES (?, ?, ?, ?, ?, ?)',
            [$userId, $emailTo, $subject, $template, $status, $error]
        );
    }

    public static function recent(int $limit = 50): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM mail_logs ORDER BY sent_at DESC LIMIT ?',
            [$limit]
        );
        return $stmt->fetchAll();
    }
}
