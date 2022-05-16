<?php

/**
 * Delete
 */

use Wallets\DeleteWallet\BaseWallet;

$DeleteWallets = new class extends BaseWallet
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
        $wallet = $this->deleteWallet($wallet['wallet']);
        if(!$wallet)
            return new WP_REST_Response($this->response);
        else 
            return new WP_REST_Response($this->response);
        
    }
};
