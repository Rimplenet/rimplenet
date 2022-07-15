<?php

class Rimplenet_Base_Emails_Api
{

	private $plugin_name;

	private $version;

	public function __construct()
	{
		$this->plugin_name = $plugin_name ?? 'RimplenetIn';
		$this->version = $version ?? 'v1';
		$this->load_required_files();
	}
	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'emails/post-password-reset.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'emails/password-reset-mail.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'emails/change-password.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'emails/change-password-post.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'emails/verify-email-mail.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'emails/verify-email-mail-post.php';
	}
}


$Rimplenet_Base_Emails_Api = new Rimplenet_Base_Emails_Api();
