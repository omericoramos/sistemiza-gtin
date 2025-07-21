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
        $gtinCodes = $this->storeGtinCodes->execute($gtinCodesData['gtinCodes']);

        if ($gtinCodes) {
            $codesOnly = collect($gtinCodes)->pluck('gtin_code')->toArray();
            return $this->exportGtinCodes->execute($codesOnly, $gtinCodesData['companyName']);
        }

        return CustomMessages::error('Nenhum código GTIN encontrado');
    }
}
