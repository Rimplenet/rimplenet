<?php

class APIkeyPermission
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
		add_action('rimplenet_api_request_started', array($this, 'validate_api_key'), 1, 3);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'api-key-permission', [
            'methods' => 'POST',
            'callback' => [$this, 'api_key_permit']
        ]);
    }

    public function api_key_permit(WP_REST_Request $req)
    {
    }

    public function validate_api_key($request, $allowed_roles, $action = '')
    {
        if (empty($allowed_roles)) return;

        # Check if an authorization is passed in the header
        $headers = $this->hasAuthorization();
        [$a, $b] = explode(' ', $headers['Authorization']);
        if ($a == 'Key' || $a == 'Bearer') :
            if ($a == 'Key') :
                $this->apikey = new ApiKey;
                # decode and get api key information
                $key = (object) $this->apikey->decodeBasic($b, false);
                # if key is not true
                if (isset($key->scalar)) :
                    echo json_encode(Utils::$response);
                    status_header(Utils::$response['status_code']);
                    exit;
                endif;
                $this->authorizeKey($action, $key);
                return;
            endif;
        else :
            Res::error([
                'token' => 'No Token',
                'recommendation' => 'Provide an authorization header'
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            exit;
        endif;
    }

    /**
     * Authorize API key
     * @param string $action > API Key action
     * @param object $key > API Key information
     * @return void
     */
    public function authorizeKey($action, $key)
    {
        $permisson = $this->apikey->getPermission($key->allowedActions, $key->permission);
        // $action = $this->apikey->applyAffix($action);

        # check if incoming permssion is a list of read-write
        if(is_array($permisson[0])){
            $permissons = array_filter($permisson, function($permisson) use ($action) {
                if(in_array($action, $permisson)) return $permisson;
            });
            $permisson = !empty($permissons) ? $permissons[0] : $permisson;
        }
        
        if (!in_array($action, $permisson)) {
            Res::error([
                'permissionType' => $key->permission,
                'permissions' => $permisson,
                'permission' => 'Permission Denied for ' . $action,
                'recommendation' => 'Set allowed actions withing the given permissions list'
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            exit;
        }

        AllowedIPAndDomains::domains();
    }

    /**
     * Ensure authorization header is present
     */
    public function hasAuthorization()
    {
        $headers = getallheaders();
        if(!isset($headers['Authorization'])):
            Res::error([
                'authorization' => 'An authorization is required',
                'recommendation' => 'Provide an authorization header'
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            exit;
        endif;
        
        $auth = explode(' ', $headers['Authorization']);
        
        # Account for possible errors..
        # Sometimes if no header is passed... using PostMan.
        # Authorization returns Basic without a value
        if(count($auth) < 2):
            Res::error([
                'authorization' => 'An authorization is required',
                'recommendation' => 'Provide an authorization header'
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            exit;
        endif;

        return $headers;
    }
}

$APIkeyPermission = new APIkeyPermission();
