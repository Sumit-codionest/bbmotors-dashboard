<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;

class MasterController extends BaseModel
{
    public function countries(): void { Response::json($this->db->query('SELECT * FROM Country_Master WHERE Is_Deleted=0')->fetchAll()); }
    public function states(): void { Response::json($this->db->query('SELECT * FROM State_Master WHERE Is_Deleted=0')->fetchAll()); }
    public function cities(): void { Response::json($this->db->query('SELECT * FROM City_Master WHERE Is_Deleted=0')->fetchAll()); }

    public function codes(array $params): void
    {
        $stmt = $this->db->prepare('SELECT d.* FROM Code_Details d JOIN Code_Header h ON d.Header_Id = h.Header_Id WHERE h.Code_Name=:name AND d.Is_Deleted=0');
        $stmt->execute(['name' => strtoupper($params['codeName'])]);
        Response::json($stmt->fetchAll());
    }

    public function createHeader(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('INSERT INTO Code_Header (Code_Name, Description, Is_Deleted, Created_At, Updated_At) VALUES (:n,:d,0,NOW(),NOW())');
        $stmt->execute(['n' => strtoupper($input['code_name']), 'd' => $input['description'] ?? null]);
        Response::json(['message' => 'Header created'], 201);
    }

    public function createDetail(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('INSERT INTO Code_Details (Header_Id, Code_Value, Code_Label, Sort_Order, Is_Deleted, Created_At, Updated_At) VALUES (:h,:v,:l,:s,0,NOW(),NOW())');
        $stmt->execute(['h' => $input['header_id'], 'v' => strtoupper($input['code_value']), 'l' => $input['code_label'], 's' => $input['sort_order'] ?? 1]);
        Response::json(['message' => 'Detail created'], 201);
    }

    public function updateDetail(array $params): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('UPDATE Code_Details SET Code_Value=:v, Code_Label=:l, Sort_Order=:s, Updated_At=NOW() WHERE Detail_Id=:id');
        $stmt->execute(['v' => strtoupper($input['code_value']), 'l' => $input['code_label'], 's' => $input['sort_order'] ?? 1, 'id' => (int) $params['id']]);
        Response::json(['message' => 'Detail updated']);
    }

    public function deleteDetail(array $params): void
    {
        $stmt = $this->db->prepare('UPDATE Code_Details SET Is_Deleted=1, Updated_At=NOW() WHERE Detail_Id=:id');
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json(['message' => 'Detail deleted']);
    }
}
