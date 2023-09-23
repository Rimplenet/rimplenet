<?php

namespace Wallets\UpdateWallets;
use Wallets\Base;

abstract class BaseWallet extends Base
{
    /**
     * validate fields for decimals and numbers
     * @return bool
     */
    protected function notFloatOrNumber(array $req = [])
    {
        $prop = empty($req) ? $this->req : $req;

        $this->item = [
            'max_amount' => $prop['max_withdrawal_amount'],
            'min_amount' => $prop['min_withdrawal_amount'],
            'wallet_decimal' => $prop['wallet_decimal']
        ];

        foreach ($this->item as $key => $value) {
            if (is_float($value) || is_int($value)) continue;
            $this->error[$key] = 'Requires a number or decimal';
        }

        extract($this->item);

        $this->checkMinMax('max_amount', $max_amount);
        $this->checkMinMax('min_amount', $min_amount);

        if (!empty($this->error)) :
            $this->response['error'] = $this->error;
            return true;
        endif;

        return;
    }

    /**
     * Validate Min and max
     * store an error if the minimum int is greater than max int
     * store an error if the maximum int is lesser than the min int
     * validate the maximum amount to be stored
     * validate min amount to be stored
     * @param string $type the type to check e.g (min_amount, max_amount)
     * @param int $amount the value of the amount
     */
    protected function checkMinMax(string $type, int $amount)
    {
        $max_amount = self::MAX_AMOUNT; # minimun wallet withdrawal
        $min_amount = self::MIN_AMOUNT; #maximum wallet withdrawal

        $max_mssg = 'Withdrawal amount cannot be greater than ' . $max_amount; # Maximum withdrawal message
        $min_mssg = 'Withdrawal amount cannot be less than ' . $min_amount; # Minnimum withdrawal message
        $gtMssg = 'Equality Error'; # Minnimum withdrawal message

        $repeat = function ($type, $mssg) {
            if (isset($this->error[$type]))
                $this->error[$type] = array_push($this->error[$type], $mssg); # [...$this->error[$type], $mssg];
            else $this->error[$type] = $mssg;
        };

        # Check max
        if ($amount > $max_amount) {
            $repeat($type, $max_mssg);
        }

        # Check min
        if ($amount < $min_amount) {
            $repeat($type, $min_mssg);
        }

        extract($this->item);

        if ($min_amount > $max_amount || $max_amount <= $min_amount) {
            $repeat($type, $gtMssg);
        }

        return;
    }

    /**
     * Update Wallet
     */
    protected function updateWallet(array $req = [])
    {
        $prop = empty($req) ? $this->req : $req;
        $wallet_id = strtolower($this->prop['wallet_id']);
        $wallet = $this->getWalletById($wallet_id);

        # check if wallet already exist
        // return $this->walletExists();
        if ($wallet) :
            foreach ($prop as $key => $value) {
                if ($key == 'wallet_id') continue;
                update_post_meta($wallet->post_id, $key, $value);
            }
            return $this->success($wallet, "Wallet was successfully Updated");
        else :
            $this->error('Wallet not found', "Invalid Wallet");
            return false;
        endif;
    }
}
