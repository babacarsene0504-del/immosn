<?php

namespace Controller;

use Model\Bien;
use Model\CronLog;
use Model\MailLog;
use Core\QrGenerator;
use Mail\Mailer;
use Model\User;

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

        // Email de confirmation au propriétaire, avec le QR Code en pièce jointe
        $bien = Bien::findById($id);
        $proprietaire = User::findById($bien['user_id']);
        $qr = \Model\QrCode::findByBienId($id);

        if ($proprietaire && $qr) {
            $absoluteQrPath = __DIR__ . '/../public' . $qr['file_path'];
            Mailer::getInstance()->send(
                $proprietaire['email'],
                'Votre bien est en ligne sur ImmoSn.com',
                'bien_valide',
                [
                    'prenom'   => $proprietaire['prenom'],
                    'titreBien' => $bien['titre'],
                    'bienUrl'  => $baseUrl . '/biens/' . $id,
                ],
                is_file($absoluteQrPath) ? [$absoluteQrPath => 'qrcode-bien.png'] : [],
                $proprietaire['id']
            );
        }

        header('Location: /admin');
        exit;
    }

    /** POST /admin/biens/:id/rejeter */
    public function reject(string $id): void
    {
        $this->requireModerateur();
        $this->checkCsrf();

        $motif = trim($_POST['motif_rejet'] ?? '');
        $bien = Bien::findById($id); // récupéré AVANT updateStatut, pour garder le titre

        Bien::updateStatut($id, 'rejete', $motif ?: null);

        $proprietaire = User::findById($bien['user_id']);
        if ($proprietaire) {
            $dashboardUrl = ($_SERVER['HTTPS'] ?? '') === 'on' ? 'https://' : 'http://';
            $dashboardUrl .= $_SERVER['HTTP_HOST'] . '/dashboard';

            Mailer::getInstance()->send(
                $proprietaire['email'],
                "Votre annonce ImmoSn.com n'a pas été validée",
                'bien_rejete',
                [
                    'prenom'       => $proprietaire['prenom'],
                    'titreBien'    => $bien['titre'],
                    'motif'        => $motif ?: 'Non précisé',
                    'dashboardUrl' => $dashboardUrl,
                ],
                userId: $proprietaire['id']
            );
        }

        header('Location: /admin');
        exit;
    }

    /** GET /admin/cron — historique des exécutions Cron */
    public function cronMonitor(): void
    {
        $this->requireModerateur();
        $logs = CronLog::recent(50);
        require __DIR__ . '/../View/admin/cron_monitor.php';
    }

    /** GET /admin/mails — historique des emails envoyés */
    public function mailLogs(): void
    {
        $this->requireModerateur();
        $logs = MailLog::recent(50);
        require __DIR__ . '/../View/admin/mail_logs.php';
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
