<?php

class APIkeyPermission
{

    public $apikey;
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
        add_action('nll_api_request_started_api_key', array($this, 'validate_api_key'), 1, 3);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'api-key-permission', [
            'methods' => 'POST',
            'callback' => [$this, 'api_key_permit']
        ]);
    }

    public function validate_api_key($request, $allowed_roles, $action = '')
    {
        if (empty($allowed_roles)) return;

        # Check if an authorization is passed in the header
        $headers = $this->hasAuthorization();
        $key = explode(' ', $headers['Authorization']);
        $a = isset($key[0]) ? $key[0] : '';
        $b = isset($key[1]) ? $key[1] : '';
        if ($a === 'Key' || $a === 'Bearer') :
            // if ($a === 'Key') :
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
        // endif;
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
        AllowedIPAndDomains::ip_domains($key->allowedIpDomain ?? '');
        $permission = $this->apikey->getPermission($key->allowedActions, $key->permission);
        
        // $action = $this->apikey->applyAffix($action);
        
        # check if incoming permssion is a list of read-write
        if (is_array($permission[0])) {
            $permissions = array_filter($permission, function ($permission) use ($action) {
                if (in_array($action, $permission)) return $permission;
            });
            $permission = (!empty($permissions)) ? ((isset($permissions[0]) ? $permissions[0] : $permissions[1])) : $permission;
        }

        if (!is_array($permission)) {
            Res::error([
                'permissions' => $permission,
                'action' => $action,
                'errors' => "Something went wrong with the permissions"
            ], "Error: Permission", 400);
            echo json_encode(Utils::$response);
            exit;
        }

        if (!in_array($action, $permission)) {
            Res::error([
                'permissionType' => $key->permission,
                'permissions' => $permission,
                'permission' => 'Permission Denied for ' . $action,
                'recommendation' => 'Set allowed actions withing the given permissions list'
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            exit;
        }
    }

    /**
     * Ensure authorization header is present
     */
    public function hasAuthorization()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) :
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
        if (count($auth) < 2) :
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
