<?php

namespace Controller;

use Model\User;
use Mail\Mailer;

class AuthController
{
    /** Affiche le formulaire d'inscription (GET /register) */
    public function showRegister(): void
    {
        require __DIR__ . '/../View/auth/register.php';
    }

    /** Traite l'inscription (POST /register) */
    public function register(): void
    {
        $this->checkCsrf();

        $nom       = trim($_POST['nom'] ?? '');
        $prenom    = trim($_POST['prenom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $telephone = trim($_POST['telephone'] ?? '');
        $ville     = trim($_POST['ville'] ?? '');
        $role      = $_POST['role'] ?? 'particulier';

        $errors = $this->validateRegistration($nom, $prenom, $email, $password);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /register');
            exit;
        }

        if (User::emailExists($email)) {
            $_SESSION['errors'] = ['email' => 'Cet email est déjà utilisé.'];
            header('Location: /register');
            exit;
        }

        $userId = User::create([
            'nom'       => $nom,
            'prenom'    => $prenom,
            'email'     => $email,
            'password'  => $password,
            'telephone' => $telephone ?: null,
            'ville'     => $ville ?: null,
            'role'      => $role,
        ]);

        $loginUrl = ($_SERVER['HTTPS'] ?? '') === 'on' ? 'https://' : 'http://';
        $loginUrl .= $_SERVER['HTTP_HOST'] . '/login';

        Mailer::getInstance()->send(
            $email,
            'Bienvenue sur ImmoSn.com',
            'bienvenue',
            ['prenom' => $prenom, 'role' => $role, 'loginUrl' => $loginUrl],
            userId: $userId
        );

        $this->loginUser($userId, $email, $role);

        header('Location: /dashboard');
        exit;
    }

    /** Affiche le formulaire de connexion (GET /login) */
    public function showLogin(): void
    {
        require __DIR__ . '/../View/auth/login.php';
    }

    /** Traite la connexion (POST /login) */
    public function login(): void
    {
        $this->checkCsrf();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            $_SESSION['errors'] = ['login' => 'Email ou mot de passe incorrect.'];
            header('Location: /login');
            exit;
        }

        if ($user['status'] !== 'actif') {
            $_SESSION['errors'] = ['login' => 'Ce compte est suspendu.'];
            header('Location: /login');
            exit;
        }

        $this->loginUser($user['id'], $user['email'], $user['role']);

        header('Location: /dashboard');
        exit;
    }

    /** Déconnexion (GET /logout) */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function loginUser(string $id, string $email, string $role): void
    {
        session_regenerate_id(true); // empêche la fixation de session
        $_SESSION['user_id'] = $id;
        $_SESSION['email']   = $email;
        $_SESSION['role']    = $role;
    }

    private function validateRegistration(string $nom, string $prenom, string $email, string $password): array
    {
        $errors = [];

        if ($nom === '')      $errors['nom'] = 'Le nom est requis.';
        if ($prenom === '')   $errors['prenom'] = 'Le prénom est requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        return $errors;
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
