<?php

namespace Controller;

use Model\Alerte;
use Model\Ville;
use Model\CategorieBien;

class AlerteController
{
    /** GET /alertes */
    public function index(): void
    {
        $this->requireAuth();

        $alertes = Alerte::getByUser($_SESSION['user_id']);
        $regions = Ville::getRegions();
        $categories = CategorieBien::all();

        require __DIR__ . '/../View/member/alertes.php';
    }

    /** POST /alertes */
    public function store(): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        $budgetMax = $_POST['budget_max'] ?? null;
        if (!is_numeric($budgetMax) || $budgetMax <= 0) {
            $_SESSION['errors'] = ['budget_max' => 'Indiquez un budget valide.'];
            header('Location: /alertes');
            exit;
        }

        Alerte::create(
            $_SESSION['user_id'],
            !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
            !empty($_POST['ville_id']) ? (int) $_POST['ville_id'] : null,
            (float) $budgetMax
        );

        header('Location: /alertes');
        exit;
    }

    /** POST /alertes/:id/toggle */
    public function toggle(string $id): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        $active = ($_POST['active'] ?? '0') === '1';
        Alerte::setActive($id, $_SESSION['user_id'], $active);

        header('Location: /alertes');
        exit;
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
