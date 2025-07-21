<?php

namespace App\Actions;

use App\Support\GetIcmsCstFromNfe;
use App\Support\GetPisCstFromNfe;
use App\Support\TempDirectoryManager;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Illuminate\Support\Str;

class ProcessNFeDataAction
{
    public function __construct(
        private ReadValidNFeFileAction $readValidNFeFile,
        private GetIcmsCstFromNfe $getIcmsCstFromNfe,
        private GetPisCstFromNfe $getPisCstFromNfe,
        protected string $errorResponse = '',
        protected string $companyName = ''
    ) {}

    public function execute(array $files): ?array
    {
        $detailsProductBatches = [];
        $currentFile = null;

        try {

            foreach ($files as $file) {

                $xmlData = $this->readValidNFeFile->execute($file);
                $currentFile = $file;

                if (isset($xmlData->NFe->infNFe->det)) {

                    if (!$this->companyName) {
                        $this->companyName = Str::slug($xmlData->NFe->infNFe->dest->xNome, '_');
                    }

                    $detailsProduct =  $this->processFile($xmlData);

                    if ($detailsProduct) {
                        $detailsProductBatches[] = $detailsProduct;
                    }
                }
            }
            $allGtinCodes = array_merge(...$detailsProductBatches);
            $codes = collect($allGtinCodes)->unique('gtin_code')->values()->all();
            return ['gtinCodes' => $codes, 'companyName' => $this->companyName];
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
        $gtinCodesDetailsProduct = [];

        $productList = $xmlData->NFe->infNFe->det;

        foreach ($productList as $productInfo) {

            $detailsProduct = $this->getDetailsFromProduct($productInfo);

            if ($detailsProduct) {
                $gtinCodesDetailsProduct[] = $detailsProduct;
            }
        }
        return $gtinCodesDetailsProduct;
    }

    private function getDetailsFromProduct(SimpleXMLElement $productInfo): ?array
    {
        $gtinCode = (string) ($productInfo->prod->cEAN ?? $productInfo->prod->cEANTrib ?? null);

        if ($gtinCode) {

            $gtinCodeDigits = preg_replace('/\D/', '', $gtinCode);
            $cstIcms = $this->getIcmsCstFromNfe->getIcmsCstFromProduct($productInfo);
            $pisCofinsCst = $this->getPisCstFromNfe->getPisCstFromProduct($productInfo);

            if (
                in_array(strlen($gtinCodeDigits), [8, 12, 13, 14]) &&
                !in_array($gtinCodeDigits, ['00000000', '0000000000000', '00000000000000'])
            ) {
                return [
                    'gtin_code' => $gtinCodeDigits,
                    'ncm' => (string) ($productInfo->prod->NCM ?? ''),
                    'description' => (string) ($productInfo->prod->xProd ?? ''),
                    'cfop' => (string) ($productInfo->prod->CFOP ?? ''),
                    'cst_icms' => $cstIcms,
                    'cst_pis' => $pisCofinsCst,
                    'cst_cofins' => $pisCofinsCst,
                    'cest' => (string) ($productInfo->prod->CEST ?? ''),
                ];
            }
        }
        return null;
    }
}
