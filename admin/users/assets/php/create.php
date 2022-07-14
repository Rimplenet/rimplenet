<?php

# set response message in a function (sweet alert)
$message = function ($title, $message, $type) {
    $resp = '<script>';
    $resp .= 'swal({
        title: "' . $title . '",
        text: "' . $message . '",
        icon: "' . $type . '",
      })';
    $resp .= '</script>';

    return $resp;
};

if (isset($_POST) && isset($_POST['create_user']) && wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) :
    $data = [];

    # pass all data in an array variable $data
    foreach ($_POST as $key => $value) :
        $data[$key] = sanitize_text_field($value);
    endforeach;

    extract($data); # extract $data aray to access all values as a variable

    $user = new RimplenetCreateUser(); # create an insantiation on user class

    $newUser = $user->create_user(
        $email,
        $uname,
        $password,
        [
            'first_name' => $fname,
            'last_name' => $lname
        ]
    );
    $user = null;

    $error = '';
    # Account for error that may occur durning the create process
    if (isset($newUser['error']) && isset($newUser['error'][0])) :
        $error = $newUser['error'][0];
        foreach ($error as $key) :
            $error = $key[0];
        endforeach;
    endif;

    # Check the status code returned from create_user method
    $code = (int) $newUser['status_code'];

    if ($code == 400) :
        echo $message("Error", ucfirst(str_replace('_', ' ', $error)), 'error');
    else :
        echo $message("Success", $newUser['message'], 'success');
    endif;

endif;
