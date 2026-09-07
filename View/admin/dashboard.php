<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard admin — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="flex-1 p-4 sm:p-8">
            <h1 class="text-xl font-bold mb-6">Tableau de bord</h1>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Biens en attente</p>
                    <p class="text-2xl font-bold text-primary"><?= $stats['en_attente'] ?></p>
                </div>
                <div class="bg-white border rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Biens actifs</p>
                    <p class="text-2xl font-bold text-secondary"><?= $stats['actifs'] ?></p>
                </div>
                <div class="bg-white border rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Cron aujourd'hui</p>
                    <p class="text-2xl font-bold"><?= $stats['cron_today'] ?></p>
                </div>
                <div class="bg-white border rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Emails envoyés (24h)</p>
                    <p class="text-2xl font-bold"><?= $stats['mails_24h'] ?></p>
                </div>
            </div>

            <div class="bg-white border rounded-xl p-4 sm:p-6">
                <h2 class="font-bold mb-4">Biens en attente de validation (<?= count($biensEnAttente) ?>)</h2>

                <?php if (empty($biensEnAttente)): ?>
                    <p class="text-gray-500 text-sm">Aucun bien en attente pour le moment.</p>
                <?php endif; ?>

                <?php foreach ($biensEnAttente as $bien): ?>
                    <div class="border-t py-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <p class="font-semibold"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-500">
                                <?= htmlspecialchars($bien['prenom'] . ' ' . $bien['nom'], ENT_QUOTES, 'UTF-8') ?>
                                · <?= htmlspecialchars($bien['ville_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                · <?= htmlspecialchars($bien['categorie_libelle'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                · <?= number_format($bien['prix'], 0, ',', ' ') ?> FCFA
                            </p>
                            <p class="text-sm mt-2 text-gray-700"><?= htmlspecialchars($bien['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <div class="flex gap-2 shrink-0">
                            <form method="POST" action="/admin/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/valider">
                                <?= csrf_field() ?>
                                <button type="submit" class="bg-secondary text-white px-3 py-1.5 rounded text-sm">Valider</button>
                            </form>
                            <button type="button" onclick="document.getElementById('rejet-<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>').classList.toggle('hidden')"
                                    class="bg-red-100 text-red-800 px-3 py-1.5 rounded text-sm">Rejeter</button>
                        </div>
                    </div>

                    <form method="POST" action="/admin/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/rejeter"
                          id="rejet-<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>" class="hidden pb-4 flex gap-2">
                        <?= csrf_field() ?>
                        <input type="text" name="motif_rejet" placeholder="Motif du rejet" required class="border rounded px-2 py-1 text-sm flex-1">
                        <button type="submit" class="bg-red-700 text-white px-3 py-1.5 rounded text-sm">Confirmer le rejet</button>
                    </form>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
