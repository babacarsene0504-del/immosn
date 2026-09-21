<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes alertes — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="flex-1 p-4 sm:p-8">
            <h1 class="text-xl font-bold mb-6">Mes alertes</h1>

            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/alertes" class="border rounded p-4 mb-6 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm mb-1">Région</label>
                    <select id="region" class="w-full border rounded px-2 py-2">
                        <option value="">Toutes</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Ville</label>
                    <select id="ville" name="ville_id" class="w-full border rounded px-2 py-2">
                        <option value="">Toutes les villes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Catégorie</label>
                    <select name="category_id" class="w-full border rounded px-2 py-2">
                        <option value="">Toutes</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Budget max (FCFA)</label>
                    <input type="number" name="budget_max" required class="w-full border rounded px-2 py-2">
                </div>
                <div class="sm:col-span-4">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded text-sm">Créer l'alerte</button>
                </div>
            </form>

            <div class="space-y-3">
                <?php foreach ($alertes as $alerte): ?>
                    <div class="border rounded p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="font-medium">
                                <?= htmlspecialchars($alerte['categorie_libelle'] ?? 'Toutes catégories', ENT_QUOTES, 'UTF-8') ?>
                                · <?= htmlspecialchars($alerte['ville_nom'] ?? 'Toutes villes', ENT_QUOTES, 'UTF-8') ?>
                                · max <?= number_format($alerte['budget_max'], 0, ',', ' ') ?> FCFA
                            </p>
                            <p class="text-sm text-gray-500">Créée le <?= htmlspecialchars(substr($alerte['created_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <form method="POST" action="/alertes/<?= htmlspecialchars($alerte['id'], ENT_QUOTES, 'UTF-8') ?>/toggle">
                            <?= csrf_field() ?>
                            <input type="hidden" name="active" value="<?= $alerte['is_active'] ? '0' : '1' ?>">
                            <button type="submit" class="text-xs px-3 py-1.5 rounded <?= $alerte['is_active'] ? 'bg-secondary/10 text-secondary-dark' : 'bg-sand-200 text-gray-600' ?>">
                                <?= $alerte['is_active'] ? 'Active — désactiver' : 'Inactive — activer' ?>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($alertes)): ?>
                    <p class="text-gray-500">Aucune alerte pour l'instant.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('region').addEventListener('change', async (e) => {
        const region = e.target.value;
        const villeSelect = document.getElementById('ville');
        if (!region) { villeSelect.innerHTML = '<option value="">Toutes les villes</option>'; return; }
        villeSelect.innerHTML = '<option value="">Chargement...</option>';
        const response = await fetch(`/api/villes?region=${encodeURIComponent(region)}`);
        const villes = await response.json();
        villeSelect.innerHTML = '<option value="">Toutes les villes</option>';
        villes.forEach(v => { villeSelect.innerHTML += `<option value="${v.id}">${v.ville}</option>`; });
    });
    </script>
</body>
</html>
