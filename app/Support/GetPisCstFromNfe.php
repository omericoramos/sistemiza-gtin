<?php

declare(strict_types=1);

namespace App\Support;

use SimpleXMLElement;

class GetPisCstFromNfe
{
    public function getPisCstFromProduct(SimpleXMLElement $productInfo): ?string
    {
        if (!isset($productInfo->imposto->PIS)) {
            return null;
        }

        $detailsPis = current($productInfo->imposto->PIS) ?? null;

        if (!$detailsPis) {
            return null;
        }

        if (isset($detailsPis->CST)) {
            return (string) $detailsPis->CST;
        }

        return null;
    }
}
