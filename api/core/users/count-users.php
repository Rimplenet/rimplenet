<?php

class RimplenetCountUserApi
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/count/users',
            [
                'methods' => 'GET',
                'callback' => [$this, 'count_users']
            ]
        );
    }

    public function count_users(WP_REST_Request $request)
    {
        do_action('nll_api_request_started', $request, $allowed_roles=['administrator', 'subscriber'], $action='rimplenet_get_users');

        $user_id = sanitize_text_field($request->get_param('user_id'));
        $page = sanitize_text_field($request->get_param('page'));

        $headers = getallheaders();
        $access_token = explode(" ", $headers['Authorization'])[1];

        $user = new RimplenetGetUser();
        $count_users = $user->count_users();
        
        return new WP_REST_Response($get_user, $get_user['status_code']);

    }
}

$RimplenetCountUserApi = new RimplenetCountUserApi();