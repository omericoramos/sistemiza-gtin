<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\CustomMessages;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;

class ExportGtinCodesToExcelAction
{
    const FILE_NAME = "codigos_gtin_";
    const DIRECTORY_PATH = "app/public/exports_gtin/";
    public function execute(array $gtinCodes, string $companyName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->generateStructure($gtinCodes, $sheet);

        $token = $this->saveFile($spreadsheet, $companyName);

        return CustomMessages::success(
            'Arquivo gerado com sucesso',
            ['token' => $token]
        );
    }

    private function generateStructure(array $gtinCodes, Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'Código GTIN');

        $row = 2;

        foreach ($gtinCodes as $gtinCode) {

            $sheet->setCellValueExplicit(
                'A' . $row,
                $gtinCode,
                DataType::TYPE_STRING
            );

            $row++;
        }
    }

    private function saveFile(Spreadsheet $spreadsheet, string $companyName): string
    {
        $fileName = self::FILE_NAME . '_' . $companyName . '_' . date('Y-m-d_His') . '.xlsx';
        $fullPath = storage_path(self::DIRECTORY_PATH);

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $filePath = $fullPath . $fileName;
        $writer = new Xlsx($spreadsheet);

        $writer->save($filePath);

        $token = $this->createCache($filePath);
        return $token;
    }

    private function createCache(string $filePath): string
    {
        $token = Str::random(40);
        Cache::put("gtin_download:{$token}", $filePath, now()->addMinutes(5));
        return $token;
    }
}
