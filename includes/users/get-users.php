<?php

class RimplenetGetUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-get-user', array($this, 'get_user_test'));
    }

    public function get_user_test() {
        ob_start();
        var_dump($this->get_user(1, "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE2NTEwOTQyODcsImV4cCI6MTY1MTA5Nzg4NywiZGF0YSI6eyJJRCI6IjI1IiwidXNlcl9sb2dpbiI6InRhaXdvb28iLCJ1c2VyX25pY2VuYW1lIjoidGFpd29vbyIsInVzZXJfZW1haWwiOiJ0YWl3b0BnbWFpbC5jb20iLCJ1c2VyX3VybCI6IiIsInVzZXJfcmVnaXN0ZXJlZCI6IjIwMjItMDQtMjcgMjA6NDE6NDkiLCJ1c2VyX2FjdGl2YXRpb25fa2V5IjoiIiwidXNlcl9zdGF0dXMiOiIwIiwiZGlzcGxheV9uYW1lIjoidGFpd29vbyJ9fQ.TX-xxGCl5JMn3IUu8bBdk3vAJr-FI5UQfOd_pztX0Vw"));
        return ob_get_clean();
    }

    public function get_user($caller_id, $access_token)
    {

        $validation = $this->validate($caller_id, $access_token);


        if (empty($this->validation_error)) {

            if(!$this->authorization($caller_id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);

            if ($access_token) {
                try {
                    
                    $user_access_token = JWT::decode($access_token);

                    if ($user_access_token === "Expired token") {
                        return $this->response(400, "failed", "Validation error", [], ["Expired token"]);
                    } elseif ($user_access_token === "Invalid signature") {
                        return $this->response(400, "failed", "Validation error", [], ["Invalid signature"]);
                    } elseif ($user_access_token) {
                        return $this->response(200, true, "Successful", [json_decode($user_access_token)], []);
                    }

                } catch (Exception $ex) {
                    
                    return $ex->getMessage();
                    return $this->response(400, "failed", "Validation error", [], [$ex->getMessage()]);

                }
            } else {

                return $this->response(404, "failed", "Validation error", [], ["User not found"]);
                
            }


        }

        return $this->response(400, "failed", "Validation error", [], $this->validation_error);
        
    }

    public function validate($caller_id, $access_token)
    {

        $access_token_error = [];
        $caller_id_error = [];

        $user['caller_id'] = sanitize_text_field($caller_id);
        $user['access_token'] = $access_token;

        if ($user['access_token'] == '') {
            $access_token_error[] = 'access_token is required';
        }
        if (!empty($access_token_error)) {
            $this->validation_error[] = ['access_token' => $access_token_error];
        }

        if ($user['caller_id'] == '') {
            $caller_id_error[] = 'caller_id is required';
        }
        if (!empty($caller_id_error)) {
            $this->validation_error[] = ['caller_id' => $caller_id_error];
        }

    }

    public function response($status_code, $status, $response_message, $data=[], $error=[])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "response_message" => $response_message,
            "data" => $data,
            "error" =>$error
        ];
    }

    public function authorization($caller_id)
    {
        $user = get_user_by('ID', $caller_id);

        if (user_can($user, 'administrator')) {
            
            return true;

        }

        return false;
    }
}

$RimplenetGetUser = new RimplenetGetUser();