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
        var_dump($this->delete_user(2, 27));
        return ob_get_clean();
    }

    public function delete_user($caller_id, $user_id)
    {
        // global $wpdb;

        $validation = $this->validate($caller_id, $user_id);

        if(!empty($this->validation_error)) return $this->response(400, "failed", "Validation error", [], $this->validation_error);

        if (empty($this->validation_error)) {

            if(!$this->authorization($caller_id)) return $this->response(403, "failed", "Permission denied", [], ["unauthorize"=>"caller_id is not authorize"]);

            // $table='wp_users';
            $deleted = wp_delete_user($user_id);

            if ($deleted) {
                return $this->response(200, true, "Deleted", [], []);
            }

            return $this->response(404, "Failed", "User not found", [], []);

        }

    }

    public function validate($caller_id, $user_id)
    {

        $user_id_error = [];
        $caller_id_error = [];

        if ($user_id == '') {
            $user_id_error[] = 'user_id is required';
        }
        if (!empty($user_id_error)) {
            $this->validation_error[] = ['user_id' => $user_id_error];
        }

        if ($caller_id == '') {
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

$RimplenetDeleteUser = new RimplenetDeleteUser();