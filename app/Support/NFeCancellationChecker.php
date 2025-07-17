<?php 

namespace App\Support;

class NFeCancellationChecker {
    public static function isCanceled( ?object $xmlData = null) {
        
        $canceled = false;

        if (isset($xmlData->protNFe->infProt->cStat) && current($xmlData->protNFe->infProt->cStat) === '101') {
            $canceled = true;
        }

        if (isset($xmlData->procEventoNFe->evento->infEvento->tpEvento)) {
            $canceledEvent = current($xmlData->procEventoNFe->evento->infEvento->tpEvento);
            if ($canceledEvent === '110111' || $canceledEvent === '110112') {
                $canceled = true;
            }
        }

        return $canceled;
    }
}