<?php

namespace ApiKey;

use JWT;
use Wallets\Base;

class ApiKey extends Base
{
    
    const API_KEYS= "API-KEYS";

    public function __call($method, $argc)
    {
        $method = "_" . $method;
        if (method_exists($this, $method)) :
            if ($this->pre() !== false) call_user_func_array([$this, $method], $argc);
        endif;
    }

    protected function pre()
    {
        return $this->requireAuthorization();
    }

    protected function requireAuthorization()
    {
        # Get headers
        $header = apache_request_headers();
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
}
