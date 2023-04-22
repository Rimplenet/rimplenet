<?php

    $subject = 'Debit Alert - '.$data['symbol'].$data["amount_formatted"];
    $message = "
                <div style='text-align:center; padding: 7%; align-self:center;'>
                    <img src='https://app.middey.com/img/middey-logowhitebg.7893c61c.png' width='200' height='100' style='max-width: 400px; text-align: center'> <br>
                    Your account has been debited with the sum 
                    of ".$data['symbol'].$data['amount_formatted']."
                    on transaction #".$data['transaction_id']." with 
                    description: ".$data['note'].". 
                    Your new balance is ".$data['symbol'].number_format($data['balance_after'], $data['decimal'])."
                    Please visit Middey for more enquiries and If this is not you,
                    do not hesitate to change your password and contact us immediately
                </div>";

                
    // $headers = 'just a test';
    $headers = array('Content-Type: text/html; charset=UTF-8');