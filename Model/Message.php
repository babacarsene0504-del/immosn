<?php

namespace Model;

use Database\Database;

class Message
{
    public static function create(?string $bienId, string $expediteurId, string $destinataireId, string $sujet, string $contenu): string
    {
        $stmt = Database::getInstance()->query(
            'INSERT INTO messages (bien_id, expediteur_id, destinataire_id, sujet, contenu)
             VALUES (?, ?, ?, ?, ?)
             RETURNING id',
            [$bienId, $expediteurId, $destinataireId, $sujet, $contenu]
        );
        return $stmt->fetchColumn();
    }

    /** Liste des conversations d'un utilisateur : un correspondant, avec son dernier message */
    public static function getConversations(string $userId): array
    {
        $stmt = Database::getInstance()->query(
            "SELECT DISTINCT ON (other_id) other_id, contenu, created_at, lu, destinataire_id
             FROM (
                 SELECT *,
                     CASE WHEN expediteur_id = ? THEN destinataire_id ELSE expediteur_id END AS other_id
                 FROM messages
                 WHERE expediteur_id = ? OR destinataire_id = ?
             ) sub
             ORDER BY other_id, created_at DESC",
            [$userId, $userId, $userId]
        );
        $conversations = $stmt->fetchAll();

        foreach ($conversations as &$conv) {
            $conv['other_user'] = User::findById($conv['other_id']);
        }

        return $conversations;
    }

    public static function getThread(string $userId, string $otherUserId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM messages
             WHERE (expediteur_id = ? AND destinataire_id = ?)
                OR (expediteur_id = ? AND destinataire_id = ?)
             ORDER BY created_at ASC',
            [$userId, $otherUserId, $otherUserId, $userId]
        );
        return $stmt->fetchAll();
    }

    public static function markThreadRead(string $userId, string $otherUserId): void
    {
        Database::getInstance()->query(
            'UPDATE messages SET lu = true WHERE destinataire_id = ? AND expediteur_id = ? AND lu = false',
            [$userId, $otherUserId]
        );
    }
}
