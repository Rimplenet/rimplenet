<?php

use Traits\Wallet\RimplenetWalletTrait;
use Transfers\Transfers;

/**
 * Create Transfers
 */
class RimplenetCreateTransfer extends Transfers
{
    use RimplenetWalletTrait;

    public function transfer($req = [])
    {
        $prop = empty($req) ? $this->req : $req;
        $this->req = $prop;
        extract($prop);
        if($this->checkEmpty()) return false;

        # Get current loggedin user (user from)
        $current_user = get_user_by('ID', $user_id);
        $current_user_id  = $current_user->ID;

        # Get Other user (user to)
        $user_transfer_to = get_user_by('login', $transfer_to_user);
        $transfer_to_user_id  = $user_transfer_to->ID;

        $min_transfer_amt = 0;
        # Get user balance
        $user_transfer_bal = self::get_withdrawable_wallet_bal($user_id, $wallet_id);
        $user_non_transfer_bal = self::get_nonwithdrawable_wallet_bal($user_id, $wallet_id);

        # Get user wallet
        $walllet = $this->getWallet($wallet_id);
        $dec = $wallet['wallet_decimal'];
        $symbol = $wallet['wallet_symbol'];
        $name = $walllet['wallet_name'];
        $balance = $symbol . number_format($user_transfer_bal, $dec);

        $exec = $this->executeTransfer([
            'user_id' => $user_id,
            'wallet_id' => $wallet_id,
            'amount_to_transfer' => $amount_to_transfer,
            'transfer_to_user' => $transfer_to_user,
            'user_transfer_bal' => $user_transfer_bal,
            'transfer_to_user_id' => $transfer_to_user_id,
            'user_non_transfer_bal' => $user_non_transfer_bal,
            'current_user_id' => $current_user_id,
            'current_user' => $current_user,
            'min_transfer_amt' => $min_transfer_amt,
            "user_transfer_to" => $user_transfer_to
        ]);
    }


    public function executeTransfer($param = [])
    {
        extract($param);
        if (empty($user_id) || empty($amount_to_transfer) || empty($wallet_id) || empty($transfer_to_user)) :
            $this->response['error'][] = 'One or more compulsory field is empty';
        elseif ($amount_to_transfer > $user_transfer_bal) :
            $this->response['error'][] = 'Amount to transfer - [' . $symbol . number_format($amount_to_transfer, $dec) . '] is larger than the amount in your mature wallet, input amount not more than the balance in your ( ' . $name . ' mature wallet - [' . $symbol . number_format($user_transfer_bal, $dec) . '] ), the balance in your ( ' . $name . ' immature wallet  - [' . $symbol . number_format($user_non_transfer_bal, $dec) . '] )  cannot be transferred until maturity';
        elseif ($amount_to_transfer < $min_transfer_amt) :
            $this->response['error'][] = 'Requested amount [' . $amount_to_transfer . '] is below minimum transfer amount, input amount not less than ' . $min_transfer_amt;
        elseif (!username_exists($user_transfer_to->user_login)) :
            $this->response['error'][] = 'User with the username [' . $transfer_to_user . '] does not exist, please crosscheck the username';
        else :
            $transfer = $this->completeTransfer($param);
            return $transfer;
        endif;

        $this->error();
    }

    public function completeTransfer($param)
    {
        extract($param);
        //transfer funds to user

        $amount_to_transfer_to_user = apply_filters('rimplenet_amount_to_transfer', $amount_to_transfer, $wallet_id, $transfer_to_user_id);
        $txn_transfer_id1 = $this->add_user_mature_funds_to_wallet($transfer_to_user_id, $amount_to_transfer_to_user, $wallet_id, $note);

        $transfer_info = $txn_transfer_id1;
        $txn_transfer_id2 = '';
        if (is_int($txn_transfer_id1)) {

            $modified_title = 'TRANSFER ~ ' . get_the_title($txn_transfer_id1);
            $args =
                array(
                    'ID'    =>  $txn_transfer_id1,
                    'post_title'   => $modified_title,
                    'post_status'   =>  'publish',
                    'meta_input' => array(
                        'transfer_address_from' => $user_id,
                        'note' => __("TRANSFER from $current_user->user_login $note"),
                    )
                );
            wp_set_object_terms($txn_transfer_id1, 'TRANSFER', 'rimplenettransaction_type', true);
            wp_set_object_terms($txn_transfer_id1, 'INTERNAL TRANSFER', 'rimplenettransaction_type', true);
            wp_update_post($args);

            //debit from user making the transfer

            $amount_to_debit_in_transfer = $amount_to_transfer * -1;
            $txn_transfer_id2 = $this->add_user_mature_funds_to_wallet($user_id, $amount_to_debit_in_transfer, $wallet_id, $note);
        }


        if (is_int($txn_transfer_id2)) {


            $modified_title = 'TRANSFER ~ ' . get_the_title($txn_transfer_id2);
            $args =
                array(
                    'ID'    =>  $txn_transfer_id2,
                    'post_title'   => $modified_title,
                    'post_status'   =>  'publish',
                    'meta_input' => array(
                        'transfer_address_to' => $transfer_to_user_id,
                        'note' => __("TRANSFER to $user_transfer_to->user_login $note"),
                    )
                );
            wp_set_object_terms($txn_transfer_id2, 'TRANSFER', 'rimplenettransaction_type', true);
            wp_set_object_terms($txn_transfer_id2, 'INTERNAL TRANSFER', 'rimplenettransaction_type', true);
            wp_update_post($args);

            $transfer_info = $txn_transfer_id2;
        }

        update_post_meta($txn_transfer_id1, "alt_transfer_id", $txn_transfer_id2);
        update_post_meta($txn_transfer_id2, "alt_transfer_id", $txn_transfer_id1);
        wp_reset_postdata();
        
        $this->response = [
            'status_code' => 200,
            'status' => true,
            'message' => 'Transfer Successful',
            'data' => $transfer_info,
        ];
        return $transfer_info;
    }
}
