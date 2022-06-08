<?php

class RimplenetGetUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-get-user', array($this, 'get_users_test'));
    }

    public function get_users_test() {
        ob_start();
        var_dump($this->get_users(null));
        return ob_get_clean();
    }

    public function get_users($access_token = null, $id = null)
    {

        $this->validate($access_token);


        if ($access_token == null) {

            if(!$this->authorization(get_current_user_id())) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);
            
            if($id !== null) return $this->response(200, true, "Successful", get_user_by('ID', $id), []);

            return $this->response(200, true, "Successful", get_users(), []);

        } else {

            try {
                    
                $user_access_token = JWT::decode($access_token);
                $id = json_decode($user_access_token)->data->ID;
                
                if ($user_access_token === "Expired token") {
                    return $this->response(400, "failed", "Validation error", [], ["Expired token"]);
                } elseif ($user_access_token === "Invalid signature") {
                    return $this->response(400, "failed", "Validation error", [], ["Invalid signature"]);
                } elseif ($user_access_token) {
                    if(!$this->authorization($id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);
                    return $this->response(200, true, "Successful", get_users(), []);
                }

            } catch (Exception $ex) {
                
                return $ex->getMessage();
                return $this->response(400, "failed", "Validation error", [], [$ex->getMessage()]);

            }

        }

        return $this->response(400, "failed", "Validation error", [], $this->validation_error);
        
    }

    public function validate($access_token)
    {

        $access_token_error = [];

        $user['access_token'] = $access_token;

        if ($user['access_token'] == '') {
            $access_token_error[] = 'access_token is required';
        }
        if (!empty($access_token_error)) {
            $this->validation_error[] = ['access_token' => $access_token_error];
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