<?php
class Res
{

    public static array $submitted = [];
    public static function error($err = '', string $message = '', int $status = 400)
    {
        Utils::$response = [
            'status_code' => $status,
            'status' => false,
            'message' => $message,
            'submitted' => self::$submitted,
            'error' => Utils::$response['error'] ?? $err
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
            'submitted' => self::$submitted,
            'data' => $data
        ];
        
        if(count(self::$submitted) == 0) unset(Utils::$response['submitted']);
        
        status_header(Utils::$response['status_code']);
        return true;
    }
}
