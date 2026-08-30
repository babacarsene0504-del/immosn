<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mail Logs — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <strong style="color:#C2542E;">ImmoSn.com</strong> <span style="color:#7A6F63;"> — Espace modérateur</span>
    </header>

    <main class="max-w-5xl mx-auto mt-8 p-6">
        <?php require __DIR__ . '/_nav.php'; ?>

        <h1 class="text-xl font-bold mb-4">Historique des emails envoyés</h1>

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">Destinataire</th>
                    <th>Sujet</th>
                    <th>Template</th>
                    <th>Statut</th>
                    <th>Envoyé le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr class="border-b">
                        <td class="py-2"><?= htmlspecialchars($log['email_to'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($log['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="font-mono text-xs"><?= htmlspecialchars($log['template'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="text-xs px-2 py-0.5 rounded <?= $log['status'] === 'sent' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' ?>">
                                <?= htmlspecialchars($log['status'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td class="text-gray-500"><?= htmlspecialchars($log['sent_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <?php if (!empty($log['error'])): ?>
                        <tr class="border-b bg-red-50">
                            <td colspan="5" class="py-1 px-2 text-xs text-red-700"><?= htmlspecialchars($log['error'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="py-4 text-gray-500">Aucun email enregistré pour le moment.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
