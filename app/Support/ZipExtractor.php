<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use ZipArchive;

class ZipExtractor
{
    private static string $directory;

    public static function extractAndOrganize(string $zipPath, ?string $targetDirectory = null): void
    {
        self::$directory = $targetDirectory ?? dirname($zipPath);

        $zip = new ZipArchive();

        if ($zip->open($zipPath)) {

            $zip->extractTo(self::$directory);
            $zip->close();

            self::organizeFiles();
        }

        File::delete($zipPath);
    }

    private static function organizeFiles(): void
    {
        $items = scandir(self::$directory);

        foreach ($items as $item) {

            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = self::$directory . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                self::moveFilesUp($path);
                @rmdir($path);
            }

            if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'zip') {
                self::extractAndOrganize($path, self::$directory);
            }
        }
    }

    private static function moveFilesUp(string $subDir): void
    {
        $files = scandir($subDir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $from = $subDir . DIRECTORY_SEPARATOR . $file;
            $to = self::$directory . DIRECTORY_SEPARATOR . $file;

            rename($from, $to);
        }
    }
}
