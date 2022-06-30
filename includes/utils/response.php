<?php

namespace Res;

use Utils\Utils;

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
        return true;
    }
}
