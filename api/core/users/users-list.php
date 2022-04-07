
  
<?php

class UsersList
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', '/users-list', array([
            'methods' => 'GET',
            'callback' => [$this, 'users_list']
        ]));
    }

    public function users_list(WP_REST_Request $request)
    {

        $dummy_users = [
            'user 1' =>  'Taiwo Taiwo',
            'user 2' =>  'Flash Flash',
            'user 3' =>  'Unknown Unknown'
        ];

        $dt = [
            'status_code' => 200,
            'status' => 'Success',
            'response_message' => 'Users List'
        ];

        extract($dt);

        return new WP_REST_Response(
            array(
                'status_code' => $status_code,
                'status' => $status,
                'message' => $response_message,
                'data' => $dummy_users
            )
        );
    }
}

$UsersList = new UsersList();