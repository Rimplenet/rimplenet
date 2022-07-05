<?php

namespace InvestmentPackageGroup;

use WP_Query;
use WP_Term;

abstract class Base
{

    /**
     * @var array
     */
    public $error;

    /**
     * @var string
     */
   
    public function __construct($var = "")
    {
        $this->var = $var;
    }

    /**
     * @var array
     */
    public $response = [
        'status_code' => 400,
        'status' => false,
        'message' => ''
    ];

    public $query = null;


    public function error($err = '', $message = '', $status = 400)
    {
        $this->response = [
            'status_code' => 400,
            'status' => false,
            'message' => $message,
            'error' => $this->response['error'] ?? $err
        ];
    }

    public function success($data, $message, $status = 200)
    {
        $this->response = [
            'status_code' => $status,
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }
}
