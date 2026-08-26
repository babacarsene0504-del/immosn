<?php require_once __DIR__ . '/../../Core/csrf_helper.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <main class="max-w-md mx-auto mt-16 p-6">
        <h1 class="text-2xl font-bold mb-6">Connexion</h1>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="password" class="block mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                Se connecter
            </button>
        </form>

        <p class="mt-4 text-sm">
            Pas encore de compte ? <a href="/register" class="text-blue-600">S'inscrire</a>
        </p>
    </main>
</body>
</html>
