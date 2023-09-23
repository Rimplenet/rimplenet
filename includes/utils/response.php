<?php
class Res
{
    public static $submitted = [];
    public static function error($err = '', string $message = '', int $status = 400)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => false,
            'message' => $message,
            'error' => Utils::$response['error'] ?? $err,
            'submitted' => self::$submitted,
        ];

        if(count(self::$submitted) == 0) unset(Utils::$response['submitted']);

        status_header(Utils::$response['status_code']);
        return false;
    }

    public static function success($data, string $message, int $status = 200)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => true,
            'message' => $message,
            'data' => $data,
            'submitted' => self::$submitted,
        ];
        
        if(count(self::$submitted) == 0) unset(Utils::$response['submitted']);

        status_header(Utils::$response['status_code']);
        return true;
    }
}
