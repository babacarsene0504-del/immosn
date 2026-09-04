<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-sand-50 text-[#2B2420]">
    <div class="min-h-screen flex">

        <!-- Panneau de marque, masqué sur mobile -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#2B2420] text-white p-12 relative overflow-hidden flex-col justify-center">
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-[#3A322B]"></div>
            <div class="absolute -top-16 right-0 w-56 h-56 rounded-full bg-[#3A322B] flex items-center justify-center">
                <svg width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.2" opacity="0.15">
                    <path d="M3 11l9-8 9 8"/><path d="M5 10v10h5v-6h4v6h5V10"/>
                </svg>
            </div>

            <p class="text-xl font-semibold mb-10 relative z-10">ImmoSn<span class="text-secondary">.com</span></p>
            <h1 class="text-3xl font-bold leading-tight mb-4 relative z-10">Trouvez votre<br>bien au Sénégal</h1>
            <p class="text-sm text-gray-300 mb-8 relative z-10 max-w-xs">
                Annonces vérifiées, QR Code par bien, contact direct sans intermédiaire.
            </p>
            <ul class="space-y-3 text-sm relative z-10">
                <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Milliers d'annonces à Dakar et régions</li>
                <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Agences vérifiées (badge NINEA)</li>
            </ul>
        </div>

        <!-- Formulaire -->
        <div class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                <h2 class="text-2xl font-bold text-center mb-1">Connexion</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Accédez à votre espace</p>

                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endforeach; unset($_SESSION['errors']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/login" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label for="email" class="block mb-1 text-sm">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full border rounded-lg px-3 py-2.5">
                    </div>

                    <div>
                        <label for="password" class="block mb-1 text-sm">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                               class="w-full border rounded-lg px-3 py-2.5">
                    </div>

                    <div class="text-right">
                        <a href="#" class="text-xs text-secondary">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-medium">
                        Se connecter
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Pas encore de compte ? <a href="/register" class="text-secondary font-medium">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
