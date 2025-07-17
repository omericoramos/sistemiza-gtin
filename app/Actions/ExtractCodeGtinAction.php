<?php

namespace App\Actions;

use App\Facades\TempDirectoryManager;
use App\Support\CustomMessages;
use Illuminate\Support\Facades\Auth;

class ExtractCodeGtinAction
{
    public function __construct(
        protected ProcessNFeDataAction $processNFeDataAction,
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

            return $this->processNFeDataAction->execute($files);
        }
        return CustomMessages::error('Nenhuma NFe encontrada');
    }
}
