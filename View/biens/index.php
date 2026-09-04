<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher un bien — ImmoSn.com</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <strong style="color:#C2542E;">ImmoSn.com</strong>
    </header>

    <main class="max-w-6xl mx-auto mt-8 p-6 grid grid-cols-1 md:grid-cols-4 gap-6">

        <form method="GET" action="/biens" class="md:col-span-1 border rounded p-4 space-y-4 h-fit">
            <h2 class="font-bold mb-2">Filtres</h2>

            <div>
                <label class="block text-sm mb-1">Région</label>
                <select id="region" class="w-full border rounded px-2 py-1">
                    <option value="">Toutes</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Ville</label>
                <select id="ville" name="ville_id" class="w-full border rounded px-2 py-1">
                    <option value="">Toutes les villes</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Catégorie</label>
                <select name="category_id" class="w-full border rounded px-2 py-1">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Type d'offre</label>
                <select name="type_offre" class="w-full border rounded px-2 py-1">
                    <option value="">Vente ou location</option>
                    <option value="location">Location</option>
                    <option value="vente">Vente</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Prix max (FCFA)</label>
                <input type="number" name="prix_max" class="w-full border rounded px-2 py-1">
            </div>

            <button type="submit" class="w-full bg-secondary text-white py-2 rounded">Rechercher</button>
        </form>

        <div class="md:col-span-3">
            <h1 class="font-heading text-xl font-bold mb-4"><?= count($biens) ?> bien(s) trouvé(s)</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($biens as $bien): ?>
                    <a href="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>" class="border rounded overflow-hidden block hover:shadow">
                        <?php if (!empty($bien['photo_principale'])): ?>
                            <img src="<?= htmlspecialchars($bien['photo_principale'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="h-40 w-full object-cover">
                        <?php else: ?>
                            <div class="h-40 bg-sand-100"></div>
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

                <?php if (empty($biens)): ?>
                    <p class="text-gray-500">Aucun bien ne correspond à votre recherche.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
    document.getElementById('region').addEventListener('change', async (e) => {
        const region = e.target.value;
        const villeSelect = document.getElementById('ville');

        if (!region) {
            villeSelect.innerHTML = '<option value="">Toutes les villes</option>';
            return;
        }

        villeSelect.innerHTML = '<option value="">Chargement...</option>';
        const response = await fetch(`/api/villes?region=${encodeURIComponent(region)}`);
        const villes = await response.json();

        villeSelect.innerHTML = '<option value="">Toutes les villes</option>';
        villes.forEach(v => {
            villeSelect.innerHTML += `<option value="${v.id}">${v.ville}</option>`;
        });
    });
    </script>
</body>
</html>
