<?php

namespace Model;

use Database\Database;

class Alerte
{
    public static function create(string $userId, ?int $categoryId, ?int $villeId, float $budgetMax): string
    {
        $stmt = Database::getInstance()->query(
            'INSERT INTO alertes (user_id, category_id, ville_id, budget_max, is_active)
             VALUES (?, ?, ?, ?, true)
             RETURNING id',
            [$userId, $categoryId, $villeId, $budgetMax]
        );
        return $stmt->fetchColumn();
    }

    public static function getByUser(string $userId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT a.*, c.libelle AS categorie_libelle, v.ville AS ville_nom
             FROM alertes a
             LEFT JOIN categories_biens c ON c.id = a.category_id
             LEFT JOIN villes v ON v.id = a.ville_id
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC',
            [$userId]
        );
        return $stmt->fetchAll();
    }

    public static function setActive(string $id, string $userId, bool $active): void
    {
        Database::getInstance()->query(
            'UPDATE alertes SET is_active = ? WHERE id = ? AND user_id = ?',
            // Même correctif que Photo::create : false → '' est rejeté par PostgreSQL via PDO.
            [$active ? 1 : 0, $id, $userId]
        );
    }

    /** Alertes actives correspondant à un bien qui vient d'être validé, non encore notifiées */
    public static function getMatchingForBien(string $bienId, ?int $categoryId, ?int $villeId, float $prix): array
    {
        $stmt = Database::getInstance()->query(
            "SELECT a.* FROM alertes a
             WHERE a.is_active = true
               AND a.budget_max >= ?
               AND (a.category_id IS NULL OR a.category_id = ?)
               AND (a.ville_id IS NULL OR a.ville_id = ?)
               AND NOT EXISTS (
                   SELECT 1 FROM alerte_logs al WHERE al.alerte_id = a.id AND al.bien_id = ?
               )",
            [$prix, $categoryId, $villeId, $bienId]
        );
        return $stmt->fetchAll();
    }
}
