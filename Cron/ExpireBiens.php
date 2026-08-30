<?php

namespace Cron;

use Database\Database;

/** Passe en statut "expire" tous les biens actifs dont expire_at est dépassé */
class ExpireBiens extends CronBase
{
    public function __construct()
    {
        parent::__construct('expire-biens');
    }

    protected function run(): int
    {
        $stmt = Database::getInstance()->query(
            "UPDATE biens
             SET statut = 'expire', updated_at = NOW()
             WHERE statut = 'actif' AND expire_at < NOW()
             RETURNING id"
        );

        return count($stmt->fetchAll());
    }
}
