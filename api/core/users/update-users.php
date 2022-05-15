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

        $user = new RimplenetUpdateUser();
        $update_user = $user->update_user(
            $request->get_param('caller_id'),
            $request->get_param('user_id'),
            $request->get_param('user_email'),
            $request->get_param('user_pass'),
            [
                "first_name" => $request->get_param('first_name'),
                "last_name" => $request->get_param('last_name')
            ]
        );
        
        return new WP_REST_Response($update_user);
        
    }

}

$RimplenetUpdateUserApi = new RimplenetUpdateUserApi();