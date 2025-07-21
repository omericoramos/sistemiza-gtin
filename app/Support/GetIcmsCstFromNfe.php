<?php

declare(strict_types=1);

namespace App\Support;

use SimpleXMLElement;

class GetIcmsCstFromNfe
{
    public function getIcmsCstFromProduct(SimpleXMLElement $productInfo): ?string
    {
        if (!isset($productInfo->imposto->ICMS)) {
            return null;
        }

        $detailsIcms = current($productInfo->imposto->ICMS) ?? null;

        if (!$detailsIcms) {
            return null;
        }

        if (isset($detailsIcms->CST)) {
            return (string) $detailsIcms->CST;
        }

        if (isset($detailsIcms->CSOSN)) {
            return (string) $detailsIcms->CSOSN;
        }

        return null;
    }
}
