<?php

namespace App\Actions;

use App\Support\CustomMessages;
use App\Support\TempDirectoryManager;
use App\Support\ZipExtractor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UploadXmlZipAction
{
    public function execute(UploadedFile $zipFile): string
    {
        if (!$zipFile instanceof UploadedFile || $zipFile->getClientOriginalExtension() !== 'zip') {
            throw new \InvalidArgumentException('Arquivo inválido ou formato incorreto.');
        }

        try {
            $path = TempDirectoryManager::ensureUserTempExists();

            ZipExtractor::extractAndOrganize($zipFile->getRealPath(), $path);

            return CustomMessages::success('success');
        } catch (\Throwable $th) {

            Log::error('Erro ao descompactar arquivo: ' . $th->getMessage(), [
                'exception' => $th,
                'user_id' => Auth::user()->id ?? 'guest'
            ]);

            return CustomMessages::error('Ocorreu um erro interno ao processar o arquivo. Por favor, tente novamente mais tarde.', 500);
        }
    }
}
