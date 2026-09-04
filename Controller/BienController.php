<?php

namespace Controller;

use Model\Bien;
use Model\Ville;
use Model\CategorieBien;
use Model\QrCode;
use Model\User;
use Model\Favori;
use Model\Photo;
use Core\Uploader;

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
        $isFavori = !empty($_SESSION['user_id']) && Favori::exists($_SESSION['user_id'], $id);
        $photos = Photo::getByBien($id);

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

        $id = Bien::create([
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

        $this->handlePhotoUploads($id);

        // La génération du QR Code intervient après validation par un modérateur (AdminController::validate)

        header('Location: /dashboard');
        exit;
    }

    /** Traite jusqu'à 10 photos envoyées avec le formulaire ; la première devient la photo principale */
    private function handlePhotoUploads(string $bienId): void
    {
        if (empty($_FILES['photos']['name'][0])) {
            return;
        }

        $destDir = __DIR__ . '/../public/storage/photos/' . $bienId;
        $count = min(count($_FILES['photos']['name']), 10);

        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $file = [
                'name'     => $_FILES['photos']['name'][$i],
                'type'     => $_FILES['photos']['type'][$i],
                'tmp_name' => $_FILES['photos']['tmp_name'][$i],
                'error'    => $_FILES['photos']['error'][$i],
                'size'     => $_FILES['photos']['size'][$i],
            ];

            try {
                $filename = Uploader::upload($file, $destDir);
                Photo::create($bienId, '/storage/photos/' . $bienId . '/' . $filename, $i === 0);
            } catch (\RuntimeException $e) {
                // Une photo invalide (format/taille) n'empêche pas la publication du bien,
                // on l'ignore simplement plutôt que de bloquer tout le formulaire.
                continue;
            }
        }
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
