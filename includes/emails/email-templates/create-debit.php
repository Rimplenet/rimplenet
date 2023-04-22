<?php

    $subject = 'Debit Alert - '.$data['symbol'].$data["amount_formatted"];
    $message = 'Your account has been debited with the sum 
                of '.$data['symbol'].$data["amount_formatted"].'
                on transaction '.$data["transaction_id"].' with 
                description: "'.$data["note"].'". 
                Your new balance is '.$data['symbol'].$data['balance_after'].'
                Please visit Middey for more enquiries and If this is not you,
                do not hesitate to change your password and contact us immediately';

                
    // $headers = 'just a test';
    $headers = array('Content-Type: text/html; charset=UTF-8');