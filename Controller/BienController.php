<?php

namespace Controller;

use Model\Bien;
use Model\Ville;
use Model\CategorieBien;
use Model\QrCode;
use Model\User;

class BienController
{
    /** GET /biens — recherche + liste des biens actifs */
    public function index(): void
    {
        $filters = array_filter([
            'ville_id'     => $_GET['ville_id'] ?? null,
            'category_id'  => $_GET['category_id'] ?? null,
            'type_offre'   => $_GET['type_offre'] ?? null,
            'prix_max'     => $_GET['prix_max'] ?? null,
            'chambres_min' => $_GET['chambres_min'] ?? null,
        ], fn($v) => $v !== null && $v !== '');

        $biens = Bien::search($filters);
        $regions = Ville::getRegions();
        $categories = CategorieBien::all();

        require __DIR__ . '/../View/biens/index.php';
    }

    /** GET /biens/:id — fiche détaillée d'un bien */
    public function show(string $id): void
    {
        $bien = Bien::findById($id);

        if (!$bien) {
            http_response_code(404);
            echo "Ce bien n'existe pas ou plus.";
            return;
        }

        Bien::incrementViews($id);
        $similaires = Bien::getSimilaires($id);
        $qrCode = QrCode::findByBienId($id);
        $proprietaire = User::findById($bien['user_id']);

        require __DIR__ . '/../View/biens/show.php';
    }

    /** GET /publier — formulaire de publication */
    public function showCreate(): void
    {
        $this->requireAuth();

        $regions = Ville::getRegions();
        $categories = CategorieBien::all();

        require __DIR__ . '/../View/biens/create.php';
    }

    /** POST /publier — traite le formulaire */
    public function store(): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /publier');
            exit;
        }

        // Chaque bien expire 90 jours après sa publication — colonne NOT NULL en base
        $expireAt = date('Y-m-d H:i:s', strtotime('+90 days'));

        Bien::create([
            'user_id'        => $_SESSION['user_id'],
            'category_id'    => $_POST['category_id'],
            'ville_id'       => $_POST['ville_id'],
            'titre'          => trim($_POST['titre']),
            'description'    => trim($_POST['description']),
            'type_offre'     => $_POST['type_offre'],
            'prix'           => $_POST['prix'],
            'superficie'     => $_POST['superficie'],
            'pieces'         => $_POST['pieces'] ?: null,
            'chambres'       => $_POST['chambres'] ?: null,
            'salles_de_bain' => $_POST['salles_de_bain'] ?: null,
            'expire_at'      => $expireAt,
        ]);

        // NOTE Sprint suivant : upload des photos (table `photos`) et génération
        // du QR Code n'interviennent qu'après validation par un modérateur — pas ici.

        header('Location: /dashboard');
        exit;
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['titre'])) $errors['titre'] = 'Le titre est requis.';
        if (empty($data['description'])) $errors['description'] = 'La description est requise.';
        if (empty($data['category_id'])) $errors['category_id'] = 'Choisissez une catégorie.';
        if (empty($data['ville_id'])) $errors['ville_id'] = 'Choisissez une ville.';
        if (empty($data['type_offre'])) $errors['type_offre'] = 'Choisissez vente ou location.';
        if (!is_numeric($data['prix'] ?? null) || $data['prix'] <= 0) $errors['prix'] = 'Prix invalide.';
        if (!is_numeric($data['superficie'] ?? null) || $data['superficie'] <= 0) $errors['superficie'] = 'Superficie invalide.';
        return $errors;
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
