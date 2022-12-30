<?php

class RimplenetKycUsers
{


	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		add_action('admin_menu', array($this, 'rimplenet_admin_menu_kyc_users'));
	}


	public function rimplenet_admin_menu_kyc_users()
	{
		add_submenu_page(
			'edit.php?post_type=rimplenettransaction',
			__('Get KYC Users', 'rimplenet'),
			__('Get KYC Users', 'rimplenet'),
			'manage_options',
			'rimplenet_get_kyc_users',
			array($this, 'get_kyc_users')
		);
	}

	public function get_kyc_users()
	{
		include_once plugin_dir_path(dirname(__FILE__)) . 'kyc/layouts/get-kyc-users.php';
	}
}


$Rimplenet_Base_Users = new RimplenetKycUsers();
