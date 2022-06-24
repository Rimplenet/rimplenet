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
        $this->req = [
            'note'          => sanitize_text_field($req['note'] ?? ''),
            'user_id'       => (int) $req['user_id'],
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'])),
            'request_id'      => sanitize_text_field($req['request_id']),
            'amount' => floatval(-str_replace('-', '', $req['amount'])),
        ];

         $this->createDebits();
         return new WP_REST_Response($this->response, $this->response['status_code']);

    }
};
