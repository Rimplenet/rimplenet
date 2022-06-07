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
        # ================= set fields ============
        $wlt_id  = sanitize_text_field($req['wallet_id']);
        $page      = $req['page'] ?? 1;

        # Check required
        if (!empty($wlt_id)) {
            $this->getWallet($wlt_id);
            return new WP_REST_Response($this->response, $this->response['status_code']);
        }else{
            $this->getWallets();
            return new WP_REST_Response($this->response, $this->response['status_code']);

        }
    }
};
