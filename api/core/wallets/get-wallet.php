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
        register_rest_route('/rimplenet/v1', 'wallets/(?P<wallet>[a-zA-Z0-9_]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'retrieve_wallet']
        ]);
    }

    public function retrieve_wallet(WP_REST_Request $req)
    {
        // $allowed_roes = []; 
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_get_wallets');

        # ================= set fields ============
        $wlt_id  = sanitize_text_field($req['wallet'] ?? '');

        # Check required
        if ($wlt_id !== '') :
            # if wallet id is not empty return the wallet
            $this->getWallet($wlt_id);
            return new WP_REST_Response(self::$response, self::$response['status_code']);
        endif;
    }
};
