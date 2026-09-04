<?php

namespace Controller;

use Model\Bien;

class HomeController
{
    /** GET / — page d'accueil publique */
    public function index(): void
    {
        $biensAlaUne = Bien::search([], 6, 0); // les 6 biens actifs les plus récents

        require __DIR__ . '/../View/home/index.php';
    }
}
