<?php

// use Credits\CreateCredits\BaseCredits;

$createCredits = new class extends RimplenetCreateCredits
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'credits', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_credits']
        ]);
    }

    public function api_create_credits(WP_REST_Request $req)
    {

        $this->req = [
            'note'          => sanitize_text_field($req['note'] ?? ''),
            'user_id'       => sanitize_text_field($req['user_id']),
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'])),
            'request_id'      => sanitize_text_field($req['request_id']),
            'amount' =>     floatval(str_replace('-', '',$req['amount'])),
        ];

            $this->createCredits();
            return new WP_REST_Response($this->response, $this->response['status_code']);
       

    }

    public function validateAmount($amount)
    {
        return preg_match('/^\d.+/', $amount);
    }
};
