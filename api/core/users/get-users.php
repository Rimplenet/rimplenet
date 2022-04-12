<?php

class GetUsers
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

        $headers = getallheaders();
        $access_token = $headers['Authorization'];


        if ($access_token) {

            try {
                
                $user = JWT::decode($access_token);

                if ($user === "Expired token") {
                    $response['status_code'] = 401;
                    $response['status'] = 'failed';
                    $response['error'] = 'Expired token';
                } elseif ($user === "Invalid signature") {
                    $response['status_code'] = 401;
                    $response['status'] = 'failed';
                    $response['error'] = 'Invalid signature';
                } elseif ($user) {
                    $response['status_code'] = 200;
                    $response['status'] = 'true';
                    $response['response_message'] = 'User Data';
                    $response['data'] = json_decode($user);
                }

            } catch (Exception $ex) {
                
                $response['status_code'] = 401;
                $response['status'] = 'Invalid';
                $response['response_message'] = $ex->getMessage();

            }

        } else {

            $response['status_code'] = 404;
            $response['status'] = 'failed';
            $response['response_message'] = 'User not found';
        }

        

        return new WP_REST_Response( $response );
    }
}

$GetUsers = new GetUsers();