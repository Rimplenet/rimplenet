<?php

class ResetPassword
{
    public $validation_error = [];

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users/reset-password',
            [
                'methods' => 'POST',
                'callback' => [$this, 'reset_password']
            ]
        );
    }

    public function reset_password(WP_REST_Request $request)
    {

        $user = $this->validate($request);

        //get user_id by password_reset_token
        $user_id = 28;

        
        if (empty($this->validation_error)) {
            
            wp_set_password($user['user_pass'], $user_id);
            unset($is_user->data->user_pass);
            
            $response['status_code'] = 200;
            $response['status'] = 'true';
            $response['response_message'] = 'Password reset successfuly';
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

	    $user['user_pass'] = sanitize_text_field( $request->get_param( 'user_pass' ) );

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

        return $user;
    }
    
}

$ResetPassword = new ResetPassword();