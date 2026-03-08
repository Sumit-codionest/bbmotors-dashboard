<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;
use App\Services\CsvImportService;

class BulkUploadController extends BaseModel
{
    public function upload(): void
    {
        if (empty($_FILES['file']['tmp_name'])) {
            Response::json(['message' => 'File is required'], 422);
        }
        $rows = (new CsvImportService())->parse($_FILES['file']['tmp_name']);
        $ok = 0;
        $fail = 0;
        foreach ($rows as $row) {
            try {
                $stmt = $this->db->prepare('INSERT INTO item_Details (Company_Id, Model_Id, Registration_No, Make_Year, Registration_Year, Km_Driven, Price, Color_Code, Owner_Count, Status_Code, Fuel_Type_Code, Transmission_Code, Is_Deleted, Created_At, Updated_At) VALUES (:company,:model,:reg,:make,:reg_year,:km,:price,:color,:owner,:status,:fuel,:trans,0,NOW(),NOW())');
                $stmt->execute([
                    'company' => $row['company_id'], 'model' => $row['model_id'], 'reg' => $row['registration_no'],
                    'make' => $row['make_year'], 'reg_year' => $row['registration_year'], 'km' => $row['km_driven'],
                    'price' => $row['price'], 'color' => $row['color_code'], 'owner' => $row['owner_count'] ?? 1,
                    'status' => $row['status_code'], 'fuel' => $row['fuel_type_code'], 'trans' => $row['transmission_code'],
                ]);
                $ok++;
            } catch (\Throwable) {
                $fail++;
            }
        }
        Response::json(['processed' => count($rows), 'success' => $ok, 'failed' => $fail]);
    }
}
