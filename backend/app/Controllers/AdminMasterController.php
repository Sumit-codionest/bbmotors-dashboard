<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;
use PDO;

class AdminMasterController extends BaseModel
{
    private array $entities = [
        'countries' => ['table' => 'Country_Master', 'id' => 'Country_Id', 'columns' => ['Country_Name', 'Country_Code'], 'soft_delete' => true],
        'states' => ['table' => 'State_Master', 'id' => 'State_Id', 'columns' => ['Country_Id', 'State_Name'], 'soft_delete' => true],
        'cities' => ['table' => 'City_Master', 'id' => 'City_Id', 'columns' => ['State_Id', 'City_Name'], 'soft_delete' => true],
        'companies' => ['table' => 'Company_Master', 'id' => 'Company_Id', 'columns' => ['Company_Name', 'Country_Id', 'State_Id', 'City_Id', 'Address', 'Is_Active'], 'soft_delete' => true],
        'users' => ['table' => 'User_Master', 'id' => 'User_Id', 'columns' => ['Username', 'Password', 'Email', 'Phone', 'Role_Id', 'Status', 'Is_Active'], 'soft_delete' => true],
        'sessions' => ['table' => 'Session_Master', 'id' => 'Session_Id', 'columns' => ['User_Id', 'Refresh_Token', 'Expires_At'], 'soft_delete' => false],
        'roles' => ['table' => 'Role_Master', 'id' => 'Role_Id', 'columns' => ['Role_Name'], 'soft_delete' => true],
        'role-permissions' => ['table' => 'Role_Permissions', 'id' => 'Permission_Id', 'columns' => ['Role_Id', 'Resource', 'Action'], 'soft_delete' => true],
        'login-history' => ['table' => 'Login_History', 'id' => 'Login_Id', 'columns' => ['Username', 'Ip_Address', 'Is_Success'], 'soft_delete' => false],
        'password-reset-tokens' => ['table' => 'Password_Reset_Tokens', 'id' => 'Token_Id', 'columns' => ['User_Id', 'Token', 'Expires_At', 'Is_Used'], 'soft_delete' => false],
        'code-headers' => ['table' => 'Code_Header', 'id' => 'Header_Id', 'columns' => ['Code_Name', 'Description'], 'soft_delete' => true],
        'code-details' => ['table' => 'Code_Details', 'id' => 'Detail_Id', 'columns' => ['Header_Id', 'Code_Value', 'Code_Label', 'Sort_Order'], 'soft_delete' => true],
        'brands' => ['table' => 'Brand_Master', 'id' => 'Brand_Id', 'columns' => ['Brand_Name', 'Is_Active'], 'soft_delete' => true],
        'features' => ['table' => 'Feature_Master', 'id' => 'Feature_Id', 'columns' => ['Feature_Name', 'Is_Active'], 'soft_delete' => true],
        'models' => ['table' => 'Item_Model_Master', 'id' => 'Model_Id', 'columns' => ['Brand_Id', 'Model_Name', 'Is_Active'], 'soft_delete' => true],
        'item-details' => ['table' => 'item_Details', 'id' => 'Item_Id', 'columns' => ['Company_Id', 'Model_Id', 'Registration_No', 'Make_Year', 'Registration_Year', 'Km_Driven', 'Price', 'Color_Code', 'Owner_Count', 'Insurance_Valid_Upto', 'Spare_Key', 'Status_Code', 'Fuel_Type_Code', 'Transmission_Code'], 'soft_delete' => true],
        'item-images' => ['table' => 'item_Images', 'id' => 'Image_Id', 'columns' => ['Item_Id', 'Image_Path', 'Is_Primary'], 'soft_delete' => false],
        'item-features' => ['table' => 'Item_Features', 'id' => 'Item_Feature_Id', 'columns' => ['Item_Id', 'Feature_Id'], 'soft_delete' => false],
    ];

