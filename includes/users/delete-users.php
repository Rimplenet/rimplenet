<?php
require_once(ABSPATH.'wp-admin/includes/user.php');

class RimplenetDeleteUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-delete-user', array($this, 'delete_user_test'));
    }

    public function delete_user_test() {
        ob_start();
        var_dump($this->delete_user(47));
        return ob_get_clean();
    }

    public function delete_user($user_id, $access_token = null)
    {
        // global $wpdb;

        $validation = $this->validate($user_id);

        if ($access_token == null) {

            if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);
    
            if (empty($this->validation_error)) {

                $request = [
                    "user_id" => $user_id
                ];
                
                do_action('rimplenet_hooks_and_monitors_on_started', $action='rimplenet_delete_users', $auth=null ,$request);
    
                if(!$this->authorization(get_current_user_id())) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorized"]);
    
                // $table='wp_users';
                $deleted = wp_delete_user($user_id);
    
                if ($deleted) return $this->response(200, true, "User deleted successfully", [], []);
    
                return $this->response(404, "Failed", "User not found", [], []);
    
            }

        } else {

            try {

                $user_access_token = JWT::decode($access_token);
                $id = json_decode($user_access_token)->user->ID;
                
                if ($user_access_token === "Expired token") {
                    return $this->response(400, "failed", "Validation error", [], ["Expired token"]);
                } elseif ($user_access_token === "Invalid signature") {
                    return $this->response(400, "failed", "Validation error", [], ["Invalid signature"]);
                } elseif ($user_access_token) {
                    if(!$this->authorization($id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"Request is not authorized"]);

                    if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);
    
                    if (empty($this->validation_error)) {
            
                        $deleted = wp_delete_user($user_id);
            
                        if ($deleted) {
                            return $this->response(200, true, "User deleted successfully", [], []);
                        }
            
                        return $this->response(404, "Failed", "User not found", [], []);
            
                    }
                }

            } catch (Exception $ex) {
            
                return $ex->getMessage();
                return $this->response(400, "failed", "Validation error", [], [$ex->getMessage()]);

            }

        }


    }

    public function validate($user_id)
    {

        $user_id_error = [];

        if ($user_id == '') {
            $user_id_error[] = 'user_id is required';
        }
        if (!empty($user_id_error)) {
            $this->validation_error[] = ['user_id' => $user_id_error];
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

    public function authorization($caller_id)
    {
        $user = get_user_by('ID', $caller_id);

        if (user_can($user, 'administrator')) {
            
            return true;

        }

        return false;
    }
    
}

$RimplenetDeleteUser = new RimplenetDeleteUser();