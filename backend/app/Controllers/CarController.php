<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;
use App\Services\FileUploadService;

class CarController extends BaseModel
{
    public function index(): void
    {
        $stmt = $this->db->query('SELECT i.*, m.Model_Name, b.Brand_Name, c.Company_Name FROM item_Details i JOIN Item_Model_Master m ON i.Model_Id=m.Model_Id JOIN Brand_Master b ON m.Brand_Id=b.Brand_Id JOIN Company_Master c ON i.Company_Id=c.Company_Id WHERE i.Is_Deleted=0 ORDER BY i.Item_Id DESC');
        Response::json($stmt->fetchAll());
    }

    public function show(array $params): void
    {
        $stmt = $this->db->prepare('SELECT * FROM item_Details WHERE Item_Id=:id AND Is_Deleted=0');
        $stmt->execute(['id' => (int) $params['id']]);
        $car = $stmt->fetch();
        Response::json($car ?: []);
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $sql = 'INSERT INTO item_Details (Company_Id, Model_Id, Registration_No, Make_Year, Registration_Year, Km_Driven, Price, Color_Code, Owner_Count, Insurance_Valid_Upto, Spare_Key, Status_Code, Fuel_Type_Code, Transmission_Code, Is_Deleted, Created_At, Updated_At) VALUES (:company_id,:model_id,:registration_no,:make_year,:registration_year,:km_driven,:price,:color_code,:owner_count,:insurance,:spare_key,:status_code,:fuel,:transmission,0,NOW(),NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'company_id' => $input['company_id'], 'model_id' => $input['model_id'], 'registration_no' => $input['registration_no'],
            'make_year' => $input['make_year'], 'registration_year' => $input['registration_year'], 'km_driven' => $input['km_driven'],
            'price' => $input['price'], 'color_code' => $input['color_code'], 'owner_count' => $input['owner_count'] ?? 1,
            'insurance' => $input['insurance_valid_upto'] ?? null, 'spare_key' => $input['spare_key'] ?? 0,
            'status_code' => $input['status_code'], 'fuel' => $input['fuel_type_code'], 'transmission' => $input['transmission_code']
        ]);
        Response::json(['message' => 'Car created'], 201);
    }

    public function update(array $params): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $this->db->prepare('UPDATE item_Details SET Price=:price, Km_Driven=:km, Status_Code=:status, Updated_At=NOW() WHERE Item_Id=:id');
        $stmt->execute(['price' => $input['price'], 'km' => $input['km_driven'], 'status' => $input['status_code'], 'id' => (int) $params['id']]);
        Response::json(['message' => 'Car updated']);
    }

    public function delete(array $params): void
    {
        $stmt = $this->db->prepare('UPDATE item_Details SET Is_Deleted=1, Updated_At=NOW() WHERE Item_Id=:id');
        $stmt->execute(['id' => (int) $params['id']]);
        Response::json(['message' => 'Car deleted']);
    }

    public function uploadImages(array $params): void
    {
        $uploader = new FileUploadService();
        $paths = $uploader->uploadMany('images');
        foreach ($paths as $idx => $path) {
            $stmt = $this->db->prepare('INSERT INTO item_Images (Item_Id, Image_Path, Is_Primary, Created_At) VALUES (:id,:path,:p,NOW())');
            $stmt->execute(['id' => (int) $params['id'], 'path' => $path, 'p' => $idx === 0 ? 1 : 0]);
        }
        Response::json(['message' => 'Images uploaded', 'paths' => $paths]);
    }

    public function deleteImage(array $params): void
    {
        $stmt = $this->db->prepare('DELETE FROM item_Images WHERE Image_Id=:img AND Item_Id=:id');
        $stmt->execute(['img' => (int) $params['imageId'], 'id' => (int) $params['id']]);
        Response::json(['message' => 'Image deleted']);
    }
}
