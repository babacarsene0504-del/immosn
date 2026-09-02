<?php

namespace Model;

use Database\Database;

class Favori
{
    /** Ajoute ou retire un favori selon son état actuel. Retourne true si ajouté, false si retiré. */
    public static function toggle(string $userId, string $bienId): bool
    {
        if (self::exists($userId, $bienId)) {
            Database::getInstance()->query(
                'DELETE FROM favoris WHERE user_id = ? AND bien_id = ?',
                [$userId, $bienId]
            );
            return false;
        }

        Database::getInstance()->query(
            'INSERT INTO favoris (user_id, bien_id) VALUES (?, ?)',
            [$userId, $bienId]
        );
        return true;
    }

    public static function exists(string $userId, string $bienId): bool
    {
        $stmt = Database::getInstance()->query(
            'SELECT 1 FROM favoris WHERE user_id = ? AND bien_id = ?',
            [$userId, $bienId]
        );
        return (bool) $stmt->fetch();
    }

    public static function getByUser(string $userId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT b.*, c.libelle AS categorie_libelle, v.ville AS ville_nom
             FROM favoris f
             JOIN biens b ON b.id = f.bien_id
             LEFT JOIN categories_biens c ON c.id = b.category_id
             LEFT JOIN villes v ON v.id = b.ville_id
             WHERE f.user_id = ?
             ORDER BY f.created_at DESC',
            [$userId]
        );
        return $stmt->fetchAll();
    }
}
