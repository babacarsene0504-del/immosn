<nav class="flex gap-4 border-b mb-6 text-sm">
    <a href="/admin" class="py-2 border-b-2 <?= ($_SERVER['REQUEST_URI'] === '/admin') ? 'border-orange-600 text-orange-600 font-medium' : 'border-transparent text-gray-500' ?>">
        Biens à valider
    </a>
    <a href="/admin/cron" class="py-2 border-b-2 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/cron')) ? 'border-orange-600 text-orange-600 font-medium' : 'border-transparent text-gray-500' ?>">
        Cron Monitor
    </a>
    <a href="/admin/mails" class="py-2 border-b-2 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/mails')) ? 'border-orange-600 text-orange-600 font-medium' : 'border-transparent text-gray-500' ?>">
        Mail Logs
    </a>
</nav>
