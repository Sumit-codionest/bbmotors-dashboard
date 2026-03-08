<?php

declare(strict_types=1);

namespace App\Helpers;

class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
        exit;
    }
}
