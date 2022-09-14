<?php
// require 'jwt.php';

class RimplenetLoginUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-login-user', array($this, 'login_user_test'));
    }

    public function login_user_test()
    {
        ob_start();
        var_dump($this->login_user("taiwo@gmail.com", "abc123"));
        return ob_get_clean();
    }

    public function login_user($user_email, $user_password, $token_expiration = null)
    {

        $validation = $this->validate($user_email, $user_password);

        $is_user = wp_authenticate($user_email, $user_password);

        if (empty($this->validation_error) && is_wp_error($is_user)) {

            $this->validation_error[] = 'Invalid Credential';
        }

        if ($token_expiration == null) {

            if (!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);

            if (empty($this->validation_error)) {

                $request = [
                    "user_email" => $user_email,
                    "user_password" => $user_password,
                    "token_expiration" => $token_expiration
                ];

                do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_login_user', $auth = null, $request);

                unset($is_user->data->user_pass);

                $iss = 'localhost';
                $iat = time();
                $exp = $iat + 3600;
                $user_data = $this->userFormat($is_user);

                # unset user data ... email /fname--lname
                $newData = [];
                $newData["id"] = $user_data["ID"];
                foreach ($user_data as $data => $val) :
                    if (
                        $data == 'ID'
                        || $data == 'user_email'
                        || $data == 'first_name'
                        || $data == 'last_name'
                    ) {
                        unset($user_data[$data]);
                        continue;
                    }
                    $newData[$data] = $val;
                endforeach;


                $payload = json_encode([
                    'iss' => $iss,
                    'iat' => $iat,
                    'exp' => $exp,
                    'user' => $newData
                ]);

                $jwt = JWT::encode($payload);

                $data = [
                    "access_token"  => $jwt,
                    "user_id"       => $is_user->data->ID,
                    "user_email"    => $is_user->data->user_email,
                    "username"      => $is_user->data->user_login,
                    "time_of_login" => time()
                ];

                return $this->response(200, true, "Login successful", $data, []);
            }
        } else {

            if ($token_expiration == 'persistent') {

                if (!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);

                if (empty($this->validation_error)) {

                    unset($is_user->data->user_pass);

                    $iss = 'localhost';
                    $iat = time();
                    $exp = $iat + 15780000;
                    $user_data = $this->userFormat($is_user);

                    $secret_key = "user123";

                    # unset user data ... email /fname--lname
                    $newData = [];
                    $newData["id"] = $user_data["ID"];
                    foreach ($user_data as $data => $val) :
                        if (
                            $data == 'ID'
                            || $data == 'user_email'
                            || $data == 'first_name'
                            || $data == 'last_name'
                        ) {
                            unset($user_data[$data]);
                            continue;
                        }
                        $newData[$data] = $val;
                    endforeach;

                    $payload = json_encode([
                        'iss' => $iss,
                        'iat' => $iat,
                        'exp' => $exp,
                        'user' => $newData
                    ]);

                    $jwt = JWT::encode($payload);

                    $data = [
                        "access_token"  => $jwt,
                        "user_id"       => $is_user->data->ID,
                        "user_email"    => $is_user->data->user_email,
                        "username"      => $is_user->data->user_login,
                        "time_of_login" => time()
                    ];

                    return $this->response(200, true, "Login successful", $data, []);
                }
            } else {
                return $this->response(400, "failed", "Validation error", [], ['error' => 'unkown token_expiration']);
            }
        }
    }

    public function validate($user_email, $user_pass)
    {
        $user_email_error = [];
        $user_pass_error = [];

        $user['user_email'] = sanitize_text_field($user_email);
        $user['user_pass'] = $user_pass;

        if ($user['user_email'] == '') {
            $user_email_error[] = 'user_email is required';
        }
        if ($user['user_email'] && !is_email($user['user_email'])) {
            $user_email_error[] = 'Invalid user_email';
        }
        if (!empty($user_email_error)) {
            $this->validation_error[] = ['user_email' => $user_email_error];
        }

        if ($user['user_pass'] == '') {
            $user_pass_error[] = 'user_password is required';
        }
        if (!empty($user_pass_error)) {
            $this->validation_error[] = ['user_pass' => $user_pass_error];
        }
    }

    public function response($status_code, $status, $message, $data = [], $error = [])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "error" => $error
        ];
    }

    private function userFormat($user)
    {

        if (!isset($user->data)) return;

        return [
            "ID" => intval($user->data->ID),
            "username" => $user->data->user_login,
            "user_email" => $user->data->user_email,
            "first_name" => get_user_meta($user->data->ID, "first_name", true),
            "last_name" => get_user_meta($user->data->ID, "last_name", true),
            "roles" => $user->roles,
        ];
    }
}

$RimplenetLoginUser = new RimplenetLoginUser();
