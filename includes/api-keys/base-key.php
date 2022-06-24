<?php

namespace ApiKey;

use JWT;
use Wallets\Base;

class ApiKey extends Base
{

    protected $token;

    public function __construct($token = null) {
        if($token) $this->token = $token;
        else $this->token = bin2hex(random_bytes(14));
    }

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

    protected static function apiKey($string = null)
    {
        $token = new ApiKey($string);
        $hash = hash('sha256', md5('Riplenet'));
        if(!$string)
        return [
            'key' => $token->token,
            'hash' => base64_encode(openssl_encrypt($token->token, 'AES-256-CBC', $hash, 0, substr($hash, 0, 16)))
        ];
        return [
            'hash' => $token->token,
            'key' => openssl_decrypt(base64_decode($token->token), 'AES-256-CBC', $hash, 0, substr($hash, 0, 16))
        ];
    }
}
