<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <main class="max-w-md mx-auto mt-16 p-6">
        <h1 class="text-2xl font-bold mb-6">Créer un compte</h1>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="nom" class="block mb-1">Nom</label>
                <input type="text" id="nom" name="nom" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="prenom" class="block mb-1">Prénom</label>
                <input type="text" id="prenom" name="prenom" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" name="email" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="password" class="block mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="role" class="block mb-1">Type de compte</label>
                <select id="role" name="role" class="w-full border rounded px-3 py-2">
                    <option value="particulier">Particulier</option>
                    <option value="agence">Agence</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                S'inscrire
            </button>
        </form>

        <p class="mt-4 text-sm">
            Déjà un compte ? <a href="/login" class="text-blue-600">Se connecter</a>
        </p>
    </main>
</body>
</html>
