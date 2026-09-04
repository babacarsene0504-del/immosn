<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ImmoSn.com — Trouvez votre bien immobilier au Sénégal</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-sand-50 text-[#2B2420]">

    <header class="flex items-center justify-between px-4 sm:px-8 py-4 bg-white border-b">
        <span class="text-lg font-semibold text-primary">ImmoSn<span class="text-secondary">.com</span></span>
        <nav class="hidden sm:flex gap-6 text-sm text-gray-600">
            <a href="/biens">Acheter</a>
            <a href="/biens?type_offre=location">Louer</a>
            <a href="/publier">Publier un bien</a>
        </nav>
        <div class="flex gap-2">
            <a href="/login" class="border rounded px-4 py-2 text-sm">Connexion</a>
            <a href="/register" class="bg-primary text-white rounded px-4 py-2 text-sm">Inscription</a>
        </div>
    </header>

    <section class="text-center py-14 px-4">
        <h1 class="text-2xl sm:text-4xl font-bold mb-2">Trouvez votre bien immobilier au Sénégal</h1>
        <p class="text-gray-500 mb-8">Des annonces vérifiées, un QR Code pour chaque visite, sans intermédiaire</p>

        <form method="GET" action="/biens" class="max-w-2xl mx-auto flex flex-col sm:flex-row gap-2 bg-white p-2 rounded-xl border">
            <select id="region" class="flex-1 border rounded px-3 py-2 text-sm">
                <option value="">Toutes les villes</option>
                <?php foreach (\Model\Ville::getRegions() as $region): ?>
                    <option value="<?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
            <select id="ville" name="ville_id" class="flex-1 border rounded px-3 py-2 text-sm">
                <option value="">Toutes les villes</option>
            </select>
            <select name="type_offre" class="flex-1 border rounded px-3 py-2 text-sm">
                <option value="">Vente ou location</option>
                <option value="location">Location</option>
                <option value="vente">Vente</option>
            </select>
            <button type="submit" class="bg-secondary text-white rounded px-6 py-2 text-sm font-medium">Rechercher</button>
        </form>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-8 pb-16">
        <h2 class="text-lg font-bold mb-4">Biens à la une</h2>

        <?php if (empty($biensAlaUne)): ?>
            <p class="text-gray-500">Aucun bien disponible pour le moment — revenez bientôt !</p>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($biensAlaUne as $bien): ?>
                <a href="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>" class="border rounded-lg overflow-hidden block hover:shadow bg-white">
                    <?php if (!empty($bien['photo_principale'])): ?>
                        <img src="<?= htmlspecialchars($bien['photo_principale'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="h-40 w-full object-cover">
                    <?php else: ?>
                        <div class="h-40 bg-sand-100"></div>
                    <?php endif; ?>
                    <div class="p-3">
                        <span class="text-xs px-2 py-0.5 rounded bg-secondary/10 text-secondary-dark">
                            <?= $bien['type_offre'] === 'location' ? 'Location' : 'Vente' ?>
                        </span>
                        <p class="font-semibold mt-1"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($bien['ville_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-primary font-bold mt-1">
                            <?= number_format($bien['prix'], 0, ',', ' ') ?> FCFA
                            <?= $bien['type_offre'] === 'location' ? '<span class="text-xs text-gray-500 font-normal">/mois</span>' : '' ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="bg-[#2B2420] text-[#B5AA9B] text-sm py-10 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-5 gap-8">
            <div class="col-span-2 sm:col-span-1">
                <p class="text-white font-semibold text-base mb-1">ImmoSn<span class="text-secondary">.com</span></p>
                <p class="text-xs">La plateforme immobilière de confiance au Sénégal.</p>
            </div>

            <div>
                <p class="text-white font-semibold text-xs mb-3">Navigation</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="/biens" class="hover:text-white">Acheter</a></li>
                    <li><a href="/biens?type_offre=location" class="hover:text-white">Louer</a></li>
                    <li><a href="/publier" class="hover:text-white">Publier un bien</a></li>
                </ul>
            </div>

            <div>
                <p class="text-white font-semibold text-xs mb-3">Entreprise</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white">À propos</a></li>
                    <li><a href="#" class="hover:text-white">Comment ça marche</a></li>
                    <li><a href="#" class="hover:text-white">Nous contacter</a></li>
                </ul>
            </div>

            <div>
                <p class="text-white font-semibold text-xs mb-3">Légal</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white">Conditions d'utilisation</a></li>
                    <li><a href="#" class="hover:text-white">Politique de confidentialité</a></li>
                </ul>
            </div>

            <div>
                <p class="text-white font-semibold text-xs mb-3">Suivez-nous</p>
                <div class="flex gap-2">
                    <a href="#" aria-label="Instagram" class="w-7 h-7 rounded-full bg-[#3A322B] flex items-center justify-center hover:bg-secondary transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1" fill="#fff" stroke="none"/></svg>
                    </a>
                    <a href="#" aria-label="Facebook" class="w-7 h-7 rounded-full bg-[#3A322B] flex items-center justify-center hover:bg-secondary transition">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#fff"><path d="M14 8h3V4h-3a5 5 0 00-5 5v2H6v4h3v9h4v-9h3l1-4h-4V9a1 1 0 011-1z"/></svg>
                    </a>
                    <a href="#" aria-label="TikTok" class="w-7 h-7 rounded-full bg-[#3A322B] flex items-center justify-center hover:bg-secondary transition">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#fff"><path d="M16.5 2c.3 1.9 1.6 3.3 3.5 3.6v3c-1.2 0-2.4-.4-3.5-1.1v6.4c0 3.4-2.8 6.1-6.1 6.1S4.3 17.3 4.3 13.9s2.8-6.1 6.1-6.1c.3 0 .5 0 .8.1v3.1c-.3-.1-.5-.1-.8-.1a3 3 0 100 6 3 3 0 003-3V2h3.1z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto border-t border-[#3A322B] mt-8 pt-6 text-center text-xs text-[#8A8072]">
            © 2026 ImmoSn.com — Tous droits réservés
        </div>
    </footer>

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
