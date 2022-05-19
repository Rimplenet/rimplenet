<?php

class RimplenetAuthorizationApi
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    public function register_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/authorization',
            [
                'methods' => 'POST',
                'callback' => [$this, 'authorization']
            ]
        );
    }

    public function authorization(WP_REST_Request $request)
    {

        $headers = getallheaders();
        $access_token = $headers['Authorization'];

        $auth = new RimplenetAuthorization();
        $get_auth = $auth->authorization(
            $access_token,
        );
        
        return new WP_REST_Response($get_auth);

    }
}

$RimplenetAuthorizationApi = new RimplenetAuthorizationApi();