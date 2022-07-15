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

     public function send($email, $sendmail = false)
     {

          $user_id = $this->getUserId('email', $email);

          $sent['token_to_verify_email'] = $this->generateToken();
          $this->storeverifyToken($user_id, $sent['token_to_verify_email']);

          if ($sendmail) {
               $sent = $this->sendVerifyEmailMail($email, $sent['token_to_verify_email']);
               $message = $sent ? 'Verification Mail Email Sent' : 'Email Not Sent';

               $sent ? $this->success($sent, $message) : $this->error($sent, $message);

               return $this->response;
          }

          $message = $sent['token_to_verify_email'] ? 'Token Generated' : 'Token Not Generated';
          $sent['token_to_verify_email'] ? $this->success($sent, $message) : $this->error($sent, $message);
          return $this->response;
     }
}
