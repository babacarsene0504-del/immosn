<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Scanner un QR Code — ImmoSn.com</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body>
    <header style="padding:16px 28px; background:#fff; border-bottom:1px solid #E8DFCF;">
        <a href="/biens" style="color:#C2542E; font-weight:bold; text-decoration:none;">ImmoSn.com</a>
    </header>

    <main class="max-w-md mx-auto mt-10 p-6 text-center">
        <h1 class="text-xl font-bold mb-2">Scanner un QR Code</h1>
        <p class="text-sm text-gray-500 mb-6">Pointez votre caméra vers le QR Code du bien</p>

        <div id="reader" class="mb-4"></div>
        <p id="scan-status" class="text-sm text-red-600 mb-4"></p>

        <p class="text-sm text-gray-500 my-4">ou saisissez le code manuellement</p>

        <form id="manual-form" class="flex gap-2">
            <input type="text" id="manual-token" placeholder="Code du bien" required
                   class="flex-1 border rounded px-3 py-2">
            <button type="submit" class="bg-secondary text-white px-4 py-2 rounded">Valider</button>
        </form>
    </main>

    <script>
    function onScanSuccess(decodedText) {
        // Le QR Code encode l'URL complète (ex: http://localhost:8000/scan/abc123...)
        // On y redirige directement le navigateur.
        window.location.href = decodedText;
    }

    function onScanFailure() {
        // Appelé en continu tant qu'aucun QR n'est détecté dans le cadre — normal, on ignore.
    }

    const scanner = new Html5QrcodeScanner('reader', { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess, onScanFailure);

    document.getElementById('manual-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const token = document.getElementById('manual-token').value.trim();
        if (token) {
            window.location.href = `/scan/${encodeURIComponent(token)}`;
        }
    });
    </script>
</body>
</html>
