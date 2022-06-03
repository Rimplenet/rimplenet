<?php

if(isset($_POST['fname'])):
    $data = [];

    foreach ($_POST as $key => $value) {
        $data[$key] = sanitize_text_field($value);
    }
    extract($data);

    $user = new RimplenetCreateUser();

    $newUser = $user->create_user(
        1, $email, $uname, $password, [
            'first_name' => $fname,
            'last_name' => $lname
        ]
    );

endif;