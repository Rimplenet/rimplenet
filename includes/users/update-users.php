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
        var_dump($this->update_user(1, 28, "taiwo1@gmail.com", '', ["somename"=>"ttttt","somename1"=>"bbbbbb"]));
        return ob_get_clean();
    }

    public function update_user($caller_id, $user_id, $user_email, $user_pass=null, $metas=[])
    {

        $validation = $this->validate($caller_id, $user_id, $user_email, $user_pass);

        if(!$this->authorization($caller_id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);
        
        if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);
        
        if (empty($this->validation_error)) {

            $update_user = wp_update_user(['ID'=>$user_id, 'user_email'=>$user_email, 'user_pass'=>$user_pass]);

            if(!empty($metas)) {
                
                foreach($metas as $meta_key=>$meta_value) {
                    
                    update_user_meta($user_id, $meta_key, $meta_value);
                }

            }

            return $this->response(200, true, "User updated", ["id"=>$update_user], $this->validation_error);

        }

    }

    public function validate($caller_id, $user_id, $user_email, $user_pass)
    {
        $user_email_error = [];
        $user_pass_error = [];

        $user_id_error = [];
        $caller_id_error = [];

        $user['user_id'] = sanitize_text_field( $user_id );
        $user['caller_id'] = sanitize_text_field( $caller_id );

        if ($user['user_id'] == '') {
            $user_id_error = 'user_id is required';
        }
        if (!empty($user_id_error)) {
            $this->validation_error[] = ['user_id' => $user_id_error];
        }

        if ($user['caller_id'] == '') {
            $caller_id_error = 'caller_id is required';
        }
        if (!empty($caller_id_error)) {
            $this->validation_error[] = ['caller_id' => $caller_id_error];
        }

        if ($get_user = get_user_by('ID', $user_id)) {

            $user['ID'] = $user['user_id'];
            
            $user['user_email'] = sanitize_text_field($user_email);
            $user['user_pass'] = $user_pass;

            if ($user['user_email'] == '') {
                $user['user_email'] = $get_user->user_email;
            }
            if ($user['user_email'] && !is_email($user['user_email'])) {
                $user_email_error[] = 'Invalid email';
            }
            if (!empty($user_email_error)) {
                $this->validation_error[] = ['user_email' => $user_email_error];
            }

            if ($user['user_pass'] == '') {
                $user['user_pass'] = $get_user->user_email;
            }
            if ($user['user_pass'] && strlen($user['user_pass']) < 6) {
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

        } else {

            $this->validation_error[] = ['user_id' => 'User not found'];
            
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

$RimplenetUpdateUser = new RimplenetUpdateUser();