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
        $balance = $symbol.number_format($user_transfer_bal, $dec);
    }


    public function executeTransfer(Type $var = null)
    {
        # code...
    }
}
