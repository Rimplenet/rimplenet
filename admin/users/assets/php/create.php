<?php

use Wallets\CreateWallets\RimplenetCreateWallets;
use Wallets\GetWallets\RimplenetGetWallets;

if(isset($_POST['first_name'])):
    $data = [];

    foreach ($_POST as $key => $value) {
        $data[$key] = sanitize_text_field($value);
    }

    $users = new RimplenetCreateUser();
    $users->create_user(
        1, 
        $data['email'],
        $data[''],
        $data[],
        $data[],
        [
            'first_name' => '',
            'last_name' => ''
        ]
    );
    
endif;