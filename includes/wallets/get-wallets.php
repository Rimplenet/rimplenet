<?php

class RimplenetGetWallets extends Utils
{
    /**
     * Get a single wallet based on wallet id
     * @param string $walletId id of wallet to get
     * @return array>boolean
     */
    public function getWallet(string $walletId)
    {
        $wallet = $this->getWalletById($walletId);

        if (!$wallet) :
            return false;
        else :
            $wallet = get_post($wallet->post_id);
            $walletData = $this->walletFormat($wallet);
           Res::success($walletData, "Wallet Retrieved");
            return $walletData;
        endif;
    }

    /**
     * Get all wallet
     * @return array>boolean
     */
    public function getWallets($page = 1)
    {
        $this->queryDb($page);

        if ($this->query && $this->query->have_posts()):
            $posts = $this->query->get_posts();
            $res = [];
            foreach($posts as $key => $value):
                $posts[$key] = $this->walletFormat($value);
            endforeach;
            Res::success($posts, "Wallet Retrieved");
            return $posts;
        else:
            Res::success(['We are sorry we cannot retrieve any wallet at the moment'], "Wallet Not found", 404);
        endif;
        return false;
    }

    private function walletFormat($wallet)
    {
        $this->id = $wallet->ID;

        $min_withdrawal = $this->postMeta('rimplenet_min_withdrawal_amount');
        $min_withdrawal == '' && $min_withdrawal  = Utils::MIN_AMOUNT;
        
        $max_withdrawal = $this->postMeta('rimplenet_max_withdrawal_amount');
        $max_withdrawal == '' && $max_withdrawal  = Utils::MAX_AMOUNT;

        $inc_wlt_curr_list = $this->postMeta('include_in_woocommerce_currency_list');
        !$inc_wlt_curr_list ? $inc_wlt_curr_list = false :  $inc_wlt_curr_list = true;

        $enb_as_wcclst = $this->postMeta('enable_as_woocommerce_product_payment_wallet');
        !$enb_as_wcclst ? $enb_as_wcclst = false : $enb_as_wcclst = true;

        $res = [
            'post_id'=>$this->id,
            'wallet_id'        => $this->postMeta('rimplenet_wallet_id'),
            'wallet_name'      => $wallet->post_title,
            "wallet_symbol"    => $this->postMeta('rimplenet_wallet_symbol'),
            "wallet_max_wdr_amount"    => $max_withdrawal,
            "wallet_min_wdr_amount"    => $min_withdrawal,
            "wallet_symbol_position"     => $this->postMeta('rimplenet_wallet_symbol_position'),
            "wallet_decimal"           => $this->postMeta('rimplenet_wallet_decimal'),
            'wallet_note'              => $this->postMeta('rimplenet_wallet_note'),
            'wallet_type'              => $this->postMeta('rimplenet_wallet_type')
            // 'in_wc_curr_list'          => $inc_wlt_curr_list,
            // 'enbl_as_wc_prdt_pymt_wlt'             => $enb_as_wcclst,
            // "include_in_withdrawal_form"           => "yes",
            // "rules_after_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_after_wallet_withdrawal'),
            // "rules_before_wallet_withdrawal" =>  $this->postMeta('rimplenet_rules_before_wallet_withdrawal'),
            // "action" => array(
            //     "deposit" => "yes",
            //     "withdraw" => "yes",
            // )
        ];
        return $res;
    }

}
