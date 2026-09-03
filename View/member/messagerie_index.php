<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <aside class="w-56 bg-[#2B2420] text-white shrink-0">
            <div class="p-6">
                <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
            </div>
            <nav class="px-3 space-y-1 text-sm">
                <a href="/dashboard" class="block px-3 py-2 rounded text-gray-300">Mes biens</a>
                <a href="/messagerie" class="block px-3 py-2 rounded bg-secondary/25">Messagerie</a>
                <a href="/alertes" class="block px-3 py-2 rounded text-gray-300">Alertes</a>
                <a href="/favoris" class="block px-3 py-2 rounded text-gray-300">Favoris</a>
                <a href="/logout" class="block px-3 py-2 rounded text-gray-300 mt-6">Déconnexion</a>
            </nav>
        </aside>

        <main class="flex-1 p-4 sm:p-8">
            <h1 class="text-xl font-bold mb-6">Messagerie</h1>

            <div class="space-y-2">
                <?php foreach ($conversations as $conv): ?>
                    <a href="/messagerie/<?= htmlspecialchars($conv['other_id'], ENT_QUOTES, 'UTF-8') ?>"
                       class="flex items-center gap-3 border rounded p-3 hover:bg-sand-100 <?= !$conv['lu'] && $conv['destinataire_id'] === ($_SESSION['user_id'] ?? '') ? 'border-primary' : '' ?>">
                        <div class="w-9 h-9 rounded-full bg-sand-100 flex items-center justify-center text-xs font-semibold text-primary-dark shrink-0">
                            <?= htmlspecialchars(mb_substr($conv['other_user']['prenom'] ?? '?', 0, 1) . mb_substr($conv['other_user']['nom'] ?? '', 0, 1), ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium"><?= htmlspecialchars(($conv['other_user']['prenom'] ?? '') . ' ' . ($conv['other_user']['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($conv['contenu'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($conversations)): ?>
                    <p class="text-gray-500">Aucune conversation pour l'instant.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
