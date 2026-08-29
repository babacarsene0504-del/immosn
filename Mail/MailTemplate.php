<?php

namespace Mail;

class MailTemplate
{
    /** Charge une vue dans views/emails/{name}.php et injecte les variables de $data */
    public static function render(string $name, array $data = []): string
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../View/emails/' . $name . '.php';
        return ob_get_clean();
    }
}
