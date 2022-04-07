<?php

class RegisterUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/register',
            [
                'methods' => 'POST',
                'callback' => [$this, 'register_user']
            ]
        );
    }

    public function register_user(WP_REST_Request $request)
    {

        $user = $this->validate($request);

        if (empty($this->validation_error)) {

            $new_user = wp_insert_user( $user );
            $response['status_code'] = 201;
            $response['status'] = 'Created';
            $response['response_message'] = 'Registration Successful';
            $response['data'] = $new_user;
            return new WP_REST_Response( $response );

        }

        $response['status_code'] = 400;
        $response['status'] = 'Bad Request';
        $response['validation_error'] = $this->validation_error;

        return new WP_REST_Response( $response );
    }

    public function validate($request)
    {
        $user['display_name'] = sanitize_text_field( $request->get_param( 'full_name' ) );
        $user['user_nicename'] = sanitize_text_field( $request->get_param( 'username' ) );
        $user['user_login'] = sanitize_text_field( $request->get_param( 'username' ) );
	    $user['user_email'] = sanitize_text_field( $request->get_param( 'email' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'password' ) );

        if ($user['display_name'] == '') {
            $this->validation_error[] = 'Fullname is required';
        }

        if ($user['user_nicename'] == '') {
            $this->validation_error[] = 'Username is required';
        }

        if ($user['user_email'] == '') {
            $this->validation_error[] = 'Email is required';
        }

        if (!is_email($user['user_email'])) {
            $this->validation_error[] = 'Invalid email';
        }

        if ($user['user_pass'] == '') {
            $this->validation_error[] = 'Password is required';
        }

        if (strlen($user['user_pass']) < 6) {
            $this->validation_error[] = 'Please enter at least 6 characters for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $user['user_pass']) == 0) {
            $this->validation_error[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $user['user_pass']) == 0) {
            $this->validation_error[] = 'Password needs at least one number';
        }

        if (username_exists($user['user_login'])) {
            $this->validation_error[] = 'Username already taken';
        }

        if (email_exists($user['user_email'])) {
            $this->validation_error[] = 'Email already taken';
        }

        return $user;
    }
    
}

$RegisterUser = new RegisterUser();