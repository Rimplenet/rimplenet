<?php
/**
 * Create wallet
 */


class CreateWallet extends RimplenetCreateWallets
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'wallets', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_wallet']
        ]);
    }

    public function api_create_wallet(WP_REST_Request $req)
    {
        do_action('rimplenet_api_request_started', $req, $allowed_roles = ['administrator'], $action = 'rimplenet_create_wallets');
        # Get and store all user inputs
        $this->req = [
            'wallet_name'           => sanitize_text_field($req['wallet_name'] ?? ''),
            'wallet_id'             => sanitize_text_field($req['wallet_id'] ?? ''),
            'wallet_symbol'         => sanitize_text_field($req['wallet_symbol'] ?? ''),
            'wallet_symbol_pos'     => sanitize_text_field($req['wallet_symbol_pos'] ?? 'left'),
            'wallet_note'           => sanitize_text_field($req['wallet_note'] ?? ''),
            'wallet_type'           => sanitize_text_field($req['wallet_type'] ?? ''),
            'wallet_decimal'        => $req['wallet_decimal'] ?? 2,
            'max_withdrawal_amount' => $req['max_withdrawal_amount'] ?? CreateWallet::MAX_AMOUNT,
            'min_withdrawal_amount' => $req['min_withdrawal_amount'] ?? CreateWallet::MIN_AMOUNT,
            // 'inc_i_w_cl'            => $req['inc_in_woocmrce_curr_list'] ?? false,
            // 'e_a_w_p'               => $req['enable_as_woocmrce_pymt_wlt'] ?? false,
            // // 'r_b_b_w'               => sanitize_text_field($req['rules_before_withdrawal'] ?? ''),
            // // 'r_a_b_w'               => sanitize_text_field($req['rules_after_withdrawal'] ?? '')
        ];
        
        $this->createWallet();
        return new WP_REST_Response(self::$response, self::$response['status_code']);
    }
}

$CreateWallet = new CreateWallet();

























// $this->req = [
//     'rimplenet_wallet_name'              => $req['wallet_name'],
//     'rimplenet_wallet_id'                => $req['wallet_id'],
//     'rimplenet_wallet_symbol'            => $req['wallet_symbol'],
//     'rimplenet_wallet_note'              => $req['wallet_note'],  
//     'rimplenet_wallet_type'              => $req['wallet_type'],
//     'rimplenet_wallet_decimal'           => $req['wallet_decimal'],
//     'rimplenet_max_withdrawal_amount'    => $req['max_withdrawal_amount'],
//     'rimplenet_min_withdrawal_amount'    => $req['min_withdrawal_amount'],
//     'include_in_woocommerce_currency_list'          => $req['inc_i_w_cl'],
//     'enable_as_woocommerce_product_payment_wallet'  => $req['e_a_w_p']
// ];