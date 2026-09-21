<?php $currentPath = $_SERVER['REQUEST_URI']; ?>
<aside class="w-56 bg-[#2B2420] text-white shrink-0 min-h-screen">
    <div class="p-6">
        <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
        <p class="text-xs text-gray-400 mt-1">Espace modérateur</p>
    </div>
    <nav class="px-3 space-y-1 text-sm">
        <a href="/admin" class="flex items-center gap-2.5 px-3 py-2 rounded <?= $currentPath === '/admin' ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
            Biens à valider
        </a>
        <a href="/admin/cron" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/admin/cron') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            Cron Monitor
        </a>
        <a href="/admin/mails" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/admin/mails') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
            Mail Logs
        </a>
        <a href="/" class="flex items-center gap-2.5 px-3 py-2 rounded text-gray-300 mt-6">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M11 6l-6 6 6 6"/></svg>
            Retour au site
        </a>
        <a href="/logout" class="flex items-center gap-2.5 px-3 py-2 rounded text-gray-300">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4H5a2 2 0 00-2 2v12a2 2 0 002 2h4"/><path d="M15 8l4 4-4 4"/><path d="M19 12H9"/></svg>
            Déconnexion
        </a>
    </nav>
</aside>
