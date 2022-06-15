<?php

namespace Trait\Wallet;

trait RimplenetWalletTrait
{
    public static function get_withdrawable_wallet_bal($user_id, $wallet_id)
    {

        $key = 'user_withdrawable_bal_' . strtolower($wallet_id);

        $user_balance = get_user_meta($user_id, $key, true);
        if (empty($user_balance)) {
            $user_balance = 0;
        }

        //$balance = number_format($user_balance,2);
        $balance = $user_balance;

        return $balance;
    }

    public static function get_nonwithdrawable_wallet_bal($user_id, $wallet_id)
    {

        $key = 'user_nonwithdrawable_bal_' . strtolower($wallet_id);

        $user_balance = get_user_meta($user_id, $key, true);
        if (empty($user_balance)) {
            $user_balance = 0;
        }

        //$balance = number_format($user_balance,2);
        $balance = $user_balance;

        return $balance;
    }

    public function getRimplenetWalletFormattedAmount($amount,$wallet_id,$include_data=''){
    
        if(empty($include_data)){$include_data = array();}
        else{ $include_data = explode(",",$include_data);}

        $wallet = $this->getWallet($wallet_id);

        $dec = $wallet['wallet_decimal'];
        $symbol = $wallet['wallet_symbol'];
        $symbol_position = $wallet['wallet_symbol_position'];
        
        if($symbol_position=='right'){
           $disp_info = number_format($amount,$dec)." ".$symbol;;
        }
        else{
           $disp_info = $symbol.number_format($amount,$dec);
        }
          
        if(in_array('wallet_name', $include_data)){
            $disp_info = $wallet['wallet_name']." - ".$disp_info;
        }
          
    return $disp_info;
}
}
