<!DOCTYPE html>
<html lang="fr">
<body style="font-family: Arial, sans-serif; background:#FBF6EF; padding:32px;">
    <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden;">
        <div style="background:#2B2420; padding:24px; text-align:center;">
            <span style="color:#fff; font-size:20px; font-weight:bold;">ImmoSn<span style="color:#0F6E56;">.com</span></span>
        </div>
        <div style="padding:32px;">
            <h1 style="font-size:20px; color:#2B2420;">Un bien correspond à votre alerte 🔔</h1>
            <p style="color:#5C5347; line-height:1.6;">
                Bonjour <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>, un nouveau bien vient d'être publié et correspond à l'une de vos alertes :
            </p>
            <div style="background:#FBF6EF; border-radius:8px; padding:16px; margin:16px 0;">
                <p style="margin:0; font-weight:bold; color:#2B2420;"><?= htmlspecialchars($titreBien, ENT_QUOTES, 'UTF-8') ?></p>
                <p style="margin:4px 0 0; color:#C2542E; font-weight:bold;"><?= htmlspecialchars($prixFormatte, ENT_QUOTES, 'UTF-8') ?> FCFA</p>
            </div>
            <a href="<?= htmlspecialchars($bienUrl, ENT_QUOTES, 'UTF-8') ?>"
               style="display:inline-block; background:#C2542E; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none;">
                Voir ce bien
            </a>
        </div>
    </div>
</body>
</html>
