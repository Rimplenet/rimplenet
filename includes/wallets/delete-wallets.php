<?php
use Wallets\Base;

abstract class RimplenetDeleteWallets extends Base
{
    protected function deleteWallet(string $wallet_id){
        $wallet = $this->getWalletById($wallet_id);

        # check if wallet is valid
        if(!$wallet):
            $this->response['status_code'] = 404;
            $this->response['message'] = "Wallet not found";
            $this->response['error'][] = "Operation cannot be completed";
            return false;
        else:
            // trash wallet
            wp_delete_post($wallet->post_id, false );
            $this->response['status_code'] = 200;
            $this->response['status'] = 'success';
            $this->response['message'] = "$wallet_id wallet deleted";
            $this->response['data'][] = "Operation completed";
            return true;
        endif;
        return true;
    }
}