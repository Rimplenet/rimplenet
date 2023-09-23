<?php
class Token 
{
    public static function getUserByToken()
    {
        # Get headers
        $header = apache_request_headers();
        if(isset($header['Authorization'])){
            # seperate Authorization name from token
            $exploded = explode(' ', $header['Authorization']);
            $name = $exploded[0] ?? '';
            $token = $exploded[1] ?? false;
            if(!$token) return false;
            $authorization = (new RimplenetAuthorization)->authorization($token);
            $user = (object) $authorization['data']->user;
            if(!$user) return false;
            return $user;
        }
    }
}