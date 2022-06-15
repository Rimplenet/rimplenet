<?php

namespace Withdrawals;

use WP_Query;
use WP_Term;

abstract class Base
{

    /**
     * @var array
     */
    public $error;

    /**
     * @var string
     */
    const TAXONOMY   = 'rimplenettransaction_type';
    const POST_TYPE  = 'rimplenettransaction';
    const MIN_AMOUNT = 0;
    const MAX_AMOUNT = 999999999;
    const WALLET_CAT_NAME = 'RIMPLENET WALLETS';

    // public function __construct(mixed $var = '')
    public function __construct($var = "")
    {
        $this->var = $var;
    }

    /**
     * @var array
     */
    public $response = [
        'status_code' => 400,
        'status' => 'failed',
        'message' => '',
        'data' => [],
        'error' => []
    ];

    public $query = null;

    /**
     * Check empty Fields
     * @return mixed
     */
    public function checkEmpty(array $req = [])
    {
        // return "hekko";
        $prop = empty($req) ? $this->req : $req;

        foreach ($prop as $key => $value) :

            if ($key == 'r_a_b_w' || $key == 'r_b_b_w' || $key == 'e_a_w_p' || $key == 'min_withdrawal_amount' || $key == 'max_withdrawal_amount' || $key == 'inc_i_w_cl' || $key == 'wallet_symbol_pos') continue;

            if (is_bool($value) && !$value || is_bool($value) && $value) continue;

            if ($value == '')
                $this->error[$key] = 'Field Cannot be empty';
        endforeach;


        if (!empty($this->error)) {
            $this->response['message'] = "One or two fields are required";
            $this->response['error'] = $this->error;
            return true;
        }

        return false;
    }

    public function getWalletById(string $walletId)
    {
        global $wpdb;

        $wallet = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$walletId'");

        if ($wallet) :
            return $wallet;
        else :
            $this->response['status_code'] = 404;
            $this->response['message'] = "Wallet not found";
            $this->response['error'][] = 'Invalid Wallet Id';
            return false;
        endif;
    }

    /**
     * Check if wallet already exists
     */
    public function walletExists()
    {
        global $wpdb;

        $exists = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$this->wallet_id'");

        if ($exists)
            $this->error[] = 'Transaction Already Exists';
        if (!empty($this->error)) return true;
        else return false;
    }



    protected function queryDb($page)
    {

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
    }
}
