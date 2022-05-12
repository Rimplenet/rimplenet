<?php

$updateWallet = new Class
{
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'wallets', [
            'methods' => 'PUT',
            'callback' => [$this, 'api_update_wallet']
        ]);
    }

    public function api_update_wallet(WP_REST_Request $req)
    {
        return "Update Route";
    }
};