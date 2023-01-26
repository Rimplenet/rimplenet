<?php

namespace Traits\Wallet;

use Res;

trait RimplenetWalletTrait
{
    /**
     * Get a user withdrawalble balance
     * @param mixed $user_id id of the user e.g (4/username)
     * @param string $wallet_id id of wallet e.g (USD)
     * @return int 
     */
    public static function get_withdrawable_wallet_bal($user_id, $wallet_id)
    {

        $key = 'user_withdrawable_bal_' . strtolower($wallet_id);

        $balance = get_user_meta($user_id, $key, true);
        if (empty($balance)) {
            $balance = 0;
        }

        // $balance = number_format($balance,2);

        return $balance;
    }

    /**
     * Get a user Nonwithdrawalble balance
     * @param int|string $user_id id of the user e.g (4/username)
     * @param string $wallet_id id of wallet e.g (USD)
     * @return int 
     */
    public static function get_nonwithdrawable_wallet_bal($user_id, $wallet_id)
    {

        $key = 'user_nonwithdrawable_bal_' . strtolower($wallet_id);

        $balance = get_user_meta($user_id, $key, true);
        if (empty($balance)) {
            $balance = 0;
        }

        // $balance = number_format($balance,2);
        return $balance;
    }

    /**
     * Get Formatted amount
     * @param int $amount amount to format
     * @param string $wallet_id id of wallet e.g (USD)
     * @param string $included_data 
     * @return string 
     */
    public function getRimplenetWalletFormattedAmount($amount, $wallet_id, $include_data = '')
    {

        if (empty($include_data)) {
            $include_data = array();
        } else {
            $include_data = explode(",", $include_data);
        }

        $wallet = $this->getWallet($wallet_id);

        $dec = $wallet['wallet_decimal'] ?? 0;
        $symbol = $wallet['wallet_symbol'] ?? '';
        $symbol_position = $wallet['wallet_symbol_position'] ?? '';

        if ($symbol_position == 'right') {
            $disp_info = number_format($amount, $dec) . " " . $symbol;;
        } else {
            $disp_info = $symbol . number_format($amount, $dec);
        }

        if (in_array('wallet_name', $include_data)) {
            $disp_info = $wallet['wallet_name'] . " - " . $disp_info;
        }

        return $disp_info;
    }

    /**
     * Get a user Total wallet balance
     * @param mixed $user_id id of the user e.g (4/username)
     * @param string $wallet_id id of wallet e.g (USD)
     * @return int 
     */
    public function get_total_wallet_bal($user_id, $wallet_id)
    {


        $balance = $this->get_withdrawable_wallet_bal($user_id, $wallet_id) + $this->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);

          $walllets = (object) $this->getWallet($wallet_id);
          $dec = $walllets->wallet_decimal;

        // $balance = number_format($balance, $dec);

