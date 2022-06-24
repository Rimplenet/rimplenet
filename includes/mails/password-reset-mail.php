<?php

use Mails\Base;
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
        $sent=$this->sendRestPasswordMail($email);
        $message=$sent ? 'Email Sent' : 'Password Rest Email Not Sent';

        $sent ? $this->success($sent, $message) : $this->error($sent, $message);

        return $this->response;
   }
}