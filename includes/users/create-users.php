<?php

class RimplenetCreateUser
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-create-user', array($this, 'create_user_test'));
    }

    public function create_user_test() {
        ob_start();
        var_dump($this->create_user(1, "taiwo@gmail.com", "taiwooo", "abc123",["somename"=>"ttttt","somename1"=>"aaaaaaaa"]));
        return ob_get_clean();
    }

    public function create_user($caller_id, $user_email, $user_login, $user_pass, $metas=[])
    {
        
        $validation = $this->validate($caller_id, $user_email, $user_login, $user_pass);

        if(!$this->authorization($caller_id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);

        if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);
        
        if (empty($this->validation_error)) {


            $new_user = wp_insert_user(['user_email'=>$user_email, 'user_login'=>$user_login, 'user_pass'=>$user_pass]);

            if(!empty($metas)) {
                
                foreach($metas as $meta_key=>$meta_value) {
                    
                    add_user_meta($new_user, $meta_key, $meta_value);
                }

            }

            return $this->response(200, true, "New user create", ["id"=>$new_user], $this->validation_error);
        
            
        }

    }

    public function validate($caller_id, $user_email, $user_login, $user_pass)
    {
        $user_login_error = [];
        $user_email_error = [];
        $user_pass_error = [];

        $user['caller_id'] = sanitize_text_field($caller_id);

        $user['user_login'] = sanitize_text_field($user_login);
	    $user['user_email'] = sanitize_text_field($user_email);
	    $user['user_pass'] = $user_pass;

        if ($user['caller_id'] == '') {
            $caller_id_error[] = 'caller_id is required';
        }
        if (!empty($caller_id_error)) {
            $this->validation_error[] = ['caller_id' => $caller_id_error];
        }

        if ($user['user_login'] == '') {
            $user_login_error[] = 'user_login is required';
        }
        if (strlen($user['user_login']) < 4) {
            $user_login_error[] = 'user_login must be atleast 4 chars';
        }
        if (username_exists($user['user_login'])) {
            $user_login_error[] = 'user_login already taken';
        }
        if (!empty($user_login_error)) {
            $this->validation_error[] = ['user_login' => $user_login_error];
        }

        if ($user['user_email'] == '') {
            $user_email_error[] = 'user_email is required';
        }
        if ($user['user_email'] && !is_email($user['user_email'])) {
            $user_email_error[] = 'Invalid user_email';
        }
        if (email_exists($user['user_email'])) {
            $user_email_error[] = 'user_email already taken';
        }
        if (!empty($user_email_error)) {
            $this->validation_error[] = ['user_email' => $user_email_error];
        }

        if ($user['user_pass'] == '') {
            $user_pass_error[] = 'user_pass is required';
        }
        if (strlen($user['user_pass']) < 6) {
            $user_pass_error[] = 'Please enter at least 6 characters for the user_pass';
        }
        // if (preg_match('/.*[a-z]+.*/i', $user['user_pass']) == 0) {
        //     $user_pass_error[] = 'user_pass needs at least one letter';
        // }
        // if (preg_match('/.*\d+.*/i', $user['user_pass']) == 0) {
        //     $user_pass_error[] = 'user_pass needs at least one number';
        // }
        if (!empty($user_pass_error)) {
            $this->validation_error[] = ['user_pass' => $user_pass_error];
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

$RimplenetCreateUser = new RimplenetCreateUser();