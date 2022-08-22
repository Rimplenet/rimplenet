<?php

class RimplenetLoginUserApi
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

        $user = new RimplenetLoginUser();
        $login_user = $user->login_user(
            $request->get_param('user_email'),
            $request->get_param('user_password'),
            $request->get_param('token_expiration')
        );
        
        return new WP_REST_Response($login_user, $login_user['status_code']);

    }
    
}

$RimplenetLoginUserApi = new RimplenetLoginUserApi();