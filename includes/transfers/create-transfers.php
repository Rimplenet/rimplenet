<?php

use Traits\Wallet\RimplenetWalletTrait;

/**
 * Create Transfers
 */
class RimplenetCreateTransfer extends RimplenetGetWallets
{
    const TERMS = 'INTERNAL TRANSFER';
    use RimplenetWalletTrait;
    
    public function transfer($req = [])
    {
        $prop = empty($req) ? $this->req : $req;
        $this->req = $prop;
        extract($prop);
        if (self::requires([
            'transfer_from_user' => "$transfer_from_user || int",
            'amount_to_transfer' => "$amount_to_transfer || amount",
            'transfer_to_user' => "$transfer_to_user || int",
            'wallet_id' => "$wallet_id || alnum",
        ])) return self::$response;

        # Get current loggedin user (user from)
        $current_user = get_user_by('ID', $transfer_from_user);
        $current_user_id  = $current_user->ID;
        if(!$current_user) return Res::error(['Invalid User '.$transfer_from_user], 'Unable to reach '.$transfer_from_user);
        
        # Get Other user (user to)
        $user_transfer_to = get_user_by('ID', $transfer_to_user);
        if(!$user_transfer_to) return Res::error(['Invalid User '.$transfer_to_user], 'Unable to reach '.$transfer_to_user);
        $transfer_to_user_id  = $user_transfer_to->ID;
        
        # Get user wallet
        $wallet = $this->getWallet($wallet_id);
        if(!$wallet) return Res::error(['Invalid Wallet Provided'], 'Invalid Wallet', 404);
        
        $min_transfer_amt = 0;
        # Get user balance
        $user_transfer_bal = self::get_withdrawable_wallet_bal($current_user_id, $wallet_id);
        $user_non_transfer_bal = self::get_nonwithdrawable_wallet_bal($current_user_id, $wallet_id);
        
        // return Res::error($wallet);
        
        $dec = $wallet['wallet_decimal'];
        $symbol = $wallet['wallet_symbol'];
        $name = $wallet['wallet_name'];
        $balance = $symbol . number_format($user_transfer_bal, $dec);

        $exec = $this->executeTransfer([
            'transfer_from_user_id' => $current_user_id,
            'wallet_id' => $wallet_id,
            'amount_to_transfer' => $amount_to_transfer,
            'transfer_to_user' => $transfer_to_user,
            'user_transfer_bal' => $user_transfer_bal,
            'transfer_to_user_id' => $transfer_to_user_id,
            'user_non_transfer_bal' => $user_non_transfer_bal,
            'current_user_id' => $current_user_id,
            'current_user' => $current_user,
            'min_transfer_amt' => $min_transfer_amt,
            "user_transfer_to" => $user_transfer_to,
            'symbol' => $symbol,
            'dec' => $dec, 
            'name' => $name,
            'balance' => $balance,
            'note' => $note
        ]);
    }


    public function executeTransfer($param = [])
    {
        extract($param);
        if (empty($current_user_id) || empty($amount_to_transfer) || empty($wallet_id) || empty($transfer_to_user)) :
           return Res::error('One or more compulsory field is empty');
        elseif ($amount_to_transfer > $user_transfer_bal) :
           return Res::error('Amount to transfer - [' . $symbol . number_format($amount_to_transfer, $dec) . '] is larger than the amount in your mature wallet, input amount not more than the balance in your ( ' . $name . ' mature wallet - [' . $symbol . number_format($user_transfer_bal, $dec) . '] ), the balance in your ( ' . $name . ' immature wallet  - [' . $symbol . number_format($user_non_transfer_bal, $dec) . '] )  cannot be transferred until maturity', "Insufficient Balance");
        elseif ($amount_to_transfer < $min_transfer_amt) :
           return Res::error('Requested amount [' . $amount_to_transfer . '] is below minimum transfer amount, input amount not less than ' . $min_transfer_amt, "Request amount is below minimum transfer amount");
        elseif (!username_exists($user_transfer_to->user_login)) :
           return Res::error('User with the username [' . $transfer_to_user . '] does not exist, please crosscheck the username', "Username does not exist", 404);
        else :
            $transfer = $this->completeTransfer($param);
            return $transfer;
        endif;

        Res::error();
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
                        'transfer_address_from' => $current_user_id,
                        'transfer_address_to' => $transfer_to_user_id,
                        'transfer_wallet_symbol' => $symbol,
                        'note' => __("TRANSFER from $current_user->user_login $note"),
                    )
                );
            // wp_set_object_terms($txn_transfer_id1, 'TRANSFER', 'rimplenettransaction_type', true);
            wp_set_object_terms($txn_transfer_id1,  self::TRANSFERS, self::TAXONOMY, true);
            wp_update_post($args);

            //debit from user making the transfer

            $amount_to_debit_in_transfer = $amount_to_transfer * -1;
            $txn_transfer_id2 = $this->add_user_mature_funds_to_wallet($current_user_id, $amount_to_debit_in_transfer, $wallet_id, $note);
        }
        
        
        if (is_int($txn_transfer_id2)) {
            
            
            $modified_title = 'TRANSFER ~ ' . get_the_title($txn_transfer_id2);
            $args =
            array(
                'ID'    =>  $txn_transfer_id2,
                'post_title'   => $modified_title,
                'post_status'   =>  'publish',
                'meta_input' => array(
                    'transfer_address_from' => $current_user_id,
                    'transfer_address_to' => $transfer_to_user_id,
                    'transfer_wallet_symbol' => $symbol,
                    'note' => __("TRANSFER to $user_transfer_to->user_login $note"),
                    )
                );
                
            wp_set_object_terms($txn_transfer_id2,  self::TRANSFERS, self::TAXONOMY, true);
            wp_update_post($args);

            $transfer_info = $txn_transfer_id2;
        }

        update_post_meta($txn_transfer_id1, "alt_transfer_id", $txn_transfer_id2);
        update_post_meta($txn_transfer_id2, "alt_transfer_id", $txn_transfer_id1);
        wp_reset_postdata();

       Res::success(['transfer' => $transfer_info], 'Transfer Action Completed');
        return $transfer_info;
    }
    public function getTransferById(int $transferID)
    {
        global $wpdb;
        $transferID = sanitize_text_field($transferID);
        $transfer = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='alt_transfer_id' AND meta_value='$transferID'");

        if ($transfer) :
            return $transfer;
        else :
            Res::error('Invalid Transfer Id', 'Transfer not found', 404);
            return false;
            exit;
        endif;
    }
}
