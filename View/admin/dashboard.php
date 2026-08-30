<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Biens à valider — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <strong style="color:#C2542E;">ImmoSn.com</strong> <span style="color:#7A6F63;"> — Espace modérateur</span>
    </header>

    <main class="max-w-5xl mx-auto mt-8 p-6">
        <?php require __DIR__ . '/_nav.php'; ?>
        <h1 class="text-xl font-bold mb-6"><?= count($biensEnAttente) ?> bien(s) en attente de validation</h1>

        <?php if (empty($biensEnAttente)): ?>
            <p class="text-gray-500">Aucun bien en attente pour le moment.</p>
        <?php endif; ?>

        <?php foreach ($biensEnAttente as $bien): ?>
            <div class="border rounded p-4 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-sm text-gray-500">
                            <?= htmlspecialchars($bien['prenom'] . ' ' . $bien['nom'], ENT_QUOTES, 'UTF-8') ?>
                            · <?= htmlspecialchars($bien['ville_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            · <?= htmlspecialchars($bien['categorie_libelle'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            · <?= number_format($bien['prix'], 0, ',', ' ') ?> FCFA
                        </p>
                        <p class="text-sm mt-2"><?= htmlspecialchars($bien['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="flex gap-2 shrink-0 ml-4">
                        <form method="POST" action="/admin/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/valider">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-emerald-700 text-white px-3 py-1.5 rounded text-sm">Valider</button>
                        </form>

                        <button type="button" onclick="document.getElementById('rejet-<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>').classList.toggle('hidden')"
                                class="bg-red-100 text-red-800 px-3 py-1.5 rounded text-sm">Rejeter</button>
                    </div>
                </div>

                <form method="POST" action="/admin/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/rejeter"
                      id="rejet-<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>" class="hidden mt-3 flex gap-2">
                    <?= csrf_field() ?>
                    <input type="text" name="motif_rejet" placeholder="Motif du rejet" required class="border rounded px-2 py-1 text-sm flex-1">
                    <button type="submit" class="bg-red-700 text-white px-3 py-1.5 rounded text-sm">Confirmer le rejet</button>
                </form>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
