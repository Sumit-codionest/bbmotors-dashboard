<?php

declare(strict_types=1);

namespace App\Services;

class FileUploadService
{
    public function uploadMany(string $field): array
    {
        $dir = dirname(__DIR__, 2) . '/' . ($_ENV['UPLOAD_DIR'] ?? 'storage/uploads');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $files = $_FILES[$field] ?? null;
        if (!$files) {
            return [];
        }
        $paths = [];
        foreach ($files['tmp_name'] as $i => $tmp) {
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $name = uniqid('car_', true) . '.' . $ext;
            $target = $dir . '/' . $name;
            move_uploaded_file($tmp, $target);
            $paths[] = 'storage/uploads/' . $name;
        }
        return $paths;
    }
}
