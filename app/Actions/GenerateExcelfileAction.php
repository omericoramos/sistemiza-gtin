<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\CustomMessages;

class GenerateExcelfileAction
{
    public function __construct(
        private StoreGtinCodesAction $storeGtinCodes,
        protected ExportGtinCodesToExcelAction $exportGtinCodes
    ) {}
    public function execute(array $gtinCodesData)
    {
        $gtinData = $this->storeGtinCodes->execute($gtinCodesData['gtinCodes']);

        if ($gtinData) {
            return $this->exportGtinCodes->execute($gtinData, $gtinCodesData['companyName']);
        }

        return CustomMessages::error('Nenhum código GTIN encontrado');
    }
}
