<?php

class RimplenetCreateDebits extends Debits
{
    public function createDebits(array $param = [])
    {

        $prop = empty($param) ? $this->req : $param;
        extract($prop);
        if (self::requires([
            'user_id'    => "$user_id || int",
            'wallet_id'  => "$wallet_id || string",
            'request_id' => "$request_id || alnum",
            'amount'     => "$amount || amount",
        ])) return;

        do_action('rimplenet_hooks_and_monitors_on_started', 'rimplenet_create_debits', null, $prop);

        if (!$this->getWalletById($wallet_id)) return;

        # verify user exists
        $userToCredit = get_user_by('ID', $user_id);
        if (!$userToCredit) return Res::error(["Unable to reach $user_id"], "Invalid User credentials", 404);


        # Set transaction id
        $txn_id = $user_id . '_' . strtolower($request_id);
        # Set transient key
        $recent_txn_transient_key = "recent_txn_" . $txn_id;

        # check if transaction already exist
        if ($this->debitsExists($txn_id, '', $prop)) return;


        # Chech transient key
        if (isset($GLOBALS[$recent_txn_transient_key])) {
            if ($GLOBALS[$recent_txn_transient_key] == "executing") return;
            if (get_transient($recent_txn_transient_key)) return;
        }


        $key = 'user_withdrawable_bal_' . $wallet_id;
        $user_balance = get_user_meta($user_id, $key, true);

        # check if user balance is a valid int>float>double
        if (!is_numeric($user_balance) && !is_int($user_balance) || !$user_balance) $user_balance = 0;

        # set user balance before time
        $bal_before = $user_balance;
        // return $user_balance_total;

        // $RimplenetWallet = new Rimplenet_Wallets;
        $user_balance_total = $this->get_total_wallet_bal($user_id, $wallet_id);

        $amount = "-$amount";

        $new_balance  = $user_balance + $amount;
        $new_balance  = $new_balance;

        $update_bal = update_user_meta($user_id, $key, $new_balance);

        if ($update_bal) :
            if ($amount > 0) :
                $tnx_type = self::CREDIT;
            else :
                $tnx_type = self::DEBIT;
                $amount = $amount * -1;
            endif;

            $txn_add_bal_id = $this->record_Txn($user_id, $amount, $wallet_id, $tnx_type, 'publish');

            # add note if not empty
            if (!empty($note))  add_post_meta($txn_add_bal_id, 'note', $note);

            add_post_meta($txn_add_bal_id, 'request_id', $request_id);
            add_post_meta($txn_add_bal_id, 'txn_request_id', $txn_id);
            update_post_meta($txn_add_bal_id, 'balance_before', $bal_before);
            update_post_meta($txn_add_bal_id, 'balance_after', $new_balance);

            update_post_meta($txn_add_bal_id, 'total_balance_before', $user_balance_total);
            update_post_meta($txn_add_bal_id, 'total_balance_after', $this->get_total_wallet_bal($user_id, $wallet_id));
            update_post_meta($txn_add_bal_id, 'funds_type', $key);
            update_post_meta($txn_add_bal_id, 'user_id', $user_id);
        else :
            #  action hook
            $param['action_status'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_create_debits',
                null,
                $request = $param
            );
            return Res::error('Unknown Error', "unknown error", 400);
        endif;

        if ($txn_add_bal_id > 0) {
            # action hook
            $param['action_status'] = "success";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_create_debits',
                null,
                $param
            );
            $result = $txn_add_bal_id;
            $get_user=get_user_by('id', $user_id);
            $prop['email']=$get_user->user_email;

            do_action(
                'rimplenet_create_debit_alert_hook',
                'rimplenet_create_debits',
                null,
                $prop
            );
            return Res::success(['transaction_id' => $result, 'user_id' => $user_id], "Transaction Completed", 200);
        } else {
            # action hook
            $param['action_status'] = "failed";
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_create_debits',
                null,
                $param
            );
            return Res::error('Transaction Already Executed', 'Transaction Already Executed', 409);
        }
        return;
    }


    /**
     * Check Transaction Exists
     * @return
     */
    protected function debitsExists($value, string $type = '', array $param = [])
    {
        global $wpdb;
        $value = strtolower($value);
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$value'");
        if ($row) :
            $param['action_status'] = "already_executed";
            $param['transaction_id'] = $row->post_id;
            do_action(
                'rimplenet_hooks_and_monitors_on_finished',
                'rimplenet_create_debits',
                null,
                $param
            );
            Res::error([
                'txn_id' => $row->post_id,
                'exist' => "Transaction already executed"
            ], "Transaction already exists", 409);
            return true;
        endif;
        return false;
    }
}
