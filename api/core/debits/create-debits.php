<?php

// use Debits\CreateDebits\BaseDebits;

$createDebits = new class extends RimplenetCreateDebits
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
        
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_create_debits');

        $this->req = [
            'note'          => sanitize_text_field($req['note'] ?? ''),
            'user_id'       => sanitize_text_field($req['user_id'] ?? ''),
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'] ?? '')),
            'request_id'    => sanitize_text_field($req['request_id'] ?? ''),
            'amount'        => sanitize_text_field($req['amount'] ?? ''),
        ];

        $this->createDebits();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
