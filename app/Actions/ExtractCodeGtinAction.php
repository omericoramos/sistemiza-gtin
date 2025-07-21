<?php

namespace App\Actions;

use App\Facades\TempDirectoryManager;
use App\Support\CustomMessages;
use Illuminate\Support\Facades\Auth;

class ExtractCodeGtinAction
{
    public function __construct(
        private ProcessNFeDataAction $processNFeDataAction,
        private GenerateExcelfileAction $generateExcelfile
    ) {}

    public function execute()
    {
        if (is_dir(TempDirectoryManager::userTempPath())) {

            $files = array_values(
                array_filter(
                    scandir(TempDirectoryManager::userTempPath()),
                    fn($file) => $file !== '.' && $file !== '..'
                )
            );

            $gtinCodesData = $this->processNFeDataAction->execute($files);

            if ($gtinCodesData) {
                return $this->generateExcelfile->execute($gtinCodesData);
            }
        }
        return CustomMessages::error('Nenhuma NFe encontrada');
    }
}
