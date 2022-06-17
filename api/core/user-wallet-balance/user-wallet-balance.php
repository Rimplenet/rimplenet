<?php
/**
 * Create Transfers
 */

class User_Wallet_Balance extends RimplenetGetWalletBalance
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'user-wallet-balance', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_wallet_balance']
        ]);
    }

    public function api_get_wallet_balance(WP_REST_Request $req)
    {
        $this->req = [
            'user_id' => sanitize_text_field($req['user_id']),
            'wallet_id' => sanitize_text_field($req['wallet_id']),
        ];

        $this->getWalletBalance();
        return new WP_REST_Response($this->response, $this->response['status_code']);
    }
}

$CreateTransfers = new CreateTransfers();