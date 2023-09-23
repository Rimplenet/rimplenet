<?php

abstract class RimplenetDeleteWallets extends Utils
{
    protected function deleteWallet(string $wallet_id){
        $wallet = $this->getWalletById($wallet_id);

        # check if wallet is valid
        if(!$wallet):
            return Res::error(['Operation cannot be completed', 'Wallet not found', 404]);
        else:
            // trash wallet
            wp_delete_post($wallet->post_id, false );
            return Res::success(['Operation completed'], "$wallet_id wallet deleted");
        endif;
        return true;
    }
}