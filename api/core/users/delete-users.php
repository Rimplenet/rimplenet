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
            'rimplenet/v1', '/users/(?P<user_id>\d+)',
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'delete_user']
            ]
        );
    }

    public function delete_user(WP_REST_Request $request)
    {
        $user_id = sanitize_text_field($request->get_param('user_id'));

        $headers = getallheaders();
        $access_token = $headers['Authorization'];

        $user = new RimplenetDeleteUser();
        $delete_user = $user->delete_user(
            $user_id,
            $access_token
        );
        
        return new WP_REST_Response($delete_user);
    }
    
}

$RimplenetDeleteUserApi = new RimplenetDeleteUserApi();