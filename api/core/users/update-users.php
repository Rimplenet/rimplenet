<?php

class UpdateUser
{
    public $validation_error = [];
    public $user_id;

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/(?P<user_id>\d+)',
            [
                'methods' => 'PUT',
                'callback' => [$this, 'update_user']
            ]
        );
    }

    public function update_user(WP_REST_Request $request)
    {

        $user = $this->validate($request);

        if (empty($this->validation_error)) {

            update_user_meta($this->user_id, 'first_name', $user['user_meta']['first_name']);
            update_user_meta($this->user_id, 'last_name', $user['user_meta']['last_name']);
            
            $update_user = wp_update_user( $user['user'] );
            
            $response['status_code'] = 200;
            $response['status'] = 'true';
            $response['response_message'] = 'Updated Successful';
            $response['data'] = $update_user;
            return new WP_REST_Response( $response );

        }

        $response['status_code'] = 400;
        $response['status'] = 'failed';
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

        $this->user_id = sanitize_text_field( $request->get_param( 'user_id' ) );
        $get_user = get_user_by('ID', $this->user_id);
        $get_user_meta_first_name = get_user_meta($this->user_id, 'first_name')[1];
        $get_user_meta_last_name = get_user_meta($this->user_id, 'last_name')[1];

        $user['ID'] = $this->user_id;

        $user_meta['first_name'] = sanitize_text_field( $request->get_param( 'first_name' ) );
        $user_meta['last_name'] = sanitize_text_field( $request->get_param( 'last_name' ) );

        $user['display_name'] = sanitize_text_field( $request->get_param( 'display_name' ) );
        $user['user_nicename'] = sanitize_text_field( $request->get_param( 'user_nicename' ) );
	    $user['user_email'] = sanitize_text_field( $request->get_param( 'user_email' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'user_pass' ) );

        if ($user_meta['first_name'] == '') {
            $user_meta['first_name'] = $get_user_meta_first_name;
        }
        if (strlen($user_meta['first_name']) < 2) {
            $first_name_error[] = 'first_name must be atleast 2 chars';
        }
        if (!empty($first_name_error)) {
            $this->validation_error[] = ['first_name' => $first_name_error];
        }

        if ($user_meta['last_name'] == '') {
            $user_meta['last_name'] = $get_user_meta_last_name;
        }
        if (strlen($user_meta['last_name']) < 2) {
            $last_name_error[] = 'last_name must be atleast 2 chars';
        }
        if (!empty($last_name_error)) {
            $this->validation_error[] = ['last_name' => $last_name_error];
        }

        if ($user['display_name'] == '') {
            $user['display_name'] = $get_user->display_name;
        }
        if (strlen($user['display_name']) < 4) {
            $display_name_error[] = 'display_name must be atleast 4 chars';
        }
        if (!empty($display_name_error)) {
            $this->validation_error[] = ['display_name' => $display_name_error];
        }

        if ($user['user_nicename'] == '') {
            $user['user_nicename'] = $get_user->user_nicename;
        }
        if (strlen($user['user_nicename']) < 4) {
            $user_nicename_error[] = 'user_nicename must be atleast 4 chars';
        }
        if (!empty($user_nicename_error)) {
            $this->validation_error[] = ['user_nicename' => $user_nicename_error];
        }

        if ($user['user_email'] == '') {
            $user['user_email'] = $get_user->user_email;
        }
        if (!is_email($user['user_email'])) {
            $user_email_error[] = 'Invalid email';
        }
        if (!empty($user_email_error)) {
            $this->validation_error[] = ['user_email' => $user_email_error];
        }

        if ($user['user_pass'] == '') {
            $user['user_pass'] = $get_user->user_pass;
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

$UpdateUser = new UpdateUser();