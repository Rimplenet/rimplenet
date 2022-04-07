<?php

class UsersList
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/users-list',
            [
                'methods' => 'GET',
                'callback' => [$this, 'users_list']
            ]
        );
    }

    public function users_list(WP_REST_Request $request)
    {

        $users_list = new WP_User_Query(['orderby' => 'ID', 'order' => 'DESC']);

        if (count($users_list->get_results()) > 0) {

            $response['status_code'] = 200;
            $response['status'] = 'Success';
            $response['response_message'] = 'Users List';
            $response['data'] = $users_list->get_results();

        } else {

            $response['status_code'] = 404;
            $response['response_message'] = 'Users not found';
        }

        

        return new WP_REST_Response( $response );
    }
}

$UsersList = new UsersList();