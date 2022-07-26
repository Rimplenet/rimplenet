<?php
class Res
{

    public static function error($err = '', $message = '', $status = 400)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => false,
            'message' => $message,
            'error' => Utils::$response['error'] ?? $err
        ];
        status_header(Utils::$response['status_code']);
        return false;
    }

    public static function success($data, $message, $status = 200)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
        status_header(Utils::$response['status_code']);
        return true;
    }
}
