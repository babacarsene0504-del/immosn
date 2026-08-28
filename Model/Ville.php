<?php

namespace Model;

use Database\Database;

class Ville
{
    /** Liste des régions distinctes, pour le premier select */
    public static function getRegions(): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT DISTINCT region FROM villes ORDER BY region'
        );
        return array_column($stmt->fetchAll(), 'region');
    }

    /** Villes appartenant à une région donnée — pour le 2e select, rempli en AJAX */
    public static function getByRegion(string $region): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT DISTINCT id, ville FROM villes WHERE region = ? ORDER BY ville',
            [$region]
        );
        return $stmt->fetchAll();
    }

    /** Quartiers d'une ville donnée — pour un éventuel 3e niveau plus tard */
    public static function getQuartiersByVille(int $villeId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT id, quartier FROM villes WHERE id = ? OR ville = (SELECT ville FROM villes WHERE id = ?) ORDER BY quartier',
            [$villeId, $villeId]
        );
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getInstance()->query('SELECT * FROM villes WHERE id = ?', [$id]);
        $ville = $stmt->fetch();
        return $ville ?: null;
    }
}
