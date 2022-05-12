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
            'rimplenet/v1', '/users',
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

            $new_user = wp_insert_user( $user['user'] );
            add_user_meta($new_user, 'first_name', $user['user_meta']['first_name']);
            add_user_meta($new_user, 'last_name', $user['user_meta']['last_name']);
            $response['status_code'] = 201;
            $response['status'] = 'Created';
            $response['response_message'] = 'Registration Successful';
            $response['data'] = $new_user;
            return new WP_REST_Response( $response );

        }

        $response['status_code'] = 400;
        $response['status'] = 'Bad Request';
        $response['error'] = $this->validation_error;

        return new WP_REST_Response( $response );
    }

    public function validate($request)
    {
        $first_name_error = [];
        $last_name_error = [];
        $display_name_error = [];
        $user_nicename_error = [];
        $user_login_error = [];
        $user_email_error = [];
        $user_pass = [];

        $user_meta['first_name'] = sanitize_text_field( $request->get_param( 'first_name' ) );
        $user_meta['last_name'] = sanitize_text_field( $request->get_param( 'last_name' ) );

        $user['display_name'] = sanitize_text_field( $request->get_param( 'display_name' ) );
        $user['user_nicename'] = sanitize_text_field( $request->get_param( 'user_nicename' ) );
        $user['user_login'] = sanitize_text_field( $request->get_param( 'user_login' ) );
	    $user['user_email'] = sanitize_text_field( $request->get_param( 'user_email' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'user_pass' ) );

        if ($user_meta['first_name'] == '') {
            $first_name_error[] = 'first_name is required';
        }
        if (strlen($user_meta['first_name']) < 2) {
            $first_name_error[] = 'first_name must be atleast 2 chars';
        }
        if (!empty($first_name_error)) {
            $this->validation_error[] = ['first_name' => $first_name_error];
        }

        if ($user_meta['last_name'] == '') {
            $last_name_error[] = 'last_name is required';
        }
        if (strlen($user_meta['last_name']) < 2) {
            $last_name_error[] = 'last_name must be atleast 2 chars';
        }
        if (!empty($last_name_error)) {
            $this->validation_error[] = ['last_name' => $last_name_error];
        }

        if ($user['display_name'] == '') {
            $display_name_error[] = 'display_name is required';
        }
        if (strlen($user['display_name']) < 4) {
            $display_name_error[] = 'display_name must be atleast 4 chars';
        }
        if (!empty($display_name_error)) {
            $this->validation_error[] = ['display_name' => $display_name_error];
        }

        if ($user['user_nicename'] == '') {
            $user_nicename_error[] = 'user_nicename is required';
        }
        if (strlen($user['user_nicename']) < 4) {
            $user_nicename_error[] = 'user_nicename must be atleast 4 chars';
        }
        if (!empty($user_nicename_error)) {
            $this->validation_error[] = ['user_nicename' => $user_nicename_error];
        }
        
        if ($user['user_login'] == '') {
            $user_login_error[] = 'user_login is required';
        }
        if (strlen($user['user_login']) < 4) {
            $user_login_error[] = 'user_login must be atleast 4 chars';
        }
        if (username_exists($user['user_login'])) {
            $user_login_error[] = 'user_login already taken';
        }
        if (!empty($user_login_error)) {
            $this->validation_error[] = ['user_login' => $user_login_error];
        }

        if ($user['user_email'] == '') {
            $user_email_error[] = 'user_email is required';
        }
        if (!is_email($user['user_email'])) {
            $user_email_error[] = 'Invalid user_email';
        }
        if (email_exists($user['user_email'])) {
            $user_email_error[] = 'user_email already taken';
        }
        if (!empty($user_email_error)) {
            $this->validation_error[] = ['user_email' => $user_email_error];
        }

        if ($user['user_pass'] == '') {
            $user_pass_error[] = 'user_pass is required';
        }
        if (strlen($user['user_pass']) < 6) {
            $user_pass_error[] = 'Please enter at least 6 characters for the user_pass';
        }
        if (preg_match('/.*[a-z]+.*/i', $user['user_pass']) == 0) {
            $user_pass_error[] = 'user_pass needs at least one letter';
        }
        if (preg_match('/.*\d+.*/i', $user['user_pass']) == 0) {
            $user_pass_error[] = 'user_pass needs at least one number';
        }
        if (!empty($user_pass_error)) {
            $this->validation_error[] = ['user_pass' => $user_pass_error];
        }


        return ['user' => $user, 'user_meta' => $user_meta];
    }
    
}

$RegisterUser = new RegisterUser();