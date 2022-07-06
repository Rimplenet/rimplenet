<?php

use Emails\Base;
use Traits\Email\RimplenetEmailTrait;

class UpdatePasswordResetMail extends Base
{

    public $prop;
    use RimplenetEmailTrait;
    public function __construct()
    {
        # code...
    }

    public function validate(array $param = [])
    {

        $this->prop = empty($param) ? $this->req : $param;
        extract($this->prop);

        $this->prop['user_id'] = $this->getUserId('email', $email);

        if ($this->checkToken()) {
            if ($this->checkPasswordMatch() && $this->verifyPassword()) {
                $passwordchange = wp_set_password($new_password, $this->prop['user_id']);
                $message = "Password Changed Successfully";
                $this->success($passwordchange, $message);
                return $this->response;
            }
            $message = "Passwords do not match";
            $this->error(false, $message);
            return $this->response;
        }
        $message = "Invalid token";
        $this->error(false, $message);
        return $this->response;
    }

    public function checkToken()
    {
        $user = get_user_meta($this->prop['user_id'] ?? 1, 'token_to_reset_password');

        if ($this->prop['token'] == $user) {
            return true;
        }

        return false;
    }

    public function checkPasswordMatch()
    {
        if ($this->prop['new_password'] == $this->prop['confirm_password']) {
            return true;
        }

        return false;
    }

    public function verifyPassword()
    {
        $user = get_user_by('email', $this->prop['email'] );
        if ( $user && wp_check_password( $this->prop['password'], $user->data->user_pass, $user->ID ) ) {
            return true;
        } else {
            return false;
        }
    }
}
