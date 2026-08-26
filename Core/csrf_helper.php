<?php
// Helper CSRF — à inclure (require) au tout début de vos vues avec formulaire.
// Suppose que session_start() a déjà été appelé (fait dans index.php).

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    $token = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"{$token}\">";
}
