<?php

namespace App\Actions;

use App\Support\NFeCancellationChecker;
use App\Support\TempDirectoryManager;
use App\Support\XmlFileParser;

class ReadValidNFeFileAction
{
    public function execute($file)
    {
        $filePath = TempDirectoryManager::userTempPath() . '/' . $file;
        $xmlData = XmlFileParser::extract($filePath);
        $checkCanceled = NFeCancellationChecker::isCanceled($xmlData);

        if ($xmlData && !$checkCanceled) {
            return $xmlData;
        }
    }
}
