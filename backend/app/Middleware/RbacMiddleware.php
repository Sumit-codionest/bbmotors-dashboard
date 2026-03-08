<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Config\Database;
use App\Helpers\Response;

class RbacMiddleware
{
    public function __construct(private string $resource = '', private string $action = 'read')
    {
    }

    public function handle(): void
    {
        $auth = json_decode($_SERVER['auth_user'] ?? '{}', true);
        $roleId = (int) ($auth['role_id'] ?? 0);
        $stmt = Database::connect()->prepare('SELECT 1 FROM Role_Permissions WHERE Role_Id = :role AND Resource = :resource AND Action = :action AND Is_Deleted = 0 LIMIT 1');
        $stmt->execute(['role' => $roleId, 'resource' => $this->resource, 'action' => $this->action]);
        if (!$stmt->fetchColumn()) {
            Response::json(['message' => 'Forbidden'], 403);
        }
    }
}
