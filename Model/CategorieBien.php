<?php

namespace Model;

use Database\Database;

class CategorieBien
{
    public static function all(): array
    {
        $stmt = Database::getInstance()->query('SELECT * FROM categories_biens ORDER BY libelle');
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getInstance()->query('SELECT * FROM categories_biens WHERE id = ?', [$id]);
        $categorie = $stmt->fetch();
        return $categorie ?: null;
    }
}
