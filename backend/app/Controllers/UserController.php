<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;

class UserController extends BaseModel
{
    public function index(): void
    {
        $stmt = $this->db->query('SELECT User_Id, Username, Email, Phone, Role_Id, Status, Is_Active FROM User_Master WHERE Is_Deleted = 0 ORDER BY User_Id DESC');
        Response::json($stmt->fetchAll());
    }

    public function show(array $params): void
    {
        $stmt = $this->db->prepare('SELECT User_Id, Username, Email, Phone, Role_Id, Status, Is_Active FROM User_Master WHERE User_Id = :id AND Is_Deleted=0');
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json($stmt->fetch() ?: []);
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('INSERT INTO User_Master (Username, Password, Email, Phone, Role_Id, Status, Is_Active, Is_Deleted, Created_At, Updated_At) VALUES (:u,:p,:e,:ph,:r,:s,:a,0,NOW(),NOW())');
        $stmt->execute([
            'u' => $input['username'], 'p' => password_hash($input['password'], PASSWORD_DEFAULT),
            'e' => $input['email'], 'ph' => $input['phone'], 'r' => $input['role_id'], 's' => $input['status_code'], 'a' => $input['is_active'] ?? 1
        ]);
        Response::json(['message' => 'User created'], 201);
    }

    public function update(array $params): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('UPDATE User_Master SET Email=:e, Phone=:ph, Role_Id=:r, Status=:s, Is_Active=:a, Updated_At=NOW() WHERE User_Id=:id');
        $stmt->execute(['e' => $input['email'], 'ph' => $input['phone'], 'r' => $input['role_id'], 's' => $input['status_code'], 'a' => $input['is_active'], 'id' => (int) $params['id']]);
        Response::json(['message' => 'User updated']);
    }

    public function delete(array $params): void
    {
        $stmt = $this->db->prepare('UPDATE User_Master SET Is_Deleted=1, Updated_At=NOW() WHERE User_Id=:id');
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json(['message' => 'User deleted']);
    }
}
