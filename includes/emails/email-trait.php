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
    include(plugin_dir_path( dirname( __FILE__ ) ) . 'email-templates/verify-email-mail.php');

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }

  public function sendResetPasswordMail($email)
  {
    $to = $email;
    include(plugin_dir_path( dirname( __FILE__ ) ) . 'email-templates/password-reset.php');

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }

  public function sendPasswordChange($email, $password)
  {
    $to = $email;
    include(plugin_dir_path( dirname( __FILE__ ) ) . 'email-templates/change-user-password.php');

    if (wp_mail( $to, $subject, $message )) {
        return true;
    } else {
        return false;
    }
  }
}
