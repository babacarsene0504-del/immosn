<?php

namespace Model;

use Database\Database;

class User
{
    private const ROLES = ['particulier', 'agence', 'moderateur', 'admin'];

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(string $id): ?array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM users WHERE id = ?',
            [$id]
        );
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(array $data): string
    {
        $role = $data['role'] ?? 'particulier';
        if (!in_array($role, self::ROLES, true)) {
            throw new \InvalidArgumentException("Rôle invalide : {$role}");
        }

        $stmt = Database::getInstance()->query(
            'INSERT INTO users (nom, prenom, email, password, telephone, ville, role, is_verified, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, false, \'actif\')
             RETURNING id',
            [
                $data['nom'],
                $data['prenom'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                $data['telephone'] ?? null,
                $data['ville'] ?? null,
                $role,
            ]
        );

        return $stmt->fetchColumn();
    }

    public static function verifyPassword(string $plainPassword, string $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }

    public static function emailExists(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }

    public static function markVerified(string $id): void
    {
        Database::getInstance()->query(
            'UPDATE users SET is_verified = true, updated_at = NOW() WHERE id = ?',
            [$id]
        );
    }
}
