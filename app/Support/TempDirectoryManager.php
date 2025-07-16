<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TempDirectoryManager
{
    public static function userTempPath(?string $prefix = ''): string
    {
        $user = Auth::user();

        $folderName = Str::slug($user->name). "-{$user->id}";

        $basePath = storage_path('app/temp');

        if ($prefix) {
            $basePath .= '/' . Str::slug($prefix);
        }

        return "{$basePath}/{$folderName}";
    }

    public static function ensureUserTempExists( ?string $prefix = null): string
    {
        $path = self::userTempPath($prefix);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }

    public static function clearUserTemp($user = null, string $prefix = ''): bool
    {
        $path = self::userTempPath($user, $prefix);

        if (File::exists($path) && File::isDirectory($path)) {
            return File::deleteDirectory($path);
        }

        return false;
    }
}
