<?php

use App\Controllers\AdminMasterController;
use App\Controllers\AuthController;
use App\Controllers\BulkUploadController;
use App\Controllers\CarController;
use App\Controllers\DashboardController;
use App\Controllers\MasterController;
use App\Controllers\ResourceController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;

$router->add('POST', '/api/auth/login', [AuthController::class, 'login'], [RateLimitMiddleware::class]);
$router->add('POST', '/api/auth/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);
$router->add('POST', '/api/auth/refresh', [AuthController::class, 'refresh']);
$router->add('POST', '/api/auth/forgot-password', [AuthController::class, 'forgotPassword']);
$router->add('POST', '/api/auth/reset-password', [AuthController::class, 'resetPassword']);
$router->add('GET', '/api/auth/me', [AuthController::class, 'me'], [AuthMiddleware::class]);

$router->add('GET', '/api/dashboard/summary', [DashboardController::class, 'summary'], [AuthMiddleware::class]);
$router->add('GET', '/api/dashboard/charts', [DashboardController::class, 'charts'], [AuthMiddleware::class]);

$router->add('GET', '/api/cars', [CarController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/api/cars/{id}', [CarController::class, 'show'], [AuthMiddleware::class]);
$router->add('POST', '/api/cars', [CarController::class, 'store'], [AuthMiddleware::class]);
$router->add('PUT', '/api/cars/{id}', [CarController::class, 'update'], [AuthMiddleware::class]);
$router->add('DELETE', '/api/cars/{id}', [CarController::class, 'delete'], [AuthMiddleware::class]);
$router->add('POST', '/api/cars/{id}/images', [CarController::class, 'uploadImages'], [AuthMiddleware::class]);
$router->add('DELETE', '/api/cars/{id}/images/{imageId}', [CarController::class, 'deleteImage'], [AuthMiddleware::class]);
$router->add('POST', '/api/cars/bulk-upload', [BulkUploadController::class, 'upload'], [AuthMiddleware::class]);

foreach (['brands','models','features','companies'] as $resource) {
    $router->add('GET', "/api/{$resource}", fn() => (new ResourceController())->index(['resource' => $resource]), [AuthMiddleware::class]);
    $router->add('GET', "/api/{$resource}/{id}", fn($p) => (new ResourceController())->show(['resource' => $resource, 'id' => $p['id']]), [AuthMiddleware::class]);
    $router->add('POST', "/api/{$resource}", fn() => (new ResourceController())->store(['resource' => $resource]), [AuthMiddleware::class]);
    $router->add('PUT', "/api/{$resource}/{id}", fn($p) => (new ResourceController())->update(['resource' => $resource, 'id' => $p['id']]), [AuthMiddleware::class]);
    $router->add('DELETE', "/api/{$resource}/{id}", fn($p) => (new ResourceController())->delete(['resource' => $resource, 'id' => $p['id']]), [AuthMiddleware::class]);
}

$router->add('GET', '/api/users', [UserController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/api/users/{id}', [UserController::class, 'show'], [AuthMiddleware::class]);
$router->add('POST', '/api/users', [UserController::class, 'store'], [AuthMiddleware::class]);
$router->add('PUT', '/api/users/{id}', [UserController::class, 'update'], [AuthMiddleware::class]);
$router->add('DELETE', '/api/users/{id}', [UserController::class, 'delete'], [AuthMiddleware::class]);

$router->add('GET', '/api/countries', [MasterController::class, 'countries'], [AuthMiddleware::class]);
$router->add('GET', '/api/states', [MasterController::class, 'states'], [AuthMiddleware::class]);
$router->add('GET', '/api/cities', [MasterController::class, 'cities'], [AuthMiddleware::class]);
$router->add('GET', '/api/codes/{codeName}', [MasterController::class, 'codes'], [AuthMiddleware::class]);

$router->add('POST', '/api/codes/header', [MasterController::class, 'createHeader'], [AuthMiddleware::class]);
$router->add('POST', '/api/codes/details', [MasterController::class, 'createDetail'], [AuthMiddleware::class]);
$router->add('PUT', '/api/codes/details/{id}', [MasterController::class, 'updateDetail'], [AuthMiddleware::class]);
$router->add('DELETE', '/api/codes/details/{id}', [MasterController::class, 'deleteDetail'], [AuthMiddleware::class]);

$router->add('GET', '/api/masters/{entity}', [AdminMasterController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/api/masters/{entity}/{id}', [AdminMasterController::class, 'show'], [AuthMiddleware::class]);
$router->add('POST', '/api/masters/{entity}', [AdminMasterController::class, 'store'], [AuthMiddleware::class]);
$router->add('PUT', '/api/masters/{entity}/{id}', [AdminMasterController::class, 'update'], [AuthMiddleware::class]);
$router->add('DELETE', '/api/masters/{entity}/{id}', [AdminMasterController::class, 'delete'], [AuthMiddleware::class]);
