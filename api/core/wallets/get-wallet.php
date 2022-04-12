<?php

class RetrieveWallet
{

    protected array $is_empty;
    protected $response;
    protected $query = null;

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'get-wallet', [
            'method' => 'GET',
            'callback' => [$this, 'retrieve_wallet']
        ]);
    }

    public function retrieve_wallet(WP_REST_Request $req)
    {
        # ================= set fields ============
        $opts = [
            'user_id'   => $req['user_id']  ?? 'any',
            'wlt_type'  => $req['wlt_type'] ?? '',
            'page'      => $req['page']     ?? 1
        ];


        #=========================== Check for empty fields ==========================
        foreach ($opts as $key => $value) :
            if ($value == '')
                $this->is_empty[$key] = $key . ' field is Required';
        endforeach;

        #=========================================
        if (!empty($this->is_empty)) :
            $this->response = [
                'code' => 400,
                'message' => "Looks like you some credentials are empty",
                'payload' => $this->is_empty
            ];
        else :
            extract($opts);
            $page == ''
                ? $page = sanitize_text_field($_GET['page'])
                : $page = 1;

            if (isset($user_id) && $user_id !== '' || $user_id !== 'any')
                $this->query = new WP_Query([
                    'post_type'     => 'rimplenettransaction',
                    'post_status'   => 'publish',
                    'post_per_page' => -1,
                    'author'        => $user_id,
                    'paged'         => $page,
                    'tax_query'     => array([
                        'taxonomy'  => 'rimplenettransaction_type',
                        'field'     => 'name',
                        'terms'     => 'RIMPLENET WALLETS'
                    ])
                ]);

            else
                $this->query = new WP_Query([
                    'post_type'     => 'rimplenettransaction',
                    'post_status'   => 'publish',
                    'post_per_page' => -1,
                    'paged'         => $page,
                    'tax_query'     => array([
                        'taxonomy'  => 'rimplenettransaction_type',
                        'field'     => 'name',
                        'term'      => 'RIMPLENET WALLET'
                    ])
                ]);
        endif;

        #===============================================================
        #*************************************
        if ($this->query && $this->query->have_posts()) {
            $data = $this->formatWalletData();

            $this->response = [
                'code' => 200,
                'message' => 'Wallet was successfully retrieved',
                'payload' => $data
            ];
        } else {
            if(!$this->response['code'])
            $this->response = [
                'code' => 406,
                'message' => 'Failed to retrieve wallet',
                'payload' => []
            ];
        }

        #===========================================================
        if ($this->response['code'] == 200) :
            return new WP_REST_Response($this->response);
        else :
            extract($this->response);
            return new WP_Error($code, $message, $payload);
        endif;
    }


    /**
     * Format wallet
     * @return array
     */
    public function formatWalletData()
    {
        $res = [];
        $posts = $this->query->get_posts();
        foreach ($posts as $value) :
            $this->id = $value->ID;

            $max_withdrawal = $this->postMeta('rimplenet_min_withdrawal_amount');
            $max_withdrawal == '' && $max_withdrawal  = 0;

            $min_widhdrawal = $this->postMeta('rimplenet_max_withdrawal_amount');
            $min_widhdrawal == '' && $min_widhdrawal  = INF;

            $inc_in_w_f     = $this->postMeta('include_in_withdrawal_form');

            $res[] = [
                'id'        => $this->postMeta('rimplenet_wallet_id'),
                'name'      => $value->post_title,
                "symbol"    => $this->postMeta('rimplenet_wallet_symbol'),
                "symbol_position"     => $this->postMeta('rimplenet_wallet_symbol_position'),
                "value_1_to_base_cur" => 0.01,
                "value_1_to_usd"    => 1,
                "value_1_to_btc"    => 0.01,
                "decimal"           => $this->postMeta('rimplenet_wallet_decimal'),
                "max_wdr_amount"    => $max_withdrawal,
                "min_wdr_amount"    => $min_widhdrawal,
                "include_in_withdrawal_form"           => "yes",
                "include_in_woocommerce_currency_list" =>  $this->postMeta('include_in_woocommerce_currency_list'),
                "action" => array(
                    "deposit" => "yes",
                    "withdraw" => "yes",
                )
            ];

        endforeach;
        return $res;
    }

    public function postMeta($field)
    {
        return get_post_meta($this->id, $field, true);
    }
}

$RetrieveWallet = new RetrieveWallet();
