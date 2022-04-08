<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

        register_rest_route(
            'rimplenet/v1', '/user',
            [
                'methods' => 'POST',
                'callback' => [$this, 'user_data']
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

    public function user_data(WP_REST_Request $request)
    {

        $headers = getallheaders();
        $access_token = $headers['Authorization'];


        if ($access_token) {

            try {
                
                $secret_key = "user123";

                $user = JWT::decode($access_token, new Key($secret_key, 'HS256'));

                $response['status_code'] = 200;
                $response['status'] = 'Success';
                $response['response_message'] = 'User Data';
                $response['data'] = $user;

            } catch (Exception $ex) {
                
                $response['status_code'] = 401;
                $response['status'] = 'Invalid';
                $response['response_message'] = $ex->getMessage();

            }

        } else {

            $response['status_code'] = 404;
            $response['response_message'] = 'User not found';
        }

        

        return new WP_REST_Response( $response );
    }
}

$UsersList = new UsersList();