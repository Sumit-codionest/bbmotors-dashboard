<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;

class DashboardController extends BaseModel
{
    public function summary(): void
    {
        $queries = [
            'totalCarsInStock' => "SELECT COUNT(*) FROM item_Details WHERE Is_Deleted=0 AND Status_Code='AVAILABLE'",
            'totalCarsSold' => "SELECT COUNT(*) FROM item_Details WHERE Is_Deleted=0 AND Status_Code='SOLD'",
            'totalBrands' => 'SELECT COUNT(*) FROM Brand_Master WHERE Is_Deleted=0',
            'totalModels' => 'SELECT COUNT(*) FROM Item_Model_Master WHERE Is_Deleted=0',
            'totalUsers' => 'SELECT COUNT(*) FROM User_Master WHERE Is_Deleted=0',
            'totalInventoryValue' => 'SELECT COALESCE(SUM(Price),0) FROM item_Details WHERE Is_Deleted=0',
        ];

        $res = [];
        foreach ($queries as $k => $sql) {
            $res[$k] = (float) $this->db->query($sql)->fetchColumn();
        }
        Response::json($res);
    }

    public function charts(): void
    {
        $brand = $this->db->query('SELECT b.Brand_Name as name, COUNT(*) as value FROM item_Details i JOIN Item_Model_Master m ON i.Model_Id=m.Model_Id JOIN Brand_Master b ON m.Brand_Id=b.Brand_Id WHERE i.Is_Deleted=0 GROUP BY b.Brand_Name')->fetchAll();
        $fuel = $this->db->query("SELECT Fuel_Type_Code as name, COUNT(*) as value FROM item_Details WHERE Is_Deleted=0 GROUP BY Fuel_Type_Code")->fetchAll();
        Response::json(['carsByBrand' => $brand, 'carsByFuelType' => $fuel]);
    }
}
