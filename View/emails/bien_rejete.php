<!DOCTYPE html>
<html lang="fr">
<body style="font-family: Arial, sans-serif; background:#FBF6EF; padding:32px;">
    <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden;">
        <div style="background:#2B2420; padding:24px; text-align:center;">
            <span style="color:#fff; font-size:20px; font-weight:bold;">ImmoSn<span style="color:#0F6E56;">.com</span></span>
        </div>
        <div style="padding:32px;">
            <h1 style="font-size:20px; color:#2B2420;">Votre annonce n'a pas été validée</h1>
            <p style="color:#5C5347; line-height:1.6;">
                Bonjour <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>, votre annonce
                « <?= htmlspecialchars($titreBien, ENT_QUOTES, 'UTF-8') ?> » n'a pas pu être publiée pour la raison suivante :
            </p>
            <div style="background:#FBF6EF; border-left:3px solid #C2542E; padding:12px 16px; margin:16px 0; color:#2B2420;">
                <?= htmlspecialchars($motif, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <p style="color:#5C5347; line-height:1.6;">
                Vous pouvez corriger votre annonce et la soumettre à nouveau depuis votre espace membre.
            </p>
            <a href="<?= htmlspecialchars($dashboardUrl, ENT_QUOTES, 'UTF-8') ?>"
               style="display:inline-block; margin-top:16px; background:#C2542E; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none;">
                Accéder à mon espace
            </a>
        </div>
    </div>
</body>
</html>
