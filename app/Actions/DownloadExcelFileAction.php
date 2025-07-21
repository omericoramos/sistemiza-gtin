<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadExcelFileAction
{

    public function execute(string $token): BinaryFileResponse
    {
        $cacheKey = "gtin_download:{$token}";
        $filePath = Cache::pull($cacheKey); // já remove do cache na leitura

        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Arquivo expirado ou inexistente.');
        }

        return response()->download(
            $filePath,
            basename($filePath)
        )->deleteFileAfterSend(true);
    }
}
