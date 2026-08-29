<?php

namespace Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Model\MailLog;

/**
 * En développement : connectez-vous à MailHog (SMTP_HOST=localhost, SMTP_PORT=1025)
 * pour intercepter tous les emails sans jamais en envoyer un vrai. Voir le message
 * d'installation fourni séparément.
 */
class Mailer
{
    private static ?Mailer $instance = null;

    public static function getInstance(): Mailer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param array $attachments [chemin_absolu => nom_affiché, ...]
     */
    public function send(
        string $to,
        string $subject,
        string $template,
        array $data = [],
        array $attachments = [],
        ?string $userId = null
    ): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? 'localhost';
            $mail->Port = (int)($_ENV['SMTP_PORT'] ?? 1025);

            if (!empty($_ENV['SMTP_USER'])) {
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASS'] ?? '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->setFrom($_ENV['MAIL_FROM'] ?? 'no-reply@immosn.com', 'ImmoSn.com');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = MailTemplate::render($template, $data);

            foreach ($attachments as $path => $name) {
                $mail->addAttachment($path, $name);
            }

            $mail->send();
            MailLog::create($userId, $to, $subject, $template, 'sent');
            return true;

        } catch (PHPMailerException $e) {
            MailLog::create($userId, $to, $subject, $template, 'failed', $mail->ErrorInfo);
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
