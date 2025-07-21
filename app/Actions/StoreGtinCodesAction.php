<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Gtin;

class StoreGtinCodesAction
{
    public function execute(array $gtinCodes): ?array
    {
        $codes = collect($gtinCodes)->values();

        $existingCodes = Gtin::whereIn('gtin_code', $codes)->pluck('gtin_code')->toArray();
        $newCodes = $codes->reject(fn($code) => in_array($code, $existingCodes));

        if ($newCodes->isEmpty()) {
            return null;
        }

        $dateNow = now();

        $insertedCodes = $newCodes->map(fn($code) => [
            'gtin_code' => $code,
            'created_at' => $dateNow,
            'updated_at' => $dateNow
        ])->toArray();

        Gtin::upsert($insertedCodes, ['gtin_code'], ['updated_at']);

        return $insertedCodes;
    }
}
