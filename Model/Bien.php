<?php

namespace Model;

use Database\Database;

class Bien
{
    // La colonne `statut` n'a pas de contrainte CHECK en base (comme users.role) —
    // ces valeurs sont une convention d'application, à respecter partout dans le code.
    private const STATUTS = ['en_attente', 'actif', 'expire', 'rejete'];
    private const TYPES_OFFRE = ['vente', 'location'];

    public static function create(array $data): string
    {
        if (!in_array($data['type_offre'], self::TYPES_OFFRE, true)) {
            throw new \InvalidArgumentException("type_offre invalide : {$data['type_offre']}");
        }

        $stmt = Database::getInstance()->query(
            'INSERT INTO biens
                (user_id, category_id, ville_id, titre, description, type_offre, prix,
                 superficie, pieces, chambres, salles_de_bain, latitude, longitude,
                 statut, expire_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
             RETURNING id',
            [
                $data['user_id'],
                $data['category_id'],
                $data['ville_id'],
                $data['titre'],
                $data['description'],
                $data['type_offre'],
                $data['prix'],
                $data['superficie'],
                $data['pieces'] ?? null,
                $data['chambres'] ?? null,
                $data['salles_de_bain'] ?? null,
                $data['latitude'] ?? null,
                $data['longitude'] ?? null,
                'en_attente', // tout nouveau bien attend la validation d'un modérateur
                $data['expire_at'], // NOT NULL en base — doit être calculé avant l'appel (ex: +90 jours)
            ]
        );

        return $stmt->fetchColumn();
    }

    public static function findById(string $id): ?array
    {
        $stmt = Database::getInstance()->query(
            'SELECT b.*, c.libelle AS categorie_libelle, v.ville AS ville_nom, v.region
             FROM biens b
             LEFT JOIN categories_biens c ON c.id = b.category_id
             LEFT JOIN villes v ON v.id = b.ville_id
             WHERE b.id = ?',
            [$id]
        );
        $bien = $stmt->fetch();
        return $bien ?: null;
    }

    /**
     * Recherche avec filtres optionnels.
     * $filters peut contenir : ville_id, category_id, type_offre, prix_max, chambres_min
     */
    public static function search(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $where = ["b.statut = 'actif'"];
        $params = [];

        if (!empty($filters['ville_id'])) {
            $where[] = 'b.ville_id = ?';
            $params[] = $filters['ville_id'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'b.category_id = ?';
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['type_offre'])) {
            $where[] = 'b.type_offre = ?';
            $params[] = $filters['type_offre'];
        }
        if (!empty($filters['prix_max'])) {
            $where[] = 'b.prix <= ?';
            $params[] = $filters['prix_max'];
        }
        if (!empty($filters['chambres_min'])) {
            $where[] = 'b.chambres >= ?';
            $params[] = $filters['chambres_min'];
        }

        $sql = 'SELECT b.*, c.libelle AS categorie_libelle, v.ville AS ville_nom, p.file_path AS photo_principale
                FROM biens b
                LEFT JOIN categories_biens c ON c.id = b.category_id
                LEFT JOIN villes v ON v.id = b.ville_id
                LEFT JOIN photos p ON p.bien_id = b.id AND p.is_principal = true
                WHERE ' . implode(' AND ', $where) . '
                ORDER BY b.created_at DESC
                LIMIT ? OFFSET ?';

        $params[] = $limit;
        $params[] = $offset;

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function getByUser(string $userId): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT b.*, c.libelle AS categorie_libelle, p.file_path AS photo_principale
             FROM biens b
             LEFT JOIN categories_biens c ON c.id = b.category_id
             LEFT JOIN photos p ON p.bien_id = b.id AND p.is_principal = true
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC',
            [$userId]
        );
        return $stmt->fetchAll();
    }

    public static function getSimilaires(string $bienId, int $limit = 3): array
    {
        $bien = self::findById($bienId);
        if (!$bien) {
            return [];
        }

        $stmt = Database::getInstance()->query(
            "SELECT * FROM biens
             WHERE category_id = ? AND id != ? AND statut = 'actif'
             ORDER BY created_at DESC
             LIMIT ?",
            [$bien['category_id'], $bienId, $limit]
        );
        return $stmt->fetchAll();
    }

    public static function incrementViews(string $id): void
    {
        Database::getInstance()->query(
            'UPDATE biens SET nb_vues = COALESCE(nb_vues, 0) + 1 WHERE id = ?',
            [$id]
        );
    }

    /** Utilisé par le modérateur (AdminController) pour valider ou rejeter un bien */
    public static function updateStatut(string $id, string $statut, ?string $motifRejet = null): void
    {
        if (!in_array($statut, self::STATUTS, true)) {
            throw new \InvalidArgumentException("Statut invalide : {$statut}");
        }

        Database::getInstance()->query(
            'UPDATE biens SET statut = ?, motif_rejet = ?, updated_at = NOW() WHERE id = ?',
            [$statut, $motifRejet, $id]
        );
    }


    /** Biens en attente de validation, pour le dashboard modérateur */
    public static function findByStatut(string $statut): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT b.*, c.libelle AS categorie_libelle, v.ville AS ville_nom,
                    u.nom AS proprietaire_nom, u.prenom AS proprietaire_prenom
             FROM biens b
             LEFT JOIN categories_biens c ON c.id = b.category_id
             LEFT JOIN villes v ON v.id = b.ville_id
             LEFT JOIN users u ON u.id = b.user_id
             WHERE b.statut = ?
             ORDER BY b.created_at ASC',
            [$statut]
        );
        return $stmt->fetchAll();
    }


    /** Utilisé par AdminController pour lister les biens à valider/rejeter */
    public static function getByStatut(string $statut): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT b.*, c.libelle AS categorie_libelle, v.ville AS ville_nom, u.nom, u.prenom
             FROM biens b
             LEFT JOIN categories_biens c ON c.id = b.category_id
             LEFT JOIN villes v ON v.id = b.ville_id
             LEFT JOIN users u ON u.id = b.user_id
             WHERE b.statut = ?
             ORDER BY b.created_at ASC',
            [$statut]
        );
        return $stmt->fetchAll();
    }

    public static function countByStatut(string $statut): int
    {
        $stmt = Database::getInstance()->query('SELECT COUNT(*) FROM biens WHERE statut = ?', [$statut]);
        return (int) $stmt->fetchColumn();
    }

  

    public static function delete(string $id): void
    {
        Database::getInstance()->query('DELETE FROM biens WHERE id = ?', [$id]);
    }
}
