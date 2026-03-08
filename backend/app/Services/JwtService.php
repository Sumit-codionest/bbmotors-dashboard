<?php

declare(strict_types=1);

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function issueAccessToken(array $payload): string
    {
        $now = time();
        $ttl = (int) ($_ENV['JWT_TTL'] ?? 3600);
        return JWT::encode([
            'iss' => $_ENV['JWT_ISSUER'] ?? 'app',
            'aud' => $_ENV['JWT_AUDIENCE'] ?? 'users',
            'iat' => $now,
            'exp' => $now + $ttl,
            'sub' => $payload['user_id'],
            'role_id' => $payload['role_id'],
            'username' => $payload['username'],
        ], $_ENV['JWT_SECRET'] ?? 'secret', 'HS256');
    }

    public function decode(string $token): object
    {
        return JWT::decode($token, new Key($_ENV['JWT_SECRET'] ?? 'secret', 'HS256'));
    }
}
