<?php

class RimplenetCreateUserApi
{

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
        do_action('rimplenet_api_request', $request, $allowed_roles=['administrator'], $action='rimplenet_get_users');

        $headers = getallheaders();
        $access_token = $headers['Authorization'];

        $user = new RimplenetCreateUser();
        $create_user = $user->create_user(
            $request->get_param('user_email'),
            $request->get_param('username'),
            $request->get_param('user_password'),
            [
                "first_name" => $request->get_param('first_name'),
                "last_name" => $request->get_param('last_name')
            ],
            $access_token
        );
        
        return new WP_REST_Response($create_user, $create_user['status_code']);
        
    }

}

$RimplenetCreateUserApi = new RimplenetCreateUserApi();