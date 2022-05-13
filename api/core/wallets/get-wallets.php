<?php

use Wallets\GetWallets\BaseWallet;

$RetrieveWallet = new class extends BaseWallet
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
        if ($wlt_id !== '') :
            # if wallet id is not empty return the wallet
            $wallet = $this->getWallet($wlt_id);
            if (!$wallet)
                return new WP_REST_Response($this->response); # if wallet id is invalid
            else
                return new WP_REST_Response($wallet); # return the wallet data if valid
        else :
            # return valid wallets if wallet id is not provided
            $this->query = new WP_Query([
                'post_type' => self::POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'paged' => $page,
                'tax_query' => array([
                    'taxonomy' => self::TAXONOMY,
                    'field'    => 'name',
                    'terms'    => static::WALLET_CAT_NAME,
                ]),
            ]);
            // return $this->query->posts;

            if ($wallet = $this->getWallets())
                return new WP_REST_Response($wallet);
            else
                return new WP_REST_Response($this->response);
        endif;
    }
};
