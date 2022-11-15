<?php

    $subject = 'Debit Alert';
    $message = 'Your account has been debited with the sum of '.$data['symbol'].$data["amount"].' on transaction '.$data["transaction_id"].' with description: "'.$data["note"].'".  Please visit Middey for more enquiries and If this is not you, do not hesitate to change your password and contact us immediately';
    $headers = 'just a test';