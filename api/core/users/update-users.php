<?php

class RimplenetUpdateUserApi
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
            'rimplenet/v1', '/users',
            [
                'methods' => 'PUT',
                'callback' => [$this, 'update_user']
            ]
        );
    }

    public function update_user(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles=['administrator'], $action='rimplenet_update_users');
        
        $headers = getallheaders();
        $access_token = explode(" ", $headers['Authorization'])[1];

        $user = new RimplenetUpdateUser();
        $update_user = $user->update_user(
            $request->get_param('user_id'),
            $request->get_param('user_email'),
            [
                "old_user_password" => $request->get_param('old_user_pass'),
                "new_user_password" => $request->get_param('new_user_pass')
            ],
            [
                "first_name" => $request->get_param('first_name'),
                "last_name" => $request->get_param('last_name'),
                "phone_number" => $request->get_param('phone_number')
            ],
            $access_token
        );
        
        return new WP_REST_Response($update_user, $update_user['status_code']);
        
    }

}

$RimplenetUpdateUserApi = new RimplenetUpdateUserApi();