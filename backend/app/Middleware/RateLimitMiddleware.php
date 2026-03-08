<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;

class RateLimitMiddleware
{
    public function handle(): void
    {
        $limit = (int) ($_ENV['RATE_LIMIT'] ?? 60);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = sys_get_temp_dir() . '/rate_' . md5($ip . date('YmdHi'));
        $count = file_exists($key) ? (int) file_get_contents($key) : 0;
        if ($count >= $limit) {
            Response::json(['message' => 'Too many requests'], 429);
        }
        file_put_contents($key, (string) ($count + 1));
    }
}
