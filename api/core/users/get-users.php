<?php

class RimplenetGetUserApi
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
                'methods' => 'GET',
                'callback' => [$this, 'get_users']
            ]
        );
    }

    public function get_users(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles=['administrator', 'subscriber'], $action='rimplenet_get_users');

        $user_id = sanitize_text_field($request->get_param('user_id'));
        $page = sanitize_text_field($request->get_param('page'));

        $headers = getallheaders();
        $access_token = explode(" ", $headers['Authorization'])[1];

        $user = new RimplenetGetUser();
        $get_user = $user->get_users(
            $access_token,
            $user_id ? $user_id : null,
            $page ? $page : 1,
        );
        
        return new WP_REST_Response($get_user, $get_user['status_code']);

    }
}

$RimplenetGetUserApi = new RimplenetGetUserApi();