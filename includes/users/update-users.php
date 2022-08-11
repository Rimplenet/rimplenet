<?php

class RimplenetUpdateUser
{
    public $validation_error = [];
    // public $user_id;

    public function __construct()
    {
        add_shortcode('rimplenet-update-user', array($this, 'update_user_test'));
    }

    public function update_user_test() {
        ob_start();
        var_dump($this->update_user(48, "taiwo2@gmail.com", [], [], null));
        return ob_get_clean();
    }

    public function update_user($user_id, $user_email, $user_pass=[], $metas=[], $access_token = null)
    {

        $validation = $this->validate($user_id, $user_email, $user_pass);

        if ($access_token == null) {
            if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);

            if(!$this->authorization(get_current_user_id())) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorized"]);
            
            if (empty($this->validation_error)) {

                $request = [
                    "user_email" => $user_email,
                    "user_password" => $user_pass,
                    "metas" => $metas
                ];
                
                do_action('rimplenet_hooks_and_monitors_on_started', $action='rimplenet_update_users', $auth=null ,$request);
    
                $update_user = wp_update_user($validation);
    
                if(!empty($metas)) {
                    
                    foreach($metas as $meta_key=>$meta_value) {
                        
                        update_user_meta($user_id, $meta_key, $meta_value);
                    }
    
                }
    
                return $this->response(200, true, "User updated successfully", ["id"=>$update_user], $this->validation_error);
    
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
                    if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);

                    if(!$this->authorization($id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"Request is not authorized"]);

                    if (empty($this->validation_error)) {
    
                        $update_user = wp_update_user($validation);
            
                        if(!empty($metas)) {
                            
                            foreach($metas as $meta_key=>$meta_value) {
                                
                                update_user_meta($user_id, $meta_key, $meta_value);
                            }
            
                        }
            
                        return $this->response(200, true, "User updated successfully", ["id"=>$update_user], $this->validation_error);
            
                    }
                }

            } catch (Exception $ex) {
                
                return $ex->getMessage();
                return $this->response(400, "failed", "Validation error", [], [$ex->getMessage()]);

            }
        }

    }

    public function validate($user_id, $user_email, $user_pass=[])
    {
        $user = [];

        $user_email_error = [];
        $user_pass_error = [];

        $user_id_error = [];
        
        $sanitize_user_id = sanitize_text_field( $user_id );
        $sanitize_user_email = strtolower(sanitize_text_field($user_email));
        $sanitize_user_pass = $user_pass;

        if ($sanitize_user_id == '') {
            $user_id_error = 'user_id is required';
        }
        if (!empty($user_id_error)) {
            $this->validation_error[] = ['user_id' => $user_id_error];
        }

        $get_user = get_user_by('ID', $sanitize_user_id);

        if (!empty($get_user)) {

            $user['ID'] = $sanitize_user_id;
            
            if ($sanitize_user_email == '' || $sanitize_user_email == $get_user->user_email) {
                $user['user_email'] = $get_user->user_email;
            } else {
                if (email_exists($sanitize_user_email)) {
                    $user_email_error[] = 'user_email already taken';
                }
                if (!is_email($sanitize_user_email)) {
                    $user_email_error[] = 'Invalid email';
                }
                $user['user_email'] = $sanitize_user_email;
            }
            if (!empty($user_email_error)) {
                $this->validation_error[] = ['user_email' => $user_email_error];
            }

            if (array_key_exists('old_user_pass', $sanitize_user_pass) && array_key_exists('new_user_pass', $sanitize_user_pass)) {
            
                if ($sanitize_user_pass['old_user_pass'] != '' && $sanitize_user_pass['new_user_pass'] != '') {
                    
                    if (!wp_check_password($sanitize_user_pass['old_user_pass'], $get_user->user_pass, $user_id)) {
                        $user_pass_error[] = 'Incorrect old password';
                    }
                    if ($sanitize_user_pass['new_user_pass'] && strlen($sanitize_user_pass['new_user_pass']) < 6) {
                        $user_pass_error[] = 'Please enter at least 6 characters for the user_password';
                    }
                    $user['user_pass'] = $sanitize_user_pass['new_user_pass'];
                    if (!empty($user_pass_error)) {
                        $this->validation_error[] = ['user_pass' => $user_pass_error];
                    }
                }
            }

            return $user;

        } else {

            $this->validation_error[] = ['user_id' => 'User not found'];
            
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

$RimplenetUpdateUser = new RimplenetUpdateUser();