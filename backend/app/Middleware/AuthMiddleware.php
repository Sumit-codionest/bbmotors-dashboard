<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;
use App\Services\JwtService;

class AuthMiddleware
{
    public function handle(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!str_starts_with($header, 'Bearer ')) {
            Response::json(['message' => 'Unauthorized'], 401);
        }
        try {
            $token = substr($header, 7);
            $decoded = (new JwtService())->decode($token);
            $_SERVER['auth_user'] = json_encode($decoded);
        } catch (\Throwable) {
            Response::json(['message' => 'Invalid token'], 401);
        }
    }
}
