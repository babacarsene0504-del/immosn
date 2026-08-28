<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();                       


use Core\Router;
use Controller\AuthController;
use Controller\MemberController;
use Controller\BienController;
use Controller\VilleController;
use Controller\AdminController;

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

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
