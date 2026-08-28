<?php

namespace Controller;

use Model\Bien;
use Core\QrGenerator;

class AdminController
{
    /** GET /admin — liste des biens en attente de validation */
    public function index(): void
    {
        $this->requireModerateur();

        $biensEnAttente = Bien::getByStatut('en_attente');

        require __DIR__ . '/../View/admin/dashboard.php';
    }

    /** POST /admin/biens/:id/valider */
    public function validate(string $id): void
    {
        $this->requireModerateur();
        $this->checkCsrf();

        Bien::updateStatut($id, 'actif');

        // Génération du QR Code au moment précis de la validation
        $baseUrl = ($_SERVER['HTTPS'] ?? '') === 'on' ? 'https://' : 'http://';
        $baseUrl .= $_SERVER['HTTP_HOST'];
        QrGenerator::generateForBien($id, $baseUrl);

        // Sprint 5 : email de confirmation avec le QR Code en pièce jointe (Mailer)

        header('Location: /admin');
        exit;
    }

    /** POST /admin/biens/:id/rejeter */
    public function reject(string $id): void
    {
        $this->requireModerateur();
        $this->checkCsrf();

        $motif = trim($_POST['motif_rejet'] ?? '');
        Bien::updateStatut($id, 'rejete', $motif ?: null);

        header('Location: /admin');
        exit;
    }

    private function requireModerateur(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $role = $_SESSION['role'] ?? '';
        if (!in_array($role, ['moderateur', 'admin'], true)) {
            http_response_code(403);
            exit("Accès réservé aux modérateurs.");
        }
    }

    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            exit('Session expirée, veuillez réessayer.');
        }
    }
}
