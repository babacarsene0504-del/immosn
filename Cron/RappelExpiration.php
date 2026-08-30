<?php

namespace Cron;

use Database\Database;
use Model\User;
use Mail\Mailer;

/** Envoie un rappel par email aux propriétaires dont le bien expire dans 7 jours ou demain */
class RappelExpiration extends CronBase
{
    public function __construct()
    {
        parent::__construct('rappel-expiration');
    }

    protected function run(): int
    {
        $count = 0;
        $count += $this->envoyerRappels(7, 'dans 7 jours');
        $count += $this->envoyerRappels(1, 'demain');
        return $count;
    }

    private function envoyerRappels(int $joursAvant, string $texteDelai): int
    {
        $stmt = Database::getInstance()->query(
            "SELECT * FROM biens
             WHERE statut = 'actif'
               AND expire_at::date = (CURRENT_DATE + ?::integer)",
            [$joursAvant]
        );
        $biens = $stmt->fetchAll();

        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';

        foreach ($biens as $bien) {
            $proprietaire = User::findById($bien['user_id']);
            if (!$proprietaire) {
                continue;
            }

            Mailer::getInstance()->send(
                $proprietaire['email'],
                "Votre bien expire {$texteDelai}",
                'rappel_expiration',
                [
                    'prenom'    => $proprietaire['prenom'],
                    'titreBien' => $bien['titre'],
                    'texteDelai' => $texteDelai,
                    'bienUrl'   => $baseUrl . '/biens/' . $bien['id'],
                ],
                userId: $proprietaire['id']
            );
        }

        return count($biens);
    }
}
