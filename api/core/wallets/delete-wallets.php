<?php

/**
 * Delete
 */
$DeleteWallets = new class extends RimplenetDeleteWallets
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'wallets/(?P<wallet>[\w]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'api_delete_wallet']
        ]);
    }

    public function api_delete_wallet($wallet)
    {
        do_action('rimplenet_api_request_started', $wallet, $allowed_roles = ['administrator'], $action = 'rimplenet_delete_wallets');
        
        $wallet = $this->deleteWallet($wallet['wallet']);
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
