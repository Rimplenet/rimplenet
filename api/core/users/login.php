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
            'rimplenet/v1', '/login',
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

            $this->validation_error[] = 'Invalid credential';
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
            $response['status'] = 'Success';
            $response['response_message'] = 'Login Successful';
            $response['access_token'] = $jwt;
            return new WP_REST_Response( $response );

        }


        $response['status_code'] = 400;
        $response['status'] = 'Bad Request';
        $response['validation_error'] = $this->validation_error;

        return new WP_REST_Response( $response );
    }

    public function validate($request)
    {
        $user['user_email'] = sanitize_text_field( $request->get_param( 'email' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'password' ) );

        if ($user['user_email'] == '') {
            $this->validation_error[] = 'Email is required';
        }

        if (!is_email($user['user_email'])) {
            $this->validation_error[] = 'Invalid email';
        }

        if ($user['user_pass'] == '') {
            $this->validation_error[] = 'Password is required';
        }

        return $user;
    }
    
}

$LoginUser = new LoginUser();