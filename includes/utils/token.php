<?php
class Token 
{
    public static function getUserByToken()
    {
        # Get headers
        $header = apache_request_headers();
        # seperate Authorization name from token
        [$name, $token] = explode(' ', $header['Authorization']);
        if(!$token) return false;
        $authorization = (new RimplenetAuthorization)->authorization($token);
        $user = (object) $authorization['data']->user;
        if(!$user) return false;
        return $user;
    }
}