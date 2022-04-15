<?php

class ChangePassword
{
    public $validation_error = [];
    public $get_user;

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/change-password',
            [
                'methods' => 'POST',
                'callback' => [$this, 'change_password']
            ]
        );
    }

    public function change_password(WP_REST_Request $request)
    {

        $user = $this->validate($request);

        if(!$this->get_user) {
            $response['status_code'] = 404;
            $response['status'] = 'failed';
            $response['response_message'] = 'User not found';
            return new WP_REST_Response( $response );
        }

        
        if (empty($this->validation_error)) {
            
            wp_set_password($user['user_pass'], $user['user_id']);
            
            $response['status_code'] = 200;
            $response['status'] = 'true';
            $response['response_message'] = 'Password changed successfuly';
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
        $user_pass_error = [];
        $user_id_error = [];

	    $user['user_id'] = sanitize_text_field( $request->get_param( 'user_id' ) );
	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'user_pass' ) );
	    $user['old_pass'] = sanitize_text_field( $request->get_param( 'old_pass' ) );

        $this->get_user = get_user_by('ID', $user['user_id']);

        if ($user['user_id'] == '') {
            $user_id_error[] = 'user_id is required';
        }
        if (!empty($user_id_error)) {
            $this->validation_error[] = ['user_id' => $user_id_error];
        }

        if ($user['old_pass'] == '') {
            $user_pass_error[] = 'old_pass is required';
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
        if (wp_check_password($user['old_pass'], $this->get_user->user_pass, $user['user_id']) == false) {
            $user_pass_error[] = 'user_pass do not match old_pass ';
        }
        if (!empty($user_pass_error)) {
            $this->validation_error[] = ['user_pass' => $user_pass_error];
        }

        return $user;
    }
    
}

$ChangePassword = new ChangePassword();