<?php

class RimplenetAuthorization
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-authorization', array($this, 'authorization_test'));
    }

    public function authorization_test() {
        ob_start();
        var_dump($this->authorization("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE2NTEwOTQyODcsImV4cCI6MTY1MTA5Nzg4NywiZGF0YSI6eyJJRCI6IjI1IiwidXNlcl9sb2dpbiI6InRhaXdvb28iLCJ1c2VyX25pY2VuYW1lIjoidGFpd29vbyIsInVzZXJfZW1haWwiOiJ0YWl3b0BnbWFpbC5jb20iLCJ1c2VyX3VybCI6IiIsInVzZXJfcmVnaXN0ZXJlZCI6IjIwMjItMDQtMjcgMjA6NDE6NDkiLCJ1c2VyX2FjdGl2YXRpb25fa2V5IjoiIiwidXNlcl9zdGF0dXMiOiIwIiwiZGlzcGxheV9uYW1lIjoidGFpd29vbyJ9fQ.TX-xxGCl5JMn3IUu8bBdk3vAJr-FI5UQfOd_pztX0Vw"));
        return ob_get_clean();
    }

    public function authorization($access_token)
    {

        if ($access_token) {
            try {
                
                $user_access_token = JWT::decode($access_token);

                if ($user_access_token === "Expired token") {
                    return $this->response(400, "failed", "Validation error", [], ["Expired token"]);
                } elseif ($user_access_token === "Invalid signature") {
                    return $this->response(400, "failed", "Validation error", [], ["Invalid signature"]);
                } elseif ($user_access_token) {
                    return $this->response(200, true, "Authorization Successful", json_decode($user_access_token), []);
                }

            } catch (Exception $ex) {
                
                return $ex->getMessage();
                return $this->response(400, "failed", "Validation error", [], [$ex->getMessage()]);

            }
        } else {

            return $this->response(401, "failed", "Permission denied", [], ["unauthorize"=>"No authorization provided"]);
            
        }


    }

    public function response($status_code, $status, $message, $data=[], $error=[])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "error" =>$error
        ];
    }

}

$RimplenetAuthorization = new RimplenetAuthorization();