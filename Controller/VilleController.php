<?php

namespace Controller;

use Model\Ville;

class VilleController
{
    /** GET /api/villes?region=Dakar — retourne les villes de cette région en JSON, pour la cascade en JS */
    public function byRegion(): void
    {
        header('Content-Type: application/json');

        $region = $_GET['region'] ?? '';
        if ($region === '') {
            echo json_encode([]);
            return;
        }

        echo json_encode(Ville::getByRegion($region));
    }
}
