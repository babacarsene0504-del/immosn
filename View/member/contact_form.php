<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contacter le propriétaire — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <a href="/biens" style="color:#C2542E; font-weight:bold; text-decoration:none;">ImmoSn.com</a>
    </header>

    <main class="max-w-md mx-auto mt-10 p-4 sm:p-6">
        <h1 class="text-xl font-bold mb-2">Contacter le propriétaire</h1>
        <p class="text-sm text-gray-500 mb-6">« <?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?> »</p>

        <form method="POST" action="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/contacter" class="space-y-4">
            <?= csrf_field() ?>
            <textarea name="contenu" rows="5" required placeholder="Bonjour, ce bien est-il toujours disponible ?"
                      class="w-full border rounded px-3 py-2"></textarea>
            <button type="submit" class="w-full bg-primary text-white py-2 rounded">Envoyer le message</button>
        </form>
    </main>
</body>
</html>
