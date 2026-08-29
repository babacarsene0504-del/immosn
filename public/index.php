<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sert directement les fichiers statiques réels (images, CSS, JS...) sans
// passer par le Router — indispensable avec le serveur PHP intégré, qui
// n'a pas de gestion de fichiers statiques automatique quand un router
// script est fourni (contrairement à Apache/Nginx en production).
if (php_sapi_name() === 'cli-server') {
    $requestedPath = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($requestedPath !== false && is_file($requestedPath)) {
        return false;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use Core\Router;
use Controller\AuthController;
use Controller\MemberController;
use Controller\BienController;
use Controller\VilleController;
use Controller\AdminController;
use Controller\ScannerController;

session_start();

$router = new Router();

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [MemberController::class, 'dashboard']);

$router->get('/biens', [BienController::class, 'index']);
$router->get('/biens/:id', [BienController::class, 'show']);
$router->get('/publier', [BienController::class, 'showCreate']);
$router->post('/publier', [BienController::class, 'store']);
$router->get('/api/villes', [VilleController::class, 'byRegion']);

$router->get('/admin', [AdminController::class, 'index']);
$router->post('/admin/biens/:id/valider', [AdminController::class, 'validate']);
$router->post('/admin/biens/:id/rejeter', [AdminController::class, 'reject']);

$router->get('/scanner', [ScannerController::class, 'showScanner']);
$router->get('/scan/:token', [ScannerController::class, 'scan']);
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
