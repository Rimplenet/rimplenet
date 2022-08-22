<?php

class RimplenetAuthorizationApi
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_routes'));
        add_action( 'rimplenet_api_request_started', array($this, 'validate_jwt'), 1, 3 );
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
        $access_token = explode(" ", $headers['Authorization'])[1];

        $auth = new RimplenetAuthorization();
        $get_auth = $auth->authorization(
            $access_token
        );
        
        return new WP_REST_Response($get_auth);

    }

    public function validate_jwt($request, $allowed_roles, $action) 
    {
        
        $headers = getallheaders();
        
        $auth = new RimplenetAuthorization();
        
        if (empty($allowed_roles)) return;
        
        if (!$headers['Authorization']) {
            $response = $auth->response(401, "failed", "Permission denied", [], ["unauthorize"=>"No authorization provided"]);
            status_header($response['status_code']);
            echo json_encode($response);
            exit;
        }
        
        if (explode(" ", $headers['Authorization'])[0] != "Bearer") return;
        
        $access_token = explode(" ", $headers['Authorization'])[1] ?? null;
        $get_auth = $auth->authorization(
            $access_token
        );

        if (!empty($get_auth['error'])) {
            status_header($get_auth['status_code']);
            echo json_encode($get_auth);
            exit;
        }

        if(in_array($get_auth['data']->user->roles[0], $allowed_roles)){
            // $this->ID = $get_auth['data']->data->ID;
            return true;
        } else {
            $response = $auth->response(401, "failed", "Permission denied", [], ["unauthorize"=>"Request is not authorized"]);
            status_header($response['status_code']);
            echo json_encode($response);
            exit;
        }
    }
    
}

$RimplenetAuthorizationApi = new RimplenetAuthorizationApi();