<?php

namespace Controller;

use Model\Favori;

class FavoriController
{
    /** POST /favoris/:bienId/toggle */
    public function toggle(string $bienId): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        Favori::toggle($_SESSION['user_id'], $bienId);

        $redirect = $_POST['redirect'] ?? ('/biens/' . $bienId);
        header('Location: ' . $redirect);
        exit;
    }

    /** GET /favoris — liste des biens favoris de l'utilisateur connecté */
    public function index(): void
    {
        $this->requireAuth();

        $favoris = Favori::getByUser($_SESSION['user_id']);

        require __DIR__ . '/../View/member/favoris.php';
    }

    private function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
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
