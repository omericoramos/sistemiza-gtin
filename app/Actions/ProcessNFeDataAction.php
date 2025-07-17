<?php

namespace App\Actions;

use App\Support\TempDirectoryManager;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ProcessNFeDataAction
{
    public function __construct(
        protected ReadValidNFeFileAction $readValidNFeFile,
        protected string $errorResponse = '',
    ) {}

    public function execute(array $files): ?array
    {
        $gtinBatches = [];
        $currentFile = null;

        try {

            foreach ($files as $file) {

                $xmlData = $this->readValidNFeFile->execute($file);
                $currentFile = $file;
                if (isset($xmlData->NFe->infNFe->det)) {

                    $gtinCode =  $this->processFile($xmlData);

                    if ($gtinCode) {
                        $gtinBatches[] = $gtinCode;
                    }
                }
            }

            $allGtinCodes = array_merge(...$gtinBatches);
            $codes = collect($allGtinCodes)->unique()->values()->all();

            return $codes;
        } catch (\Throwable $th) {
            $this->errorResponse = $currentFile ? "Erro ao processar o arquivo: {$currentFile}" : 'Erro ao processar os arquivos';
            Log::error($this->errorResponse . $th->getMessage(), ['exception' => $th]);
            return null;
        } finally {
            TempDirectoryManager::clearUserTemp();
        }
    }

    private function processFile(SimpleXMLElement $xmlData): array
    {
        $gtinCodes = [];

        $productList = $xmlData->NFe->infNFe->det;
        foreach ($productList as $productInfo) {

            $gtinCode = $this->getGtinCode($productInfo);

            if ($gtinCode) {
                $gtinCodes[] = $gtinCode;
            }
        }
        return $gtinCodes;
    }

    private function getGtinCode($productInfo): ?string
    {
        $gtinCode = (string) ($productInfo->prod->cEAN ?? $productInfo->prod->cEANTrib ?? null);

        if ($gtinCode) {
            $gtinCodeDigits = preg_replace('/\D/', '', $gtinCode);
            if (
                in_array(strlen($gtinCodeDigits), [8, 12, 13, 14]) &&
                !in_array($gtinCodeDigits, ['00000000', '0000000000000', '00000000000000'])
            ) {
                return $gtinCodeDigits;
            }
        }
        return null;
    }
}
