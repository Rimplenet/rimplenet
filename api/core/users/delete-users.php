<?php
require_once(ABSPATH.'wp-admin/includes/user.php');

class RimplenetDeleteUserApi
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
                'methods' => 'DELETE',
                'callback' => [$this, 'delete_user']
            ]
        );
    }

    public function delete_user(WP_REST_Request $request)
    {

        $user = new RimplenetDeleteUser();
        $delete_user = $user->delete_user(
            $request->get_param('caller_id'),
            $request->get_param('user_id')
        );
        
        return new WP_REST_Response($delete_user);
    }
    
}

$RimplenetDeleteUserApi = new RimplenetDeleteUserApi();