<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publier un bien — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <strong style="color:#C2542E;">ImmoSn.com</strong>
    </header>

    <main class="max-w-2xl mx-auto mt-10 p-4 sm:p-6">
        <h1 class="text-2xl font-bold mb-6">Publier un bien</h1>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/publier" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block mb-1">Titre de l'annonce</label>
                <input type="text" name="titre" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1">Description</label>
                <textarea name="description" rows="4" required class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Type d'offre</label>
                    <select name="type_offre" required class="w-full border rounded px-3 py-2">
                        <option value="location">Location</option>
                        <option value="vente">Vente</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Prix (FCFA)</label>
                    <input type="number" name="prix" min="0" required class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Région</label>
                    <select id="region" class="w-full border rounded px-3 py-2">
                        <option value="">Choisir une région</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">Ville</label>
                    <select id="ville" name="ville_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Choisissez d'abord une région</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-1">Catégorie</label>
                <select name="category_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1">Superficie (m²)</label>
                    <input type="number" name="superficie" min="0" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1">Chambres</label>
                    <input type="number" name="chambres" min="0" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1">Salles de bain</label>
                    <input type="number" name="salles_de_bain" min="0" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <p class="text-sm text-gray-500">
                L'upload des photos et la génération du QR Code arriveront après validation par un modérateur (Sprint 3).
            </p>

            <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded">
                Publier le bien
            </button>
        </form>
    </main>

    <script>
    document.getElementById('region').addEventListener('change', async (e) => {
        const region = e.target.value;
        const villeSelect = document.getElementById('ville');

        if (!region) {
            villeSelect.innerHTML = '<option value="">Choisissez d\'abord une région</option>';
            return;
        }

        villeSelect.innerHTML = '<option value="">Chargement...</option>';
        const response = await fetch(`/api/villes?region=${encodeURIComponent(region)}`);
        const villes = await response.json();

        villeSelect.innerHTML = '<option value="">Choisir une ville</option>';
        villes.forEach(v => {
            villeSelect.innerHTML += `<option value="${v.id}">${v.ville}</option>`;
        });
    });
    </script>
</body>
</html>
