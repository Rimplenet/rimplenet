<?php
class RimplenetCreateWallets extends Utils
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
            self::$error[$key] = 'Requires a number or decimal';
        }

        extract($this->item);

        $this->checkMinMax('max_amount', $max_amount);
        $this->checkMinMax('min_amount', $min_amount);

        if (!empty(self::$error)) :
            self::$response['error'] = self::$error;
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
            if (isset(self::$error[$type])) :
                self::$error[$type] = array_push(self::$error[$type], $mssg);
            // self::$error[$type] = [...self::$error[$type], $mssg];
            else : self::$error[$type] = $mssg;
            endif;
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
     * Create Wallet
     */
    public function createWallet(array $req = [])
    {
        $this->prop = empty($req) ? $this->req : $req;
        $this->wallet_id = strtolower($this->prop['wallet_id']);

        extract($this->prop);

        if (self::requires([
            'wallet_name'           =>  "$wallet_name || string",
            'wallet_id'             =>  "$wallet_id || string",
            'wallet_symbol'         =>  $wallet_symbol,
            'wallet_symbol_pos'     =>  $wallet_symbol_pos ?? 'left',
            'wallet_type'           =>  "$wallet_type || string",
            'wallet_decimal'        => $wallet_decimal ?? 2 . " || int",
            'max_withdrawal_amount' => $max_withdrawal_amount ?? CreateWallet::MAX_AMOUNT,
            'min_withdrawal_amount' => $min_withdrawal_amount ?? CreateWallet::MIN_AMOUNT,
        ])) return self::$response;
        // if(isset($max_withdrawal_amount) || isset($min_withdrawal_amount))
        if ($this->notFloatOrNumber()) return self::$response;


        # check if wallet already exist
        // return $this->walletExists();
        if ($this->walletExists()) :
            return Res::error(self::$error, "Wallet Already Exists", 409);
        else :
            $wallet = $this->insertWallet();
            return Res::success($wallet, "Wallet was successully created");
        endif;
    }


    /**
     * Insert into DB
     */
    protected function insertWallet()
    {
        extract($this->prop);
        $wlt_id = wp_insert_post([
            'post_title'    => $wallet_name,
            'post_content'  => $wallet_note,
            'post_status'   => 'publish',
            'post_type'     => self::POST_TYPE
        ]);
        wp_set_object_terms($wlt_id, self::WALLETS, self::TAXONOMY);
        $wallet_metas = [
            'wallet_id'                          => $wlt_id,
            'rimplenet_wallet_name'              => $wallet_name,
            'rimplenet_wallet_id'                => $this->wallet_id,
            'rimplenet_wallet_symbol'            => $wallet_symbol,
            'rimplenet_wallet_note'              => $wallet_note ?? '',
            'rimplenet_wallet_type'              => $wallet_type,
            'rimplenet_wallet_decimal'           => $wallet_decimal ?? 2,
            'rimplenet_max_withdrawal_amount'    => $max_withdrawal_amount ?? self::MAX_AMOUNT,
            'rimplenet_min_withdrawal_amount'    => $min_withdrawal_amount ?? self::MIN_AMOUNT,
            'rimplenet_wallet_symbol_position'  => $wallet_symbol_pos ?? 'left',
            // 'include_in_woocommerce_currency_list'          => $inc_i_w_cl,
            // 'enable_as_woocommerce_product_payment_wallet'  => $e_a_w_p,
            // 'rimplenet_rules_after_wallet_withdrawal'       => $r_a_b_w,
            // 'rimplenet_rules_before_wallet_withdrawal'      => $r_b_b_W,
        ];

        foreach ($wallet_metas as $key => $value) {
            if ($key == 'wallet_id') continue;
            update_post_meta($wlt_id, $key, $value);
        }

        return $wallet_metas;
    }
}
