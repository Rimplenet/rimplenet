<?php

// use Wallets\GetWallets\RimplenetGetWallets;

$RetrieveWallet = new class extends RimplenetGetWallets
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);

        
    }
    
    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'wallets', [
            'methods' => 'GET',
            'callback' => [$this, 'retrieve_wallet']
        ]);

    }

    public function retrieve_wallet(WP_REST_Request $req)
    {
        // $allowed_roes = []; 
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_get_wallets');

        $this->getWallets();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
};
