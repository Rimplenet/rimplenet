<?php

# set response message in a function (sweet alert)
$message = function ($title, $message, $type) {
    $resp = '<script>';
    $resp .= 'swal({
        title: "' . $title . '",
        text: "' . $message . '",
        icon: "' . $type . '",
      })';
      $resp.='
      setTimeout(() => {location.reload()}, 1500)
      ';
    $resp .= '</script>';

    return $resp;
};

if (isset($_POST) && isset($_POST['create_transfer']) && wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) :
    $data = [];

    # pass all data in an array variable $data
    foreach ($_POST as $key => $value) :
        $data[$key] = sanitize_text_field($value);
    endforeach;

    extract($data); # extract $data aray to access all values as a variable

    if(preg_match('/\d+/', $transfer_wallet)){
        echo $message("Error", "Wallet cannot contain numbers", 'error');
        exit;
    }

    $tInit = new RimplenetCreateTransfer; # create an insantiation on Transfer class
    $transfer = $tInit->transfer([
        'user_id' => get_current_user_id(),
        'amount_to_transfer' => (float) $transfer_amount,
        'transfer_to_user' => (string) $transfer_user,
        'wallet_id' => (string) $transfer_wallet
    ]);

    $error = '';
    # Account for error that may occur durning the create process
    if (isset($tInit::$response['message'])) :
        $error = $tInit::$response['message'];
    endif;

    # Check the status code returned from create_user method
    $code = (int) $tInit::$response['status_code'];

    if ($code >= 400) :
        echo $message("Error", ucfirst(str_replace('_', ' ', $error)), 'error');
    else :
        echo $message("Success", $tInit::$response['message'], 'success');
    endif;
    exit;

endif;
