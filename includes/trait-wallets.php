<?php

namespace Traits\Wallet;

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

        $user_balance = get_user_meta($user_id, $key, true);
        if (empty($user_balance)) {
            $user_balance = 0;
        }

        //$balance = number_format($user_balance,2);
        $balance = $user_balance;

        return (int) $balance;
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

        $user_balance = get_user_meta($user_id, $key, true);
        if (empty($user_balance)) {
            $user_balance = 0;
        }

        //$balance = number_format($user_balance,2);
        $balance = (int) $user_balance;

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

        $dec = $wallet['wallet_decimal'];
        $symbol = $wallet['wallet_symbol'];
        $symbol_position = $wallet['wallet_symbol_position'];

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

        //   $walllets = $this->getWallet();
        //   $dec = $walllets[$wallet_id]['decimal'];

        //$balance = number_format($balance,$dec);

        return (int) $balance;
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
            $ext_txn_id = rimplenet_txn_exist($user_id, $external_txn_id);
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

        $new_balance  = $user_balance + $amount_to_add;
        $new_balance  = $new_balance;


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

        return $txn_add_bal_id;
    }
}
