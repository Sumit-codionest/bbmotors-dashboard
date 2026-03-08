<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Database;
use App\Helpers\Response;
use Dotenv\Dotenv;

class App
{
    public function run(): void
    {
        $this->loadEnvironment();
        date_default_timezone_set('UTC');
        header('Content-Type: application/json');
        $this->applyCors();

        try {
            Database::connect();
            $router = new Router();
            require __DIR__ . '/../../routes/api.php';
            $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        } catch (\Throwable $e) {
            http_response_code(500);
            Response::json([
                'message' => 'Internal Server Error',
                'error' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN) ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function loadEnvironment(): void
    {
        $root = dirname(__DIR__, 2);
        if (file_exists($root . '/.env')) {
            Dotenv::createImmutable($root)->safeLoad();
        }
    }

    private function applyCors(): void
    {
        $allowedOrigins = explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*');
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        if (in_array('*', $allowedOrigins, true) || in_array($origin, $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
