<?php

namespace Wallets;

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
    const WALLET_CAT_NAME = 'WALLETS';
    const DEBIT = 'DEBIT';
    const CREDIT = 'CREDIT';

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
        'status' => false,
        'message' => ''
    ];

    public $query = null;


    public function error($err = '', $message = '', $status = 400)
    {
        $this->response = [
            'status_code' => 400,
            'status' => false,
            'message' => $message,
            'error' => $this->response['error'] ?? $err
        ];
        return false;
    }

    public function success($data, $message, $status = 200)
    {
        $this->response = [
            'status_code' => $status,
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Check empty Fields
     * @return mixed
     */
    public function checkEmpty(array $req = [])
    {
        // return "hekko";
        $prop = empty($req) ? $this->req : $req;

        
        foreach ($prop as $key => $value) :
            
            if ($key == 'r_a_b_w' || $key == 'r_b_b_w' || $key == 'e_a_w_p' || $key == 'min_withdrawal_amount' || $key == 'max_withdrawal_amount' || $key == 'inc_i_w_cl' || $key == 'wallet_symbol_pos' || $key == 'note' || $key == 'app_id') continue;
            
            if (is_bool($value) && !$value || is_bool($value) && $value) continue;
            
            if ($value == '')
            $this->error[$key] = 'Field Cannot be empty';
        endforeach;

        if (!empty($this->error)) {
            $this->error($this->error, "one or more field is required", 400);
            return true; exit;
        }
        return false;
    }

    public function getWalletById(string $walletId)
    {
        global $wpdb;
        $walletId = sanitize_text_field($walletId);
        $wallet = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$walletId'");

        if ($wallet) :
            return $wallet;
        else :
            $this->error(["Invalid wallet Id"], "Wallet not found", 404);
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
            $this->error[] = 'Wallet Already Exists';
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

    public function queryTxn($page, $type = self::CREDIT)
    {
       $this->query = new WP_Query(
            array(
                'post_type' => 'rimplenettransaction',
                'post_status' => 'any',
                'author' => 'any',
                'posts_per_page' => -1,
                'paged' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    =>  $type
                    ),
                ),
            )
        );
    }
    public function FunctionName(Type $var = null)
    {
        # code...
    }

    protected function postMeta($field = '')
    {
        return get_post_meta($this->id, $field, true);
    }
}
