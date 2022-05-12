<?php

class LoginUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/login',
            [
                'methods' => 'POST',
                'callback' => [$this, 'login_user']
            ]
        );
    }

    public function login_user(WP_REST_Request $request)
    {

        $user = $this->validate($request);

        $is_user = wp_authenticate($user['user_email'], $user['user_pass']);
        
        if (is_wp_error($is_user)) {

            $this->validation_error[] = 'invalid_credential';
        }

        if (empty($this->validation_error)) {

            unset($is_user->data->user_pass);

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
            
            $response['status_code'] = 200;
            $response['status'] = 'true';
            $response['response_message'] = 'Login Successful';
            $response['data'] = ['access_token' => $jwt];
            return new WP_REST_Response( $response );

        }


        $response['status_code'] = 400;
        $response['status'] = 'failed';
        $response['response_message'] = 'Invalid Credential';
        $response['error'] = $this->validation_error;

        return new WP_REST_Response( $response );
    }

    public function validate($request)
    {
        $user_email_error = [];
        $user_pass_error = [];

        $user['user_email'] = sanitize_text_field( $request->get_param( 'user_email' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'user_pass' ) );

        if ($user['user_email'] == '') {
            $user_email_error[] = 'user_email is required';
        }
        if (!is_email($user['user_email'])) {
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

        return $user;
    }
    
}

$LoginUser = new LoginUser();