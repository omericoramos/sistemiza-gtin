<?php

namespace App\Support;

class CustomMessages
{
    public static function error(string $message, int $statusCode = 400, bool $asJson = true)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'statusCode' => $statusCode,
        ];

        return $asJson ? json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) : $response;
    }

    public static function success(string $message, array $data = [], int $statusCode = 200,  bool $asJson = true)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'statusCode' => $statusCode,
        ];

        if (!empty($data) && is_array($data)) {
            $response = array_merge($response, $data);
        }

        return $asJson ? json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) : $response;
    }

    public static function info(string $message, int $statusCode = 200, bool $asJson = true)
    {
        $response = [
            'status' => 'info',
            'message' => $message,
            'statusCode' => $statusCode,
        ];

        return $asJson ? json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK) : $response;
    }
}
