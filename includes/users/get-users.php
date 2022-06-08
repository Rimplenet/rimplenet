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
        var_dump($this->get_users(null, null, 2, 2));
        return ob_get_clean();
    }

    public function get_users($access_token = null, $user_id = null, $page = 1, $users_per_page = 10)
    {

        $this->validate($access_token);


        if ($access_token == null) {

            if(!$this->authorization(get_current_user_id())) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);
            
            if($user_id !== null) return $this->response(200, true, "Successful", get_user_by('ID', $user_id), []);

            $total_users = count(get_users());

            $offset = $users_per_page * ($page - 1);
            $total_pages = ceil($total_users / $users_per_page);

            $args  = array(
                'fields'    => 'all_with_meta',
                'number'    => $users_per_page,
                'offset'    => $offset
            );

            $wp_user_query = new WP_User_Query($args);
            $get_users = $wp_user_query->get_results();

            foreach ($get_users as $get_user) {
                unset($get_user->data->user_pass);
            }

            return $this->response(200, true, "Successful", $get_users, []);

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

                    if($user_id !== null) return $this->response(200, true, "Successful", get_user_by('ID', $user_id), []);

                    $total_users = count(get_users());

                    $offset = $users_per_page * ($page - 1);
                    $total_pages = ceil($total_users / $users_per_page);

                    $args  = array(
                        'fields'    => 'all_with_meta',
                        'number'    => $users_per_page,
                        'offset'    => $offset
                    );

                    $wp_user_query = new WP_User_Query($args);
                    $get_users = $wp_user_query->get_results();

                    foreach ($get_users as $get_user) {
                        unset($get_user->data->user_pass);
                    }

                    return $this->response(200, true, "Successful", $get_users, []);
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