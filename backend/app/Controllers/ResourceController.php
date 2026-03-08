<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;

class ResourceController extends BaseModel
{
    private array $map = [
        'brands' => ['table' => 'Brand_Master', 'id' => 'Brand_Id', 'name' => 'Brand_Name'],
        'models' => ['table' => 'Item_Model_Master', 'id' => 'Model_Id', 'name' => 'Model_Name'],
        'features' => ['table' => 'Feature_Master', 'id' => 'Feature_Id', 'name' => 'Feature_Name'],
        'companies' => ['table' => 'Company_Master', 'id' => 'Company_Id', 'name' => 'Company_Name'],
    ];

    public function index(array $params): void
    {
        $cfg = $this->map[$params['resource']];
        $stmt = $this->db->query("SELECT * FROM {$cfg['table']} WHERE Is_Deleted = 0 ORDER BY {$cfg['id']} DESC");
        Response::json($stmt->fetchAll());
    }


    public function show(array $params): void
    {
        $cfg = $this->map[$params['resource']];
        $stmt = $this->db->prepare("SELECT * FROM {$cfg['table']} WHERE {$cfg['id']} = :id AND Is_Deleted = 0 LIMIT 1");
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json($stmt->fetch() ?: []);
    }

    public function store(array $params): void
    {
        $cfg = $this->map[$params['resource']];
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare("INSERT INTO {$cfg['table']} ({$cfg['name']}, Is_Active, Is_Deleted, Created_At, Updated_At) VALUES (:name,1,0,NOW(),NOW())");
        $stmt->execute(['name' => $input['name'] ?? '']);
        Response::json(['message' => 'Created'], 201);
    }

    public function update(array $params): void
    {
        $cfg = $this->map[$params['resource']];
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare("UPDATE {$cfg['table']} SET {$cfg['name']}=:name, Updated_At=NOW() WHERE {$cfg['id']}=:id");
        $stmt->execute(['name' => $input['name'] ?? '', 'id' => (int) $params['id']]);
        Response::json(['message' => 'Updated']);
    }

    public function delete(array $params): void
    {
        $cfg = $this->map[$params['resource']];
        $stmt = $this->db->prepare("UPDATE {$cfg['table']} SET Is_Deleted=1, Updated_At=NOW() WHERE {$cfg['id']}=:id");
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json(['message' => 'Deleted']);
    }
}
