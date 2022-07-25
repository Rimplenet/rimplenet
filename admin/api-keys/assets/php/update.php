<?php
if (isset($_GET['user'])) :
    $init = new RimplenetGetUser();
    $id = sanitize_text_field($_GET['user']);
    $user = $init->get_users(null, $id);
    $user = $user['data'];
endif;

if (isset($_POST) && isset($_POST['update_user']) && wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) :   
    $data = [];

    # pass all data in an array variable $data
    foreach ($_POST as $key => $value) :
        $data[$key] = sanitize_text_field($value);
    endforeach;

    extract($data); # extract $data aray to access all values as a variable

    $userVect = new RimplenetUpdateUser();
    $update = $userVect->update_user($user_id, $email, [], [
        'first_name' => $fname,
        'last_name' => $lname
    ], null); 

    $error = '';
    # Account for error that may occur durning the create process
    if (isset($update['error']) && isset($update['error'][0])) :
        $error = $update['error'][0];
        foreach ($error as $key) :
            $error = $key[0];
        endforeach;
    endif;

    # Check the status code returned from create_user method
    $code = (int) $update['status_code'];

    if ($code == 400) :
        echo $message("Error", ucfirst(str_replace('_', ' ', $error)), 'error');
    else :
        echo $message("Success", $update['response_message'], 'success');
        echo "<script>location.reload()</script>";
    endif;

endif;
