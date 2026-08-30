<!DOCTYPE html>
<html lang="fr">
<body style="font-family: Arial, sans-serif; background:#FBF6EF; padding:32px;">
    <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden;">
        <div style="background:#2B2420; padding:24px; text-align:center;">
            <span style="color:#fff; font-size:20px; font-weight:bold;">ImmoSn<span style="color:#0F6E56;">.com</span></span>
        </div>
        <div style="padding:32px;">
            <h1 style="font-size:20px; color:#2B2420;">Votre annonce expire <?= htmlspecialchars($texteDelai, ENT_QUOTES, 'UTF-8') ?> ⏰</h1>
            <p style="color:#5C5347; line-height:1.6;">
                Bonjour <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>, votre annonce
                « <?= htmlspecialchars($titreBien, ENT_QUOTES, 'UTF-8') ?> » expire <?= htmlspecialchars($texteDelai, ENT_QUOTES, 'UTF-8') ?>.
                Passé ce délai, elle ne sera plus visible dans les résultats de recherche.
            </p>
            <a href="<?= htmlspecialchars($bienUrl, ENT_QUOTES, 'UTF-8') ?>"
               style="display:inline-block; margin-top:16px; background:#C2542E; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none;">
                Voir mon annonce
            </a>
        </div>
    </div>
</body>
</html>
