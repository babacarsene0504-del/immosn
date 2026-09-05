<?php

namespace Controller;

use Model\Message;
use Model\User;
use Model\Bien;

class MessageController
{
    /** GET /messagerie — liste des conversations */
    public function index(): void
    {
        $this->requireAuth();
        $conversations = Message::getConversations($_SESSION['user_id']);
        require __DIR__ . '/../View/member/messagerie_index.php';
    }

    /** GET /messagerie/:userId — fil de discussion avec un correspondant */
    public function thread(string $otherUserId): void
    {
        $this->requireAuth();

        $autre = User::findById($otherUserId);
        if (!$autre) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }

        Message::markThreadRead($_SESSION['user_id'], $otherUserId);
        $messages = Message::getThread($_SESSION['user_id'], $otherUserId);

        require __DIR__ . '/../View/member/messagerie_thread.php';
    }

    /** POST /messagerie/:userId — répondre dans un fil existant */
    public function reply(string $otherUserId): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        $contenu = trim($_POST['contenu'] ?? '');
        $bienId = Message::getLastBienId($_SESSION['user_id'], $otherUserId);

        if ($contenu !== '' && $bienId) {
            Message::create($bienId, $_SESSION['user_id'], $otherUserId, 'Re: conversation', $contenu);
        } elseif ($contenu !== '' && !$bienId) {
            // bien_id est obligatoire en base : une conversation ne peut démarrer
            // que depuis la fiche d'un bien (bouton "Contacter le propriétaire").
            $_SESSION['errors'] = ['message' => "Impossible de démarrer une conversation ici — contactez d'abord ce propriétaire depuis la fiche d'un bien."];
        }

        header('Location: /messagerie/' . $otherUserId);
        exit;
    }

    /** GET /biens/:id/contacter — formulaire de premier contact depuis une fiche bien */
    public function contactForm(string $bienId): void
    {
        $this->requireAuth();

        $bien = Bien::findById($bienId);
        if (!$bien) {
            http_response_code(404);
            echo "Bien introuvable.";
            return;
        }

        if ($bien['user_id'] === $_SESSION['user_id']) {
            header('Location: /biens/' . $bienId);
            exit;
        }

        require __DIR__ . '/../View/member/contact_form.php';
    }

    /** POST /biens/:id/contacter */
    public function contactSend(string $bienId): void
    {
        $this->requireAuth();
        $this->checkCsrf();

        $bien = Bien::findById($bienId);
        if (!$bien) {
            http_response_code(404);
            echo "Bien introuvable.";
            return;
        }

        $contenu = trim($_POST['contenu'] ?? '');
        if ($contenu !== '') {
            Message::create(
                $bienId,
                $_SESSION['user_id'],
                $bien['user_id'],
                'Question à propos de : ' . $bien['titre'],
                $contenu
            );
        }

        header('Location: /messagerie/' . $bien['user_id']);
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
