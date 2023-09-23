<?php
/**
 * Create wallet
 */


class CreateWithdrawals extends RimplenetCreateWithdrawals
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'withdrawals', [
            'methods' => 'POST',
            'callback' => [$this, 'api_create_withdrawal']
        ]);
    }

    public function api_create_withdrawal(WP_REST_Request $req)
    {

        // $request_id, $user_id, $amount_to_withdraw, $wallet_id, $wdr_dest, $wdr_dest_data, $note='Withdrawal',$extra_data=''
        # Get and store all user inputs
        $this->req = [
            'request_id'           => sanitize_text_field($req['request_id'] ?? ''),
            'user_id'             => sanitize_text_field($req['user_id'] ?? ''),
            'amount_to_withdraw'         => sanitize_text_field($req['amount_to_withdraw'] ?? ''),
            'wallet_id'     => sanitize_text_field($req['wallet_id'] ?? ''),
            'wdr_dest'           => sanitize_text_field($req['wdr_dest'] ?? ''),
            'wdr_dest_data'           => sanitize_text_field($req['wdr_dest_data'] ?? ''),
            'note'        => sanitize_text_field($req['note']) ?? 'Withdrawal',
            'extra_data' => sanitize_text_field($req['extra_data']) ?? '',
        ];
        
        $this->createWithdrawals();
        return new WP_REST_Response($this->response, $this->response['status_code']);
    }
}

$CreateWithdrawals = new CreateWithdrawals();

























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