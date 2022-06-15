<?php

use Traits\Wallet\RimplenetWalletTrait;
use Transfers\Transfers;

/**
 * Create Transfers
 */
class RimplenetCreateTransfer extends Transfers
{
    use RimplenetWalletTrait;

    // $user_id, $amount_to_transfer, $wallet_id, $transfer_to_user, $note=''
    public function transfer($user_id, $amount_to_transfer, $wallet_id, $transfer_to_user, $note = '')
    {
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
    }


    public function executeTransfer($user_id, $wallet_id, $amount_to_transfer)
    {
        if (empty($user_id) || empty($amount_to_transfer) || empty($wallet_id)  or empty($transfer_to_user)) :
            $this->response['error'] = 'One or more compulsory field is empty';
        elseif ($amount_to_transfer > $user_transfer_bal) :
            $this->response['error'] = 'Amount to transfer - <strong>[' . $symbol . number_format($amount_to_transfer, $dec) . ']</strong> is larger than the amount in your mature wallet, input amount not more than the balance in your <strong>( ' . $name . ' mature wallet - [' . $symbol . number_format($user_transfer_bal, $dec) . '] ),</strong> the balance in your <strong>( ' . $name . ' immature wallet  - [' . $symbol . number_format($user_non_transfer_bal, $dec) . '] )</strong>  cannot be transferred until maturity';
        elseif ($amount_to_transfer < $min_transfer_amt) :
            $this->response['error'] = 'Requested amount [' . $amount_to_transfer . '] is below minimum transfer amount, input amount not less than ' . $min_transfer_amt;
        elseif (!username_exists($user_transfer_to->user_login)) :
            $this->response = 'User with the username <b>[' . $transfer_to_user . ']</b> does not exist, please crosscheck the username';
        else:
            $this->response['data'] = "Weldone All is working";
            return $this->response;
        endif;

        return false;
    }
}
