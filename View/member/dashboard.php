<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes biens — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <aside class="w-56 bg-[#2B2420] text-white shrink-0">
            <div class="p-6">
                <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
            </div>
            <nav class="px-3 space-y-1 text-sm">
                <a href="/dashboard" class="block px-3 py-2 rounded bg-secondary/25">Mes biens</a>
                <a href="#" class="block px-3 py-2 rounded text-gray-300">Messagerie</a>
                <a href="#" class="block px-3 py-2 rounded text-gray-300">Alertes</a>
                <a href="/favoris" class="block px-3 py-2 rounded text-gray-300">Favoris</a>
                <a href="/logout" class="block px-3 py-2 rounded text-gray-300 mt-6">Déconnexion</a>
            </nav>
        </aside>

        <main class="flex-1 p-4 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold">Bienvenue, <?= htmlspecialchars($user['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?> 👋</h1>
                    <p class="text-sm text-gray-500">
                        <?= $user['role'] === 'agence' ? 'Compte agence' : 'Compte particulier' ?>
                    </p>
                </div>
                <a href="/publier" class="bg-primary text-white px-4 py-2 rounded text-sm font-medium text-center">
                    + Publier un bien
                </a>
            </div>

            <?php if (empty($mesBiens)): ?>
                <div class="border rounded p-8 text-center text-gray-500">
                    Vous n'avez encore publié aucun bien.
                    <a href="/publier" class="text-primary font-medium">Publiez votre première annonce</a>.
                </div>
            <?php endif; ?>

            <div class="space-y-3">
                <?php foreach ($mesBiens as $bien): ?>
                    <?php
                    $badge = match ($bien['statut']) {
                        'actif'      => ['bg-secondary/10 text-secondary-dark', 'Actif'],
                        'en_attente' => ['bg-amber-100 text-amber-800', 'En attente de validation'],
                        'expire'     => ['bg-sand-200 text-gray-600', 'Expiré'],
                        'rejete'     => ['bg-red-100 text-red-800', 'Rejeté'],
                        default      => ['bg-sand-200 text-gray-600', $bien['statut']],
                    };
                    ?>
                    <div class="border rounded p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-full sm:w-24 h-24 bg-sand-100 rounded shrink-0"></div>

                        <div class="flex-1">
                            <span class="text-xs px-2 py-0.5 rounded <?= $badge[0] ?>"><?= htmlspecialchars($badge[1], ENT_QUOTES, 'UTF-8') ?></span>
                            <p class="font-semibold mt-1"><?= htmlspecialchars($bien['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-500">
                                <?= (int)($bien['nb_vues'] ?? 0) ?> vue(s)
                                <?php if ($bien['statut'] === 'actif'): ?>
                                    · expire le <?= htmlspecialchars(substr($bien['expire_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?>
                                <?php elseif ($bien['statut'] === 'rejete' && !empty($bien['motif_rejet'])): ?>
                                    · motif : <?= htmlspecialchars($bien['motif_rejet'], ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if ($bien['statut'] === 'actif'): ?>
                            <a href="/biens/<?= htmlspecialchars($bien['id'], ENT_QUOTES, 'UTF-8') ?>"
                               class="border rounded px-3 py-2 text-sm text-center shrink-0">
                                Voir la fiche
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
