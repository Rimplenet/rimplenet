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
    include(plugin_dir_path( dirname( __FILE__ ) ) . 'mail-templates/verify-email-mail.php');

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }

  public function sendResetPasswordMail($email)
  {
    $to = $email;
    include(plugin_dir_path( dirname( __FILE__ ) ) . 'mail-templates/password-reset.php');

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }
}
