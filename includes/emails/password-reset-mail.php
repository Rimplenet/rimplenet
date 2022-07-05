<?php

use Emails\Base;
use Traits\Email\RimplenetEmailTrait;

class RimplenetPasswordResetMail extends Base
{
    use RimplenetEmailTrait;
   public function __construct()
   {
    # code...
   }

   public function send($email)
   {
        $sent=$this->sendResetPasswordMail($email);
        $message=$sent ? 'Email Sent' : 'Password Reset Email Not Sent';

        $sent ? $this->success($sent, $message) : $this->error($sent, $message);

        return $this->response;
   }
}