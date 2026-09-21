<?php $currentPath = $_SERVER['REQUEST_URI']; ?>
<aside class="w-56 bg-[#2B2420] text-white shrink-0 min-h-screen">
    <div class="p-6">
        <p class="text-lg font-semibold">ImmoSn<span class="text-secondary">.com</span></p>
    </div>
    <nav class="px-3 space-y-1 text-sm">
        <a href="/dashboard" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/dashboard') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h5v-6h4v6h5V10"/></svg>
            Mes biens
        </a>
        <a href="/messagerie" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/messagerie') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
            Messagerie
        </a>
        <a href="/alertes" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/alertes') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a5 5 0 00-5 5v3c0 1-.5 2-1.5 3h13c-1-1-1.5-2-1.5-3V8a5 5 0 00-5-5z"/><path d="M10 19a2 2 0 004 0"/></svg>
            Alertes
        </a>
        <a href="/favoris" class="flex items-center gap-2.5 px-3 py-2 rounded <?= str_starts_with($currentPath, '/favoris') ? 'bg-secondary/25' : 'text-gray-300' ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5z"/></svg>
            Favoris
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
