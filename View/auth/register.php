<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription — ImmoSn.com</title>
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
            <h1 class="text-3xl font-bold leading-tight mb-4 relative z-10">Rejoignez la<br>communauté ImmoSn</h1>
            <p class="text-sm text-gray-300 relative z-10 max-w-xs">
                Publiez vos biens, générez un QR Code unique, gérez vos visites facilement.
            </p>
        </div>

        <!-- Formulaire -->
        <div class="flex-1 flex items-center justify-center p-6 py-10">
            <div class="w-full max-w-sm">
                <h2 class="text-2xl font-bold text-center mb-1">Créer un compte</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Gratuit, en moins d'une minute</p>

                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endforeach; unset($_SESSION['errors']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register" class="space-y-4">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-2 gap-2 bg-sand-100 rounded-full p-1 text-sm text-center">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="particulier" class="peer sr-only" checked>
                            <span class="block py-2 rounded-full peer-checked:bg-secondary peer-checked:text-white transition">Particulier</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="agence" class="peer sr-only">
                            <span class="block py-2 rounded-full peer-checked:bg-secondary peer-checked:text-white transition">Agence</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-sm">Nom</label>
                            <input type="text" name="nom" required class="w-full border rounded-lg px-3 py-2.5">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm">Prénom</label>
                            <input type="text" name="prenom" required class="w-full border rounded-lg px-3 py-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Email</label>
                        <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2.5">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Téléphone</label>
                        <input type="tel" name="telephone" class="w-full border rounded-lg px-3 py-2.5">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Mot de passe</label>
                        <input type="password" name="password" required minlength="8" class="w-full border rounded-lg px-3 py-2.5">
                        <p class="text-xs text-gray-500 mt-1">8 caractères minimum</p>
                    </div>

                    <label class="flex items-start gap-2 text-xs text-gray-600">
                        <input type="checkbox" required class="mt-0.5">
                        J'accepte les conditions d'utilisation
                    </label>

                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-medium">
                        Créer mon compte
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Déjà un compte ? <a href="/login" class="text-secondary font-medium">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
