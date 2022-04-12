<?php

/**
 * Delete
 */

$DeleteWallets = new Class
 {
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {   
        register_rest_route('/rimplenet/v1', 'wallets/(?P<wallet>[\d\w]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_wallet']
        ]);
    }

    public function api_delete_wallet($wallet)
    {
        return $wallet['wallet'];
    }
 };