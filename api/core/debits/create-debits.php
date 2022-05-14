<?php

$createDebits = new class
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'debits', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_debits']
        ]);
    }

    public function api_create_debits(WP_REST_Request $req)
    {
        $this->req = [
            'request_id' => (int) $req['req_id'],
            'user_id' => (int) $req['user_id'],
            'amount_to_add' => floatval($req['amount_to_add']),
            'wallet_id' => sanitize_text_field(strtolower($req['wallet_id'])),
            'note' => sanitize_text_field($req['note'])
        ];

        
    }

};