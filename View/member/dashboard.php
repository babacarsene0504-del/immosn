<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon espace — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF; display:flex; justify-content:space-between; align-items:center;">
        <strong style="color:#C2542E;">ImmoSn.com</strong>
        <a href="/logout" style="font-size:13px; color:#7A6F63;">Déconnexion</a>
    </header>

    <main style="max-width:800px; margin:40px auto; padding:0 20px;">
        <h1>Bienvenue, <?= htmlspecialchars($user['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?> 👋</h1>
        <p style="color:#7A6F63;">Rôle : <?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <p>Cette page sera complétée au Sprint 2 avec la liste de vos biens publiés.</p>
    </main>
</body>
</html>
