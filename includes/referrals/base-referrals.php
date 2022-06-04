<?php

namespace Referrals;

abstract class Base
{

    /**
     * @var array
     */
    protected $error;

    /**
     * @var string
     */
    

    /**
     * @var array
     */
    public $response = [
        'status_code' => 400,
        'status' => 'failed',
        'response_message' => '',
        'data' => [],
        'error' => []
    ];

    protected $query = null;

    /**
     * Check empty and required fields
     */
   
    public function __construct($var = "")
    {
        $this->var = $var;
    }
}
