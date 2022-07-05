<?php

use Emails\Base;
use Traits\Email\RimplenetEmailTrait;

class RimplenetVerifyEmailMail extends Base
{
    use RimplenetEmailTrait;
   public function __construct()
   {
    # code...
   }

   public function send($email)
   {
        $sent=$this->sendVerifyEmailMail($email);
        $message=$sent ? 'Verification Mail Email Sent' : 'Email Not Sent';

        $sent ? $this->success($sent, $message) : $this->error($sent, $message);

        return $this->response;
   }
}