        return $balance;
    }

    /**
     * Check if a transaction already exists
     * @param mixed $user_id id of the user e.g (4/username)
     * @param int $external_txn_id
     * @return int 
     */
    public function rimplenet_txn_exist($user_id, $external_txn_id)
    {

        $txn_loop = new WP_Query(
            array(
                'post_type' => 'rimplenettransaction',
                'post_status' => 'any',
                'author' => $user_id,
                'posts_per_page' => 1,
                'tax_query' => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    => array('CREDIT'),
                    ),
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    => array('DEBIT'),
                    ),
                ),
            )
        );

        if ($txn_loop->have_posts()) {
            while ($txn_loop->have_posts()) {

                $txn_loop->the_post();
                $txn_id = get_the_ID();
                $status = get_post_status();

                if (get_post_meta($txn_id, "external_txn_id", true) == $external_txn_id) {
                    return $txn_id;
                }
            }
        }
    }

    /**
     * Record a transaction
     * @param mixed $user_id id of the user e.g (4/username)
     * @param int $amount amount to record
     * @param string $wallet_id id of wallet e.g (USD)
     * @param string $txn_type type of transaction to record (Dr/Cr)
     * @param string $status status of transaction
     * @return mixed 
     */
    public function record_Txn($user_id, $amount, $wallet_id, $tnx_type, $status = 'pending')
    {

        $user_info = get_user_by('ID', $user_id);
        $walllet = $this->getWallet($wallet_id);
        $decimal = $walllet['wallet_decimal'];
        $amount_formatted = number_format($amount, $decimal);
        $wallet_symbol = $walllet['wallet_symbol'];
        $wallet_name = $walllet['wallet_name'];

        $post_title = 'TRANSACTION by ' . $user_info->user_login . ', Type: ' . $tnx_type . ', Wallet Info: ' . $wallet_symbol . '' . $amount_formatted . ' ' . $wallet_name . '  on ' . date("l jS \of F Y @ h:i:s A");

        $post_content = 'Amount:' . $amount;

        $new_txn_args = array(
            'post_author' => $user_info->ID,
            'post_type' => 'rimplenettransaction',
            'post_title'    => wp_strip_all_tags($post_title),
            'post_content'  => $post_content,
            'post_status'   => $status,
            'meta_input' => array(
                'amount' => $amount,
                'currency' => strtolower($wallet_id),
                'txn_type' => $tnx_type
            ),
        );




        $new_txn = wp_insert_post($new_txn_args);


        if ($tnx_type == 'BUY' or $tnx_type == 'SELL') {
            $amount_usd = $amount;
            update_post_meta($new_txn, 'amount_usd', $amount_usd);

            $amount_coin = $amount;
            update_post_meta($new_txn, 'amount_coin', $amount_coin);

            $rate_1usd_to_coin = get_option('rate_1btc_to_usd_rate', 9000);
            $amount_btc = $amount_usd / $rate_1usd_to_coin;
            update_post_meta($new_txn, 'amount_btc', $amount_btc);
        }

        if (is_int($new_txn)) {
            wp_set_object_terms($new_txn, $tnx_type, 'rimplenettransaction_type', true);

            return $new_txn;
        }

        wp_reset_postdata();
    }

    /**
     * Add mature funds to wallet
     * @param mixed $user_id id of the user e.g (4/username)
     * @param int $amount amount to record
     * @param string $wallet_id id of wallet e.g (USD)
     * @param string $note a description of the transaction
     * @param array $tags 
     * @return mixed 
     */
    public function add_user_mature_funds_to_wallet($user_id, $amount_to_add, $wallet_id, $note = '', $tags = [])
    {

        if (!empty($tags['txn_ref'])) {
            $external_txn_id = $tags['txn_ref'];
            $ext_txn_id = $this->rimplenet_txn_exist($user_id, $external_txn_id);
            if ($ext_txn_id > 1) {
                return $ext_txn_id;
            }
            $note .= " ~ #$external_txn_id";
        }

        if ($amount_to_add === 0) {
            return; // don't transact 0
        }



        $key = 'user_withdrawable_bal_' . strtolower($wallet_id);
        $user_balance = get_user_meta($user_id, $key, true);

        if (!is_numeric($user_balance) and !is_int($user_balance)) {
            $user_balance = 0;
        }



        $bal_before = $user_balance;
        $user_balance_total = $this->get_total_wallet_bal($user_id, $wallet_id);



        // var_dump($amount_to_add, $user_balance);
        // die("DS");
        $new_balance  = intval($user_balance) + intval($amount_to_add);
        $new_balance  = $new_balance;
        // var_dump($new_balance);
        do_action("before_add_user_mature_funds_to_wallet", $user_id, $amount_to_add, $wallet_id, $note, $tags);




        update_user_meta($user_id, $key, $new_balance);


        if ($amount_to_add > 0) {
            $tnx_type = 'CREDIT';
        } else {
            $tnx_type = 'DEBIT';
            $amount_to_add = $amount_to_add * -1;
        }


        $txn_add_bal_id = $this->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');


        if (!empty($note)) {
            add_post_meta($txn_add_bal_id, 'note', $note);
        }

        update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
        update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

        update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
        update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id, $wallet_id));

        update_post_meta($txn_add_bal_id, 'funds_type', $key);
        if (!empty($tags['txn_ref'])) {
            update_post_meta($txn_add_bal_id, 'external_txn_id', $external_txn_id);
        }

        do_action("after_add_user_mature_funds_to_wallet", $txn_add_bal_id, $user_id, $amount_to_add, $wallet_id, $note, $tags, $tnx_type);

        // die("sd");
        return $txn_add_bal_id;
    }


    public function add_user_immature_funds_to_wallet($user_id, $amount_to_add, $wallet_id, $note = '', $tags = [])
    {

        $key = 'user_nonwithdrawable_bal_' . strtolower($wallet_id);
        $user_balance = get_user_meta($user_id, $key, true);


        if (!is_numeric($user_balance) and !is_int($user_balance)) {
            $user_balance = 0;
        }

        if ($amount_to_add === 0) {
            return; // don't transact 0
        }
        $bal_before = $user_balance;
        $user_balance_total = $this->get_total_wallet_bal($user_id, $wallet_id);

        $new_balance  = $user_balance + $amount_to_add;
        $new_balance  = $new_balance;

        do_action("before_add_user_immature_funds_to_wallet", $user_id, $amount_to_add, $wallet_id, $note, $tags);

        update_user_meta($user_id, $key, $new_balance);


        if ($amount_to_add > 0) {
            $tnx_type = 'CREDIT';
        } else {
            $tnx_type = 'DEBIT';
            $amount_to_add = $amount_to_add * -1;
        }

        $txn_add_bal_id = $this->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');

        if (!empty($note)) {
            add_post_meta($txn_add_bal_id, 'note', $note);
        }
        update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
        update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

        update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
        update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id, $wallet_id));

        update_post_meta($txn_add_bal_id, 'funds_type', $key);

        do_action("after_add_user_immature_funds_to_wallet", $txn_add_bal_id, $user_id, $amount_to_add, $wallet_id, $note, $tags, $tnx_type);

        return $txn_add_bal_id;
    }

    public function getWalletById(string $walletId)
    {
        global $wpdb;
        $walletId = sanitize_text_field($walletId);
        $wallet = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='rimplenet_wallet_id' AND meta_value='$walletId' OR post_id = '$walletId' AND meta_key='rimplenet_wallet_id'");

        if ($wallet) :
            return $wallet;
        else :
            // Res::error(["Invalid wallet Id"], "Wallet not found", 404);
            return false;
        endif;
    }


    public function getWallet(string $walletId)
    {
        $wallet = $this->getWalletById($walletId);

        if (!$wallet) :
            return false;
        else :
            $wallet = get_post($wallet->post_id);
            $walletData = $this->walletFormat($wallet);
            //    Res::success($walletData, "Wallet Retrieved");
            return $walletData;
        endif;
    }

    private function walletFormat($wallet)
    {
        $this->id = $wallet->ID;

        $min_withdrawal = $this->postMeta('rimplenet_min_withdrawal_amount');
        $min_withdrawal == '' && $min_withdrawal  = Utils::MIN_AMOUNT;

        $max_withdrawal = $this->postMeta('rimplenet_max_withdrawal_amount');
        $max_withdrawal == '' && $max_withdrawal  = Utils::MAX_AMOUNT;

        $inc_wlt_curr_list = $this->postMeta('include_in_woocommerce_currency_list');
        !$inc_wlt_curr_list ? $inc_wlt_curr_list = false :  $inc_wlt_curr_list = true;

        $enb_as_wcclst = $this->postMeta('enable_as_woocommerce_product_payment_wallet');
        !$enb_as_wcclst ? $enb_as_wcclst = false : $enb_as_wcclst = true;

        $res = [
            'post_id' => $this->id,
            'wallet_id'        => $this->postMeta('rimplenet_wallet_id'),
            'wallet_name'      => $wallet->post_title,
            "wallet_symbol"    => $this->postMeta('rimplenet_wallet_symbol'),
            "wallet_max_wdr_amount"    => $max_withdrawal,
            "wallet_min_wdr_amount"    => $min_withdrawal,
            "wallet_symbol_position"     => $this->postMeta('rimplenet_wallet_symbol_position'),
            "wallet_decimal"           => $this->postMeta('rimplenet_wallet_decimal'),
            'wallet_note'              => $this->postMeta('rimplenet_wallet_note'),
            'wallet_type'              => $this->postMeta('rimplenet_wallet_type')
            // 'in_wc_curr_list'          => $inc_wlt_curr_list,
            // 'enbl_as_wc_prdt_pymt_wlt'             => $enb_as_wcclst,
            // "include_in_withdrawal_form"           => "yes",
            // "rules_after_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_after_wallet_withdrawal'),
            // "rules_before_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_before_wallet_withdrawal'),
            // "action" => array(
            //     "deposit" => "yes",
            //     "withdraw" => "yes",
            // )
        ];
        return $res;
    }
    function rimplenet_fund_user_wallet($request_id, $user_id, $amount_to_add, $wallet_id, $note = '', $tags = [], $extra_data = '')
    {
        global $wpdb;
        $error  =  'Please try again';

        $txn_request_id = $user_id . "_" . $request_id;
        $recent_txn_transient_key = "recent_txn_" . $txn_request_id;

        if ($GLOBALS[$recent_txn_transient_key] == "executing") return Res::error(['msg' => 'please_try_again'], $error);

        if (get_transient($recent_txn_transient_key)) return Res::error(['msg' => 'please_try_again'], $error);

        $GLOBALS[$recent_txn_transient_key] = 'executing';
        set_transient($recent_txn_transient_key, 'executing', 60);

        $inputed_data = array(
            "request_id" => $request_id, "user_id" => $user_id, "amount_to_add" => $amount_to_add, "wallet_id" => $wallet_id
        );

        $empty_input_array = array();
        //Loop & Find out empty inputs
        foreach ($inputed_data as $input_key => $single_data) {
            if (empty($single_data)) {
                $empty_input_array[$input_key]  = "field_required";
            }
        }

        //RUN CHECKS
        $result = array();
        $additonal_result = array();

        $row_result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$txn_request_id'");
        if (!empty($row_result)) { //it means txn has already exist

            $funds_id = $row_result->post_id;
            // $status = "transaction_already_executed";

            $response_message = "Transaction Already Executed";
            $data = array("transaction_id" => $funds_id);

            return Res::error($data, $response_message);
        } elseif (!empty($empty_input_array)) {
            //if atleast one required input is empty
            $response_message = "One or more input field is required";
            // $data = array("msg" => $empty_input_array);

            return Res::error($empty_input_array, $response_message);
        } elseif ($amount_to_add == 0) {

            $response_message = "Amount should not be Zero";
            $data = array("mssg" => "Amount is zero",);

            return Res::error($data, $response_message);
        } else { // ALL GOOD, PROCEED WITH OPERATION
            $key = 'user_withdrawable_bal_' . strtolower($wallet_id);
            $user_balance = get_user_meta($user_id, $key, true);
            if (!is_numeric($user_balance) and !is_int($user_balance)) {
                $user_balance = 0;
            }

            $bal_before = $user_balance;
            $user_balance_total = $this->get_total_wallet_bal($user_id, $wallet_id);

            $new_balance  = $user_balance + $amount_to_add;
            $new_balance  = $new_balance;

            $update_bal = update_user_meta($user_id, $key, $new_balance);
            if ($update_bal) { //balance successfully updated
                if ($amount_to_add > 0) {
                    $tnx_type = "CREDIT";
                } else {
                    $tnx_type = "DEBIT";
                    $amount_to_add = $amount_to_add * -1;
                }

                $txn_add_bal_id = $this->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');

                if (!empty($note)) {
                    add_post_meta($txn_add_bal_id, 'note', $note);
                }
                add_post_meta($txn_add_bal_id, 'request_id', $request_id);
                add_post_meta($txn_add_bal_id, 'txn_request_id', $txn_request_id);
                update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
                update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

                update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
                update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id, $wallet_id));
                update_post_meta($txn_add_bal_id, 'funds_type', $key);
            } else {
                $status = "unknown_error";
                $response_message = "Unknown Error";
                $data = array();
            }
        }

        if ($txn_add_bal_id > 0) {

            // return $txn_add_bal_id;
            return array(
                "status" => true,
                "message" => 'Transaction successful',
                "data" => ['transaction_id' => $txn_add_bal_id]
            );
        } else {

            return Res::error($data, $response_message);
        }

        return $result;
    }
    function rimplenet_fund_user_mature_wallet($request_id, $user_id, $amount_to_add, $wallet_id, $note = '', $tags = [], $extra_data = '')
    {
        global $wpdb;

        $txn_request_id = $user_id . "_" . $request_id;
        $recent_txn_transient_key = "recent_txn_" . $txn_request_id;

        if ($GLOBALS[$recent_txn_transient_key] == "executing") {
            return;
        }
        if (get_transient($recent_txn_transient_key)) {
            return;
        }

        $GLOBALS[$recent_txn_transient_key] = 'executing';
        set_transient($recent_txn_transient_key, 'executing', 60);

        $inputed_data = array(
            "request_id" => $request_id, "user_id" => $user_id, "amount_to_add" => $amount_to_add, "wallet_id" => $wallet_id
        );

        $empty_input_array = array();
        //Loop & Find out empty inputs
        foreach ($inputed_data as $input_key => $single_data) {
            if (empty($single_data)) {
                $empty_input_array[$input_key]  = "field_required";
            }
        }

        //RUN CHECKS
        $result = array();
        $additonal_result = array();

        $row_result = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$txn_request_id'");
        if (!empty($row_result)) { //it means txn has already exist

            $funds_id = $row_result->post_id;
            $status = "transaction_already_executed";
            $response_message = "Transaction Already Executed";
            $data = array("txn_id" => $funds_id);
        } elseif (!empty($empty_input_array)) {
            //if atleast one required input is empty
            $status = "one_or_more_input_required";
            $response_message = "One or more input field is required";
            $data = array("error" => $empty_input_array);
        } elseif ($amount_to_add == 0) {
            $status = "amount_is_zero";
            $response_message = "Amount should not be Zero";
            $data = array("error" => "Amount is zero");
        } else { // ALL GOOD, PROCEED WITH OPERATION
            $key = 'user_withdrawable_bal_' . strtolower($wallet_id);
            $user_balance = get_user_meta($user_id, $key, true);
            if (!is_numeric($user_balance) and !is_int($user_balance)) {
                $user_balance = 0;
            }

            $bal_before = $user_balance;
            $user_balance_total = $this->get_total_wallet_bal($user_id, $wallet_id);

            $new_balance  = $user_balance + $amount_to_add;
            $new_balance  = $new_balance;

            $update_bal = update_user_meta($user_id, $key, $new_balance);
            if ($update_bal) { //balance successfully updated
                if ($amount_to_add > 0) {
                    $tnx_type = "CREDIT";
                } else {
                    $tnx_type = "DEBIT";
                    $amount_to_add = $amount_to_add * -1;
                }

                $txn_add_bal_id = $this->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');

                if (!empty($note)) {
                    add_post_meta($txn_add_bal_id, 'note', $note);
                }
                add_post_meta($txn_add_bal_id, 'request_id', $request_id);
                add_post_meta($txn_add_bal_id, 'txn_request_id', $txn_request_id);
                update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
                update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

                update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
                update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id, $wallet_id));
                update_post_meta($txn_add_bal_id, 'funds_type', $key);
            } else {
                $status = "unknown_error";
                $response_message = "Unknown Error";
                $data = array();
            }
        }

        if ($txn_add_bal_id > 0) {
            $result = $txn_add_bal_id;
        } else {
            $result = array(
                "status" => $status,
                "message" => $response_message,
                "data" => $data
            );
            $result = json_encode($result);
        }

        return $result;
    }

    /**
     * 
     */
    public function ConvertRimplenetAmount($amount, $wallet_from, $wallet_to)
    {

        $base_wallet = get_option("rimplenet_website_base_wallet", "rimplenetcoin");

        $key_from_wallet_to_base_wallet = "rate_1_" . $wallet_from . "_to_website_base_wallet";
        $value_from_wallet_to_base_wallet = get_option($key_from_wallet_to_base_wallet, 1);

        $key_to_wallet_to_base_wallet = "rate_1_" . $wallet_to . "_to_website_base_wallet";
        $value_to_wallet_to_base_wallet = get_option($key_to_wallet_to_base_wallet, 1);

        $amount_to_base_wallet = $amount * $value_from_wallet_to_base_wallet; // convert the amt (in wallet_from) to website base cur value
        $amount_to_wallet_to = $amount_to_base_wallet / $value_to_wallet_to_base_wallet; // convert from website base cur value TO provided WALLET_TO

        $amt_converted = $amount_to_wallet_to;

        return $amt_converted;
    }
    
    protected function postMeta($field = '')
    {
        return get_post_meta($this->id, $field, true);
    }
}
