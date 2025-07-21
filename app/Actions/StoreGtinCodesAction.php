<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Gtin;
use \Illuminate\Support\Collection;

class StoreGtinCodesAction
{
    public function execute(array $gtinCodes): ?array
    {
        $newsGtinData = $this->removeGtinReduplicates($gtinCodes);

        if ($newsGtinData->isEmpty()) {
            return null;
        }

        $dateNow = now();

        $insertedCodes = $newsGtinData->map(
            fn($gtinData) =>
            $gtinData + ['created_at' => $dateNow, 'updated_at' => $dateNow]
        )->toArray();

        Gtin::upsert($insertedCodes, ['gtin_code'], ['updated_at']);
        return $insertedCodes;
    }

    private function removeGtinReduplicates(array $gtinCodes): Collection
    {
        $codes = collect($gtinCodes)->pluck('gtin_code')->values();

        $existingCodes = Gtin::whereIn('gtin_code', $codes)->pluck('gtin_code')->toArray();
        $existingSet = array_flip($existingCodes);

        $newsGtinData = collect($gtinCodes)->reject(
            fn($code) => isset($existingSet[$code['gtin_code']])
        );

        return $newsGtinData;
    }
}
