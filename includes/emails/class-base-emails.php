<?php

class Rimplenet_Base_Emails {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/base-mails.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/password-reset-mail.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/password-reset-post.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/change-password.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/change-password-post.php';
     require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/verify-email-mail.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'emails/verify-email-mail-post.php';
	//  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mails/update-mails.php';
	//  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mails/get-mails.php'; 
	//  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mails/delete-mails.php'; 
    }
	
}


$Rimplenet_Base_Emails = new Rimplenet_Base_Emails();