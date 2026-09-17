<?php

class Response
{
    public static function json(
        array $data,
        int $status = 200
    ): void {
        http_response_code($status);

        header(
            'Content-Type: application/json; charset=utf-8'
        );

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    public static function success(
        $data = null,
        string $message = 'Thành công',
        int $status = 200
    ): void {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(
        string $message,
        int $status = 400,
        $errors = null
    ): void {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}