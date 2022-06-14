<?php
if (isset($_GET['user'])) :
    $init = new RimplenetGetUser();
    $id = sanitize_text_field($_GET['user']);
    $user = $init->get_users(null, $id);
    $user = $user['data']->data;
endif;

if (isset($_POST) && isset($_POST['update_user'])) :   
    $data = [];

    # pass all data in an array variable $data
    foreach ($_POST as $key => $value) :
        $data[$key] = sanitize_text_field($value);
    endforeach;

    extract($data); # extract $data aray to access all values as a variable

    $userVect = new RimplenetUpdateUser();
    $update = $userVect->update_user(1, $user_id, $email, null, [
        'first_name' => $fname,
        'last_name' => $lname
    ]); 

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
        echo $message("Success", $newUser['response_message'], 'success');
    endif;

endif;
