<?php

// namespace Credits\CreateCredits;

// use Rimplenet_Wallets;
use WalletBalance\RimplenetBalance;
use Traits\Wallet\RimplenetWalletTrait;

class RimplenetGetWalletBalanceMulti extends RimplenetBalance
{
    use RimplenetWalletTrait;
    public function getWalletBalance(array $param = [])
    {

          $required=["user_id"=>"required", "wallet_id"=>"required"];

        
        if ($this->checkIfEmpty($this->req ?? $param, $required)) {
            self::$response = [
                'status_code' => 422,
                'status' => false,
                'message' => "Entities Are Required",
                'error'=>$this->entityerror
            ];

            return false;
        }

        $prop = empty($param) ? $this->req : $param;
        extract($prop);

        $data =array();
        $wallet_id = explode(',',$wallet_id);

        // var_dump($wallet_id);
        // die;

        foreach ($wallet_id as $key => $value) {
            // $this->wallet_id = $value;
            
            // $wallet=$this->getWalletById($value);
            $wallet=$this->getWallet($value);
            // var_dump($wallet);
            // die;
            if ($wallet) {
                $data[$key]["wallet_id"]=$value;
                $data[$key]["wallet_balance_raw"]=$this->get_withdrawable_wallet_bal($user_id, $value) + $this->get_nonwithdrawable_wallet_bal($user_id, $value);
                $data[$key]['wallet_balance_formatted']= $this->getRimplenetWalletFormattedAmount($this->get_withdrawable_wallet_bal($user_id, $value) + $this->get_nonwithdrawable_wallet_bal($user_id, $value), $value);
                $data[$key]["wallet_name"]=$wallet['wallet_name'];
                $data[$key]["wallet_symbol"]=$wallet['wallet_symbol'];
                $data[$key]["wallet_symbol_position"]=$wallet['wallet_symbol_position'];
                $data[$key]["wallet_decimal"]=$wallet['wallet_decimal'];
                $data[$key]["wallet_note"]=$wallet['wallet_note'];
                $data[$key]["wallet_type"]=$wallet['wallet_type'];
                // }
            }else{
                continue;
            }
                // return Res::error($this->error, "Wallet Already Exists", 409);
            // $data[$value]=$this->get_withdrawable_wallet_bal($user_id, $value) + $this->get_nonwithdrawable_wallet_bal($user_id, $value);
        }
        // $data['submitted']='';

        return self::$response = [
                'status_code' => 200,
                'status' => true,
                'message' => 'Wallet Balance retrieved',
                'data' => $data,
                'submitted'=>$this->req ?? $param
            ];

        // $balance = $this->get_withdrawable_wallet_bal($user_id, $wallet_id) + $this->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
       
    }


    public function checkIfEmpty($data, $required)
    {

        // if (empty($data)) {
        //     # code...
        // }



        // $error=[];
        // var_dump($required);
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $required) && empty($value)) {
                $this->entityerror[$key]=$key." is required";
            }
        }

        // var_dump($this->entityerror, empty($this->error));
        // die;

        if (!empty($this->entityerror)) {
            return true;
        }

        return false;
    }
}
