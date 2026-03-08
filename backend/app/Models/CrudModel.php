<?php

declare(strict_types=1);

namespace App\Models;

class CrudModel extends BaseModel
{
    public function list(string $table, string $idCol, int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE Is_Deleted = 0 ORDER BY {$idCol} DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(string $table, string $idCol, int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$idCol} = :id AND Is_Deleted = 0 LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
