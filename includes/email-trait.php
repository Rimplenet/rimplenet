<?php

namespace Traits\Email;

trait RimplenetEmailTrait
{
  public function sendWelcomeMail($email)
  {
    # code...
  }

  public function sendVerifyEmailMail($email)
  {
    $to = $email;
    $subject = 'Verify Your Email';
    $message = 'A request was sent to create an account. Please If this was not you, no action is required';
    $headers = 'just a test';

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }

  public function sendResetPasswordMail($email)
  {
    $to = $email;
    $subject = 'Password Reset Request';
    $message = 'A request was sent to reset your password. Please If this was not you, no action is required';
    $headers = 'just a test';

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }
}
