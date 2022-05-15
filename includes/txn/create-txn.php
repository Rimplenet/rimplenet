<?php

namespace Txn\CreateTxn;

use Rimplenet_Wallets;
use Txn\Base;

abstract class BaseTxn extends Base
{
    protected function createDebit(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        # Set transaction id
        $txn_id = $user_id . '_' . $request_id;
        # Set transient key
        $recent_txn_transient_key = "recent_txn_" . $txn_id;


        # Chech transient key
        if ($GLOBALS[$recent_txn_transient_key] == "executing") return;
        if (get_transient($recent_txn_transient_key)) return;

        # check if transaction already exist
        // $this->txnExists($txn_id);

        $key = 'user_withdrawable_bal_' . $wallet_id;
        $user_balance = get_user_meta($user_id, $key, true);

        # check if user balance is a valid int>float>double
        if (!is_numeric($user_balance) && !is_int($user_balance) || !$user_balance) $user_balance = 0;

        # set user balance before time
        $bal_before = $user_balance;
        // return $user_balance_total;

        $RimplenetWallet = new Rimplenet_Wallets;
        $user_balance_total = $RimplenetWallet->get_total_wallet_bal($user_id, $wallet_id);

        $new_balance  = $user_balance + $amount_to_add;
        $new_balance  = $new_balance;

        $update_bal = update_user_meta($user_id, $key, $new_balance);

        if ($update_bal) :
            if ($amount_to_add > 0) :
                $tnx_type = self::CREDIT;
            else :
                $tnx_type = self::DEBIT;
                $amount_to_add = $amount_to_add * -1;
            endif;

            $txn_add_bal_id = $RimplenetWallet->record_Txn($user_id, $amount_to_add, $wallet_id, $tnx_type, 'publish');

            # add note if not empty
            if (!empty($note))  add_post_meta($txn_add_bal_id, 'note', $note);

            add_post_meta($txn_add_bal_id, 'request_id', $request_id);
            add_post_meta($txn_add_bal_id, 'txn_request_id', $txn_id);
            update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
            update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

            update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
            update_post_meta($txn_add_bal_id, 'total_balance_after', $RimplenetWallet->get_total_wallet_bal($user_id, $wallet_id));
            update_post_meta($txn_add_bal_id, 'funds_type', $key);
        else :
            $this->response['response_message'] = 'Unknown Error';
            return false;
        endif;

        if ($txn_add_bal_id > 0) {
            $result = $txn_add_bal_id;
            return $result;
        } else {
            $this->response['status_code'] = 409;
            $this->response['response_message'] = "Transaction Already Executed";
            return false;
        }
        return;
    }


    /**
     * Check Transaction Exists
     * @return
     */
    protected function txnExists($value, string $type = '')
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$value'");
        if ($row) :
            $this->response['status_code'] = 409;
            $this->response['response_message'] = "Transaction Already Executed";
            $this->response['data']['txn_id'] = $row->post_id;
            return false;
            exit;
        endif;
        return true;
    }
}
