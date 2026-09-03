<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conversation avec <?= htmlspecialchars($autre['prenom'], ENT_QUOTES, 'UTF-8') ?> — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <aside class="w-56 bg-[#2B2420] text-white shrink-0 hidden sm:block">
            <div class="p-6">
                <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
            </div>
            <nav class="px-3 space-y-1 text-sm">
                <a href="/dashboard" class="block px-3 py-2 rounded text-gray-300">Mes biens</a>
                <a href="/messagerie" class="block px-3 py-2 rounded bg-secondary/25">Messagerie</a>
                <a href="/alertes" class="block px-3 py-2 rounded text-gray-300">Alertes</a>
                <a href="/favoris" class="block px-3 py-2 rounded text-gray-300">Favoris</a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col p-4 sm:p-8">
            <a href="/messagerie" class="text-sm text-primary mb-4">← Retour aux conversations</a>
            <h1 class="text-lg font-bold mb-4"><?= htmlspecialchars($autre['prenom'] . ' ' . $autre['nom'], ENT_QUOTES, 'UTF-8') ?></h1>

            <div class="flex-1 space-y-3 mb-4">
                <?php foreach ($messages as $msg): ?>
                    <?php $estMoi = $msg['expediteur_id'] === $_SESSION['user_id']; ?>
                    <div class="max-w-md <?= $estMoi ? 'ml-auto bg-secondary text-white' : 'bg-white' ?> rounded-lg p-3">
                        <?php if (!$estMoi && !empty($msg['sujet'])): ?>
                            <p class="text-xs font-semibold mb-1 opacity-75"><?= htmlspecialchars($msg['sujet'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                        <p class="text-sm"><?= nl2br(htmlspecialchars($msg['contenu'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="POST" action="/messagerie/<?= htmlspecialchars($autre['id'], ENT_QUOTES, 'UTF-8') ?>" class="flex gap-2">
                <?= csrf_field() ?>
                <input type="text" name="contenu" required placeholder="Écrire un message..."
                       class="flex-1 border rounded-full px-4 py-2 text-sm">
                <button type="submit" class="bg-primary text-white px-5 py-2 rounded-full text-sm">Envoyer</button>
            </form>
        </main>
    </div>
</body>
</html>
