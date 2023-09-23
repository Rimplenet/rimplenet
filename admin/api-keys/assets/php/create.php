<?php

# set response message in a function (sweet alert)
$message = function ($title, $message, $type) {
    $resp = '<script>';
    $resp .= 'swal({
        title: "' . $title . '",
        text: "' . $message . '",
        icon: "' . $type . '",
      })';
    //   $resp.='
    //   setTimeout(() => {location.reload()}, 1500)
    //   ';
    $resp .= '</script>';

    return $resp;
};

if (isset($_POST) && isset($_POST['create_key']) && wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) :
    $data = [];

    # pass all data in an array variable $data
    foreach ($_POST as $key => $value) :
        $key = str_replace([' ', '-'], '_', $key);
        if(is_array($value)){
            $data[$key] = $value;
            continue;
        }
        $data[$key] = sanitize_text_field($value);
    endforeach;

    // echo json_encode($data);

    extract($data); # extract $data aray to access all values as a variable


    // if(preg_match('/\d+/', $transfer_wallet)){
    //     echo $message("Error", "Wallet cannot contain numbers", 'error');
    //     exit;
    // }

    $tInit = new RimplenetApiKeys; # create an insantiation on Transfer class
    $apiKey = $tInit->genkey([
        'name' => $app_name ?? '',
        'key_type' => $key_type ?? '',
        'app_id' => $app_id ?? '',
        'allowed_actions' => implode(',', $allowed_actions ?? []),
        'allowed_ip_domain' => trim(strip_tags($allowed_ip_domain))
    ], true);

    $error = '';
    # Account for error that may occur durning the create process
    if (isset(Utils::$response['message'])) :
        $error = Utils::$response['message'];
    endif;

    // # Check the status code returned from create_user method
    $code = (int) Utils::$response['status_code'];

    if ($code >= 400) :
        echo $message("Error", ucfirst(str_replace('_', ' ', $error)), 'error');
    else :
        echo $message("Success", Utils::$response['message'], 'success');
    endif;
    // exit;

endif;
