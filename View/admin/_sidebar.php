<?php $currentPath = $_SERVER['REQUEST_URI']; ?>
<aside class="w-56 bg-[#2B2420] text-white shrink-0 min-h-screen">
    <div class="p-6">
        <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
        <p class="text-xs text-gray-400 mt-1">Espace modérateur</p>
    </div>
    <nav class="px-3 space-y-1 text-sm">
        <a href="/admin" class="block px-3 py-2 rounded <?= $currentPath === '/admin' ? 'bg-secondary/25' : 'text-gray-300' ?>">
            Biens à valider
        </a>
        <a href="/admin/cron" class="block px-3 py-2 rounded <?= str_starts_with($currentPath, '/admin/cron') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            Cron Monitor
        </a>
        <a href="/admin/mails" class="block px-3 py-2 rounded <?= str_starts_with($currentPath, '/admin/mails') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            Mail Logs
        </a>
        <a href="/" class="block px-3 py-2 rounded text-gray-300 mt-6">Retour au site</a>
        <a href="/logout" class="block px-3 py-2 rounded text-gray-300">Déconnexion</a>
    </nav>
</aside>
