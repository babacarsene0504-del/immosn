<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cron Monitor — ImmoSn.com</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans bg-sand-50 text-[#2B2420]">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="flex-1 p-4 sm:p-8">
            <h1 class="font-heading text-xl font-bold mb-6">Historique des exécutions Cron</h1>

        <div class="overflow-x-auto"><table class="w-full text-sm border-collapse min-w-[600px]">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">Script</th>
                    <th>Statut</th>
                    <th>Éléments traités</th>
                    <th>Durée</th>
                    <th>Exécuté le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr class="border-b">
                        <td class="py-2 font-mono text-xs"><?= htmlspecialchars($log['script'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php
                            $badgeClass = match ($log['status']) {
                                'success' => 'bg-secondary/10 text-secondary-dark',
                                'error'   => 'bg-red-100 text-red-800',
                                default   => 'bg-gray-100 text-gray-700',
                            };
                            ?>
                            <span class="text-xs px-2 py-0.5 rounded <?= $badgeClass ?>"><?= htmlspecialchars($log['status'], ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                        <td><?= (int)$log['items_processed'] ?></td>
                        <td><?= $log['duration_ms'] ? (int)$log['duration_ms'] . ' ms' : '—' ?></td>
                        <td class="text-gray-500"><?= htmlspecialchars($log['executed_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <?php if (!empty($log['error_message'])): ?>
                        <tr class="border-b bg-red-50">
                            <td colspan="5" class="py-1 px-2 text-xs text-red-700"><?= htmlspecialchars($log['error_message'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="py-4 text-gray-500">Aucune exécution enregistrée pour le moment.</td></tr>
                <?php endif; ?>
            </tbody>
        </table></div>
        </main>
    </div>
</body>
</html>
