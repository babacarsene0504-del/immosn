<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?> — ImmoSn.com</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <a href="/biens" style="color:#C2542E; font-weight:bold; text-decoration:none;">ImmoSn.com</a>
    </header>

    <main class="max-w-5xl mx-auto mt-8 p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
            <div class="h-64 bg-sand-100 rounded mb-4"></div>

            <div class="flex flex-col sm:flex-row sm:justify-between items-start gap-2">
                <div>
                    <span class="text-xs px-2 py-1 rounded bg-secondary/10 text-secondary-dark">
                        <?= $bien['type_offre'] === 'location' ? 'Location' : 'Vente' ?>
                    </span>
                    <h1 class="font-heading text-2xl font-bold mt-2"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="text-gray-500"><?= htmlspecialchars($bien['ville_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <p class="text-2xl font-bold text-primary whitespace-nowrap">
                    <?= number_format($bien['prix'], 0, ',', ' ') ?> FCFA
                    <?= $bien['type_offre'] === 'location' ? '<span class="text-sm text-gray-500 font-normal">/mois</span>' : '' ?>
                </p>
            </div>

            <div class="flex gap-6 py-4 border-y my-4 text-sm text-gray-600">
                <?php if (!empty($bien['chambres'])): ?><span><?= (int)$bien['chambres'] ?> chambres</span><?php endif; ?>
                <?php if (!empty($bien['salles_de_bain'])): ?><span><?= (int)$bien['salles_de_bain'] ?> salles de bain</span><?php endif; ?>
                <span><?= htmlspecialchars($bien['superficie'], ENT_QUOTES, 'UTF-8') ?> m²</span>
                <span><?= htmlspecialchars($bien['categorie_libelle'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <h2 class="font-bold mb-2">Description</h2>
            <p class="text-gray-700"><?= nl2br(htmlspecialchars($bien['description'], ENT_QUOTES, 'UTF-8')) ?></p>

            <?php if (!empty($similaires)): ?>
                <h2 class="font-bold mt-8 mb-4">Biens similaires</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <?php foreach ($similaires as $s): ?>
                        <a href="/biens/<?= htmlspecialchars($s['id'], ENT_QUOTES, 'UTF-8') ?>" class="border rounded overflow-hidden block">
                            <div class="h-24 bg-sand-100"></div>
                            <div class="p-2">
                                <p class="text-sm font-medium truncate"><?= htmlspecialchars($s['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="text-primary text-sm font-bold"><?= number_format($s['prix'], 0, ',', ' ') ?> FCFA</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="lg:col-span-1 space-y-4">
            <?php if ($qrCode): ?>
                <div class="border rounded p-4 text-center">
                    <img src="<?= htmlspecialchars($qrCode['file_path'], ENT_QUOTES, 'UTF-8') ?>"
                         alt="QR Code du bien" class="w-full mb-3">
                    <p class="text-xs text-gray-500 mb-3">Scannez pour retrouver ce bien lors d'une visite</p>
                    <a href="<?= htmlspecialchars($qrCode['file_path'], ENT_QUOTES, 'UTF-8') ?>" download
                       class="block border rounded py-2 text-sm">
                        Télécharger le QR Code
                    </a>
                </div>
            <?php else: ?>
                <div class="border rounded p-4 text-center text-sm text-gray-500">
                    QR Code non disponible pour ce bien.
                </div>
            <?php endif; ?>

            <?php if ($proprietaire): ?>
                <div class="border rounded p-4">
                    <p class="font-semibold"><?= htmlspecialchars($proprietaire['prenom'] . ' ' . $proprietaire['nom'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-xs text-gray-500 mb-3">
                        <?= $proprietaire['role'] === 'agence' ? 'Agence' : 'Particulier' ?>
                    </p>
                    <button class="w-full bg-primary text-white py-2 rounded text-sm mb-2">
                        Contacter le propriétaire
                    </button>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <form method="POST" action="/favoris/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>/toggle">
                            <?php require_once __DIR__ . '/../../Core/csrf_helper.php'; echo csrf_field(); ?>
                            <input type="hidden" name="redirect" value="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="w-full border py-2 rounded text-sm <?= $isFavori ? 'bg-primary/10 border-primary text-primary' : '' ?>">
                                <?= $isFavori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="/login" class="block text-center w-full border py-2 rounded text-sm">
                            Se connecter pour ajouter aux favoris
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
