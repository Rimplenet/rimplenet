<?php

$RetrieveWallet = new Class
{

    private array $error;

    private $response = [
        'status_code' => 400,
        'status' => 'Bad Request',
        'data' => [],
        'error' => []
    ];

    protected $query = null;

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
        $opts = [
            'wlt_id'  => sanitize_text_field($req['wallet_id'] ?? 'any'),
            'page'      => $req['page']     ?? 1
        ];

        // return $opts;


        #=========================== Check for empty fields ==========================
        if($opts['wlt_id'] == '')
                $this->error['wlt_id'] = 'field is Required';

        #=========================================
        if (!empty($this->is_empty)) :
            $this->response['error'] = $this->error;
        else :
            extract($opts);
            $page == ''
                ? $page = sanitize_text_field($page)
                : $page = 1;

            if (isset($wlt_id) && $wlt_id !== '' && $wlt_id !== 'any'):

                // return $this->query;
                #===============================================================
                #*************************************
                if ($this->getWallet($wlt_id)) {
                    $data = $this->getWallet($wlt_id);
        
                    $this->response = [
                        'status_code' => 200,
                        'status' => 'Wallet was successfully retrieved',
                        'data' => $data
                    ];
                } else {
                    $this->response = [
                        'status_code' => 406,
                        'error' => []
                    ];
                }
            endif;
        endif;


        #===========================================================
        if ($this->response['status_code'] == 200) :
            return new WP_REST_Response($this->response);
        else :
            extract($this->response);
            return new WP_Error($status_code, $status, $error);
        endif;
    }


    /**
     * Format wallet
     * @return object
     */
    public function getWallet(int $id)
    {
        $res = [];
        $wallet = get_post($id);
            $this->id = $wallet->ID;

            $max_withdrawal = $this->postMeta('rimplenet_min_withdrawal_amount');
            $max_withdrawal == '' && $max_withdrawal  = 0;

            $min_widhdrawal = $this->postMeta('rimplenet_max_withdrawal_amount');
            $min_widhdrawal == '' && $min_widhdrawal  = INF;

            $inc_wlt_curr_list = $this->postMeta('include_in_woocommerce_currency_list');
            !$inc_wlt_curr_list ? $inc_wlt_curr_list = false :  $inc_wlt_curr_list = true;

            $enb_as_wcclst = $this->postMeta('enable_as_woocommerce_product_payment_wallet');
            !$enb_as_wcclst ? $enb_as_wcclst = false : $enb_as_wcclst = true;

            $res = [
                'wallet_id'        => $this->postMeta('rimplenet_wallet_id'),
                'wallet_name'      => $wallet->post_title,
                "wallet_symbol"    => $this->postMeta('rimplenet_wallet_symbol'),
                "wallet_max_wdr_amount"    => $max_withdrawal,
                "wallet_min_wdr_amount"    => $min_widhdrawal,
                "wallet_symbol_position"     => $this->postMeta('rimplenet_wallet_symbol_position'),
                "wallet_decimal"           => $this->postMeta('rimplenet_wallet_decimal'),
                'wallet_note'              => $this->postMeta('rimplenet_wallet_note'),
                'in_wc_curr_list'          => $inc_wlt_curr_list,
                'enbl_as_wc_prdt_pymt_wlt'             => $enb_as_wcclst,
                "include_in_withdrawal_form"           => "yes",
                "rules_after_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_after_wallet_withdrawal'),
                "rules_before_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_before_wallet_withdrawal'),
                "action" => array(
                    "deposit" => "yes",
                    "withdraw" => "yes",
                )
            ];
        return $res;
    }

    public function postMeta($field)
    {
        return get_post_meta($this->id, $field, true);
    }
};
