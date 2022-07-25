<?php


function returnWalletHtml()
{

    $wallets = new RimplenetGetWallets();
    $wallets->getWallets();
    $wallets = $wallets::$response;

    $options = '<option value="">Wallets</option>';

    if (isset($wallets['data'])) :
        $wallets = $wallets['data'];
        foreach ($wallets as $wallet) :
            $wallet = (object) $wallet;

            $data = '<option value="' . $wallet->wallet_id . '" data-symbol="' . $wallet->wallet_symbol . '">';
            $data .= "$wallet->wallet_name ($wallet->wallet_id)";
            $data .= '</option>';

            $options .= $data;
        endforeach;
        return $options;
    endif;
};

if(isset($_GET['get_user'])){
    $posts = sanitize_text_field();
    $init = new RimplenetGetUser;
}