    public function index(array $params): void
    {
        $cfg = $this->getEntity($params['entity']);
        $sql = "SELECT * FROM {$cfg['table']}" . ($cfg['soft_delete'] ? ' WHERE Is_Deleted = 0' : '') . " ORDER BY {$cfg['id']} DESC";
        Response::json($this->db->query($sql)->fetchAll());
    }

    public function show(array $params): void
    {
        $cfg = $this->getEntity($params['entity']);
        $sql = "SELECT * FROM {$cfg['table']} WHERE {$cfg['id']} = :id" . ($cfg['soft_delete'] ? ' AND Is_Deleted = 0' : '') . ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json($stmt->fetch() ?: []);
    }

    public function store(array $params): void
    {
        $cfg = $this->getEntity($params['entity']);
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $payload = $this->filterPayload($cfg['columns'], $input, true);

        if ($cfg['table'] === 'User_Master' && !empty($payload['Password'])) {
            $payload['Password'] = password_hash((string) $payload['Password'], PASSWORD_DEFAULT);
        }

        $columns = array_keys($payload);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);
        if ($cfg['soft_delete']) {
            $columns[] = 'Is_Deleted';
            $placeholders[] = ':Is_Deleted';
            $payload['Is_Deleted'] = 0;
        }
        $columns[] = 'Created_At';
        $columns[] = 'Updated_At';

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s, NOW(), NOW())', $cfg['table'], implode(', ', $columns), implode(', ', $placeholders));
        $stmt = $this->db->prepare($sql);
        $stmt->execute($payload);

        Response::json(['message' => 'Created'], 201);
    }

    public function update(array $params): void
    {
        $cfg = $this->getEntity($params['entity']);
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $payload = $this->filterPayload($cfg['columns'], $input, false);

        if ($cfg['table'] === 'User_Master' && isset($payload['Password']) && $payload['Password'] !== '') {
            $payload['Password'] = password_hash((string) $payload['Password'], PASSWORD_DEFAULT);
        } elseif ($cfg['table'] === 'User_Master') {
            unset($payload['Password']);
        }

        if (empty($payload)) {
            Response::json(['message' => 'Nothing to update'], 422);
        }

        $set = implode(', ', array_map(fn($c) => "{$c} = :{$c}", array_keys($payload)));
        $sql = "UPDATE {$cfg['table']} SET {$set}, Updated_At = NOW() WHERE {$cfg['id']} = :id";
        if ($cfg['soft_delete']) {
            $sql .= ' AND Is_Deleted = 0';
        }

        $payload['id'] = (int) $params['id'];
        $stmt = $this->db->prepare($sql);
        $stmt->execute($payload);

        Response::json(['message' => 'Updated']);
    }

    public function delete(array $params): void
    {
        $cfg = $this->getEntity($params['entity']);
        if ($cfg['soft_delete']) {
            $sql = "UPDATE {$cfg['table']} SET Is_Deleted = 1, Updated_At = NOW() WHERE {$cfg['id']} = :id";
        } else {
            $sql = "DELETE FROM {$cfg['table']} WHERE {$cfg['id']} = :id";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json(['message' => 'Deleted']);
    }

    private function getEntity(string $entity): array
    {
        if (!isset($this->entities[$entity])) {
            Response::json(['message' => 'Invalid master entity'], 404);
        }
        return $this->entities[$entity];
    }

    private function filterPayload(array $allowedColumns, array $input, bool $isCreate): array
    {
        $payload = [];
        foreach ($allowedColumns as $column) {
            if (array_key_exists($column, $input)) {
                $payload[$column] = $input[$column];
            } elseif ($isCreate && in_array($column, ['Is_Active', 'Sort_Order', 'Owner_Count', 'Spare_Key', 'Is_Used', 'Is_Success'], true)) {
                if (in_array($column, ['Is_Active', 'Sort_Order', 'Owner_Count'], true)) {
                    $payload[$column] = 1;
                } else {
                    $payload[$column] = 0;
                }
            }
        }
        return $payload;
    }
}
