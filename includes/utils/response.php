<?php
class Res
{

    public static function error($err = '', string $message = '', int $status = 400)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => false,
            'message' => strtolower(str_replace(' ', '_', $message)),
            'error' => Utils::$response['error'] ?? $err
        ];
        status_header(Utils::$response['status_code']);
        return false;
    }

    public static function success($data, string $message, int $status = 200)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => true,
            'message' => strtolower(str_replace(' ', '_', $message)),
            'data' => $data
        ];
        status_header(Utils::$response['status_code']);
        return true;
    }
}
