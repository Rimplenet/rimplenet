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

        $headers = getallheaders();
        [$a, $b] = explode(' ', $headers['Authorization']);
        if ($a == 'Basic' || $a == 'Bearer') :
            if ($a == 'Basic') :
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
            echo json_encode("No token");
            exit;
        endif;
    }

    /**
     * Authorize API key
     * @param
     */
    public function authorizeKey($action, $key)
    {
        $permisson = $this->apikey->getPermission($key->allowedActions, $key->permission);
        if (!in_array($action, $permisson)) {
            Res::error([
                'permissionType' => $key->permission,
                'permissions' => $permisson,
                'permission' => 'Permission Denied for ' . $action,
            ], 'Permission Denied', 403);
            echo json_encode(Utils::$response);
            status_header(Utils::$response['status_code']);
            exit;
        }
    }
}

$APIkeyPermission = new APIkeyPermission();
