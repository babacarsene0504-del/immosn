<?php

namespace Database;

use PDO;
use PDOException;

/**
 * Singleton de connexion PDO vers la base Neon (PostgreSQL serverless).
 *
 * Config attendue dans .env (jamais commité) :
 *   DB_HOST=ep-xxx-pooler.eu-central-1.aws.neon.tech
 *   DB_PORT=5432
 *   DB_NAME=gestion_immobiliere
 *   DB_USER=...
 *   DB_PASS=...
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? null;
        $port = $_ENV['DB_PORT'] ?? '5432';
        $name = $_ENV['DB_NAME'] ?? null;
        $user = $_ENV['DB_USER'] ?? null;
        $pass = $_ENV['DB_PASS'] ?? null;

        $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES    => false,
            ]);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new PDOException('Impossible de se connecter à la base de données.');
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private function __clone() {}
    public function __wakeup() {}
}