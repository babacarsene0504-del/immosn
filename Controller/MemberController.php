<?php

namespace Controller;

use Model\User;
use Model\Bien;

class MemberController
{
    /** GET /dashboard — page d'accueil de l'espace membre après connexion */
    public function dashboard(): void
    {
        $this->requireAuth();

        $user = User::findById($_SESSION['user_id']);
        $mesBiens = Bien::getByUser($_SESSION['user_id']);

        require __DIR__ . '/../View/member/dashboard.php';
    }

    /** Bloque l'accès si l'utilisateur n'est pas connecté */
    private function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
