<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes favoris — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/_sidebar.php'; ?>

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
                        <?php if (!empty($bien['photo_principale'])): ?>
                            <img src="<?= htmlspecialchars($bien['photo_principale'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="h-32 w-full object-cover">
                        <?php else: ?>
                            <div class="h-32 bg-sand-100"></div>
                        <?php endif; ?>
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
