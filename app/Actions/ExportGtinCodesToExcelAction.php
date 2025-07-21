<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\CustomMessages;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
        $sheet->setCellValue('B1', 'NCM');
        $sheet->setCellValue('C1', 'CFOP');
        $sheet->setCellValue('D1', 'CEST');
        $sheet->setCellValue('E1', 'Descrição');
        $sheet->setCellValue('F1', 'CST ICMS');
        $sheet->setCellValue('G1', 'CST PIS');
        $sheet->setCellValue('H1', 'CST COFINS');

        $row = 2;

        foreach ($gtinCodes as $gtinCode) {

            $sheet->getStyle("A{$row}:H{$row}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);

            $sheet->setCellValueExplicit('A' . $row, $gtinCode['gtin_code'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row, $gtinCode['ncm'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row, $gtinCode['cfop'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row, $gtinCode['cest'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row, $gtinCode['description'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $row, $gtinCode['cst_icms'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $row, $gtinCode['cst_pis'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $row, $gtinCode['cst_cofins'], DataType::TYPE_STRING);
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
