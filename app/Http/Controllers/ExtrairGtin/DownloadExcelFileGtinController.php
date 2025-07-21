<?php

namespace App\Http\Controllers\ExtrairGtin;

use App\Actions\DownloadExcelFileAction;
use App\Http\Controllers\Controller;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadExcelFileGtinController extends Controller
{
    public function __construct(
        private DownloadExcelFileAction $downloadExcelFile
    ) {}
    public function __invoke(string $token): BinaryFileResponse
    {
        $response = $this->downloadExcelFile->execute($token);
        return $response;
    }
}
