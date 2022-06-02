<?php
require 'jwt.php';

class RimplenetLoginUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-login-user', array($this, 'login_user_test'));
    }

    public function login_user_test() {
        ob_start();
        var_dump($this->login_user("taiwo@gmail.com", "abc123"));
        return ob_get_clean();
    }

    public function login_user($user_email, $user_pass)
    {

        $validation = $this->validate($user_email, $user_pass);

        $is_user = wp_authenticate($user_email, $user_pass);

        
        if (empty($this->validation_error) && is_wp_error($is_user)) {
            
            $this->validation_error[] = 'Invalid Credential';
        }
        
        if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);
        
        if (empty($this->validation_error)) {

            unset($is_user->data->user_pass);

            $new = get_userdata($is_user->data->ID);
            $check_role = get_userdata($is_user->data->ID);

            $is_user->data->role = $check_role->wp_capabilities;

            $iss = 'localhost';
            $iat = time();
            $exp = $iat + 3600;
            $user_data = $is_user->data;

            $secret_key = "user123";

            $payload = json_encode([
                'iss' => $iss,
                'iat' => $iat,
                'exp' => $exp,
                'data' => $user_data
            ]);

            $jwt = JWT::encode($payload);
            
            return $this->response(200, true, "Login successful", ["access_token"=>$jwt], $this->validation_error);

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
            $user_pass_error[] = 'user_pass is required';
        }
        if (!empty($user_pass_error)) {
            $this->validation_error[] = ['user_pass' => $user_pass_error];
        }

    }

    public function response($status_code, $status, $response_message, $data=[], $error=[])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "response_message" => $response_message,
            "data" => $data,
            "error" =>$error
        ];
    }
    
}

$RimplenetLoginUser = new RimplenetLoginUser();