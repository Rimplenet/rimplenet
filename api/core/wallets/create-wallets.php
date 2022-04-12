<?php

/**
 * Create wallet
 */


class CreateWallet
{

    /**
     * @var array
     */
    private $error;

    /**
     * @var string
     */
    const WALLET_CAT_NAME = 'RIMPLENET WALLETS';
    const TAXONOMY    = 'rimplenettransaction_type';
    const POST_TYPE   = 'rimplenettransaction';

    /**
     * @var array
     */
    private $response = [
        'status_code' => 400,
        'status' => 'Bad Request',
        'data' => [],
        'error' => []
    ];


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
        $this->req = [
            'wallet_name'           => sanitize_text_field($req['wallet_name']),
            'wallet_id'             => sanitize_text_field($req['wallet_id']),
            'wallet_symbol'         => sanitize_text_field($req['wallet_symbol']),
            'wallet_symbol_pos'     => sanitize_text_field($req['wallet_symbol_pos']),
            'wallet_note'           => sanitize_text_field($req['wallet_note']),
            'wallet_type'           => sanitize_text_field($req['wallet_type']),
            'wallet_decimal'        => $req['wallet_decimal'],
            'max_withdrawal_amount' => $req['max_withdrawal_amount'],
            'min_withdrawal_amount' => $req['min_withdrawal_amount'],
            'inc_i_w_cl'            => $req['inc_in_woocmrce_curr_list'],
            'e_a_w_p'               => $req['enable_as_woocmrce_pymt_wlt'],
            'r_b_b_w'               => sanitize_text_field($req['rules_before_withdrawal'] ?? ''),
            'r_a_b_w'               => sanitize_text_field($req['rules_after_withdrawal'] ?? '')
        ];

        # Check empty fields
        if ($this->checkEmpty())
            return new WP_REST_Response($this->response);

        # Validate decimals or number
        if ($this->notFloatOrNumber())
            return new WP_REST_Response($this->response);

        if($wallet = $this->createWallet())
            return new WP_REST_Response([
                'status_code' => 201,
                'status' => 'created',
                'data' => $wallet
            ]);

        return new WP_Error(500, 'Server Error', []);
    }


    /**
     * Check empty Fields
     * @return mixed
     */
    public function checkEmpty()
    {
        foreach ($this->req as $key => $value) :
            if (is_bool($value) && !$value || is_bool($value) && $value) continue;
            if ($value == '')
                $this->error[$key] = 'Field Cannot be empty';
        endforeach;


        if (!empty($this->error)) {
            $this->response['error'] = $this->error;
            return true;
        }

        return;
    }

    /**
     * validate fields for decimals and numbers
     * @return bool
     */
    public function notFloatOrNumber()
    {
        $this->item = [
            'max_amount' => $this->req['max_withdrawal_amount'],
            'min_amount' => $this->req['min_withdrawal_amount'],
            'wallet_decimal' => $this->req['wallet_decimal']
        ];

        foreach ($this->item as $key => $value) {
            if (is_float($value) || is_int($value)) continue;
            $this->error[$key] = 'Requires a number or decimal';
        }

        extract($this->item);

        $this->checkMinMax('max_amount', $max_amount,);
        $this->checkMinMax('min_amount', $min_amount,);

        if (!empty($this->error)) :
            $this->response['error'] = $this->error;
            return true;
        endif;

        return;
    }

    public function checkMinMax(string $type, int $amount)
    {
        $max_amount = 9999; # minimun wallet withdrawal
        $min_amount = 100; #maximum wallet withdrawal

        $max_mssg = 'Withdrawal amount cannot be greater than ' . $max_amount; # Maximum withdrawal message
        $min_mssg = 'Withdrawal amount cannot be less than ' . $min_amount; # Minnimum withdrawal message
        $gtMssg = 'Equality Error'; # Minnimum withdrawal message

        $repeat = function ($type, $mssg) {
            if (isset($this->error[$type]))
                $this->error[$type] = [...$this->error[$type], $mssg];
            else $this->error[$type] = $mssg;
        };

        # Check max
        if ($amount > $max_amount) {
            $repeat($type, $max_mssg);
        }

        # Check min
        if ($amount < $min_amount) {
            $repeat($type, $min_mssg);
        }

        extract($this->item);

        if($min_amount > $max_amount || $max_amount <= $min_amount ){
            $repeat($type, $gtMssg);
        }
    }

    /**
     * Create Wallet
     */
    public function createWallet()
    {
        extract($this->req);

        $wallet_id = wp_insert_post([
            'post_title'    => $wallet_name,
            'post_content'  => $wallet_note,
            'post_status'   => 'publish',
            'post_type'     => 'rimplenettransaction'
        ]);

        wp_set_object_terms($wallet_id, self::WALLET_CAT_NAME, self::TAXONOMY);

        $wallet_metas = [
            'rimplenet_wallet_name'              => $wallet_name,
            'rimplenet_wallet_id'                => strtolower($wallet_id),
            'rimplenet_wallet_symbol'            => $wallet_symbol,
            'rimplenet_wallet_note'              => $wallet_note,
            'rimplenet_wallet_type'              => $wallet_type,
            'rimplenet_wallet_decimal'           => $wallet_decimal,
            'rimplenet_max_withdrawal_amount'    => $max_withdrawal_amount,
            'rimplenet_min_withdrawal_amount'    => $min_withdrawal_amount,
            'rimplenet_wallet_symbol_position'  => $wallet_symbol_pos,
            'include_in_woocommerce_currency_list'          => $inc_i_w_cl,
            'enable_as_woocommerce_product_payment_wallet'  => $e_a_w_p,
            'rimplenet_rules_after_wallet_withdrawal'       => $r_a_b_w,
            'rimplenet_rules_before_wallet_withdrawal'      => $r_b_b_W
        ];

        foreach ($wallet_metas as $key => $value) {
            update_post_meta($wallet_id, $key, $value);
        }

        return $wallet_metas;
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