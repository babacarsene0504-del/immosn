<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes favoris — ImmoSn.com</title>
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
                <a href="/messagerie" class="block px-3 py-2 rounded text-gray-300">Messagerie</a>
                <a href="/alertes" class="block px-3 py-2 rounded text-gray-300">Alertes</a>
                <a href="/favoris" class="block px-3 py-2 rounded bg-secondary/25">Favoris</a>
                <a href="/logout" class="block px-3 py-2 rounded text-gray-300 mt-6">Déconnexion</a>
            </nav>
        </aside>

        <main class="flex-1 p-4 sm:p-8">
            <h1 class="text-xl font-bold mb-6"><?= count($favoris) ?> bien(s) en favoris</h1>

            <?php if (empty($favoris)): ?>
                <div class="border rounded p-8 text-center text-gray-500">
                    Vous n'avez pas encore ajouté de bien à vos favoris.
                    <a href="/biens" class="text-primary font-medium">Parcourir les biens</a>.
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($favoris as $bien): ?>
                    <a href="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>" class="border rounded overflow-hidden block hover:shadow">
                        <div class="h-32 bg-sand-100"></div>
                        <div class="p-3">
                            <p class="text-xs inline-block px-2 py-0.5 rounded bg-secondary/10 text-secondary-dark">
                                <?= $bien['type_offre'] === 'location' ? 'Location' : 'Vente' ?>
                            </p>
                            <p class="font-semibold mt-1"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($bien['ville_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-primary font-bold mt-1"><?= number_format($bien['prix'], 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
