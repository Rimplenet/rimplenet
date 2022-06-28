<?php

namespace ApiKey;

use JWT;
use RimplenetAuthorization;
use Wallets\Base;

class ApiKey extends Base
{

    /**
     * Types of keys / key modes
     * @property array
     */
    static $apiKeyTypes = [
        'read-only',
        'read-write',
        'write-only'
    ];

    const API_KEYS = "API-KEYS";

    public function __construct()
    {
        add_action('rimplenet_api_request_started', array($this, 'validate_api_key'), 1, 3);
    }

    /**
     * Run before other methods run ...
     * check if user is an admin before running method
     * @return boolean
     */
    public function __call($method, $argc)
    {
        $method = "_" . $method;
        if (method_exists($this, $method)) :
            if ($this->pre() !== false) call_user_func_array([$this, $method], $argc);
        endif;
    }

    protected function pre()
    {
        // return false;
        return $this->requireAdmin();
    }

    protected function requireAdmin()
    {
        # Get headers
        $header = apache_request_headers();
        [$name, $token] = $header['Authorization'];
        $authorization = (new RimplenetAuthorization)->authorization($token);
        $this->error($authorization); return false;
        #Check authorization is set
        if (isset($header['Authorization'])) :
            #seperate token from bearer
            $tokenx = explode(' ', $header['Authorization']);
            # decode token
            $token = json_decode(JWT::decode($tokenx[1]));
            if (is_object($token)) :
                return $this->data = $token->data;
            else :
                # if token is invalid
                return $this->error([JWT::decode($tokenx[1])], "Token error", 400);
            endif;
        else :
            # if token is not set
            return $this->error(["No Token"], "Token not Found", 404);
        endif;
    }

    public function validate_api_key($request, $allowed_roles, $action)
    {

        $headers = getallheaders();
        if (preg_match('/^Basic/', $headers['Authorization'])) :
            self::success("Basic Tokken", '');
            echo json_encode($this->response);
            exit;
        else :
            $this->error("No Token");
            echo json_encode($get_auth);
            exit;
        endif;
    }


    /**
     * Validate token type provided is valid
     * @param string $tokenType
     * @return bool
     */
    protected static function isValidTokenType(string $tokenType)
    {
        return in_array($tokenType, self::$apiKeyTypes);
    }

    /**
     * Check if user role is administrator
     * @param array $roles
     * @return bool
     */
    protected static function isAdministrator(array $roles)
    {
        return in_array('administrator', $roles);
    }
}
