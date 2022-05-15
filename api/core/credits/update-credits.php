<?php

$updateCredits = new Class 
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'credits/(?P<txn>[\w]+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'api_update_credits']
        ]);
    }

    public function api_update_credits($txn)
    {
        return $txn['txn'];
    }
};