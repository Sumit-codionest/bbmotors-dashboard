<?php

declare(strict_types=1);

namespace App\Services;

class CsvImportService
{
    public function parse(string $path): array
    {
        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            $headers = fgetcsv($h);
            while (($line = fgetcsv($h)) !== false) {
                $rows[] = array_combine($headers, $line);
            }
            fclose($h);
        }
        return $rows;
    }
}
