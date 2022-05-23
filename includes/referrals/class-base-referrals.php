<?php

class Rimplenet_Base_Referrals {

	private $plugin_name;

	private $version;

	public function __construct()
	{
		$this->plugin_name = $plugin_name ?? 'RimplenetIn';
		$this->version = $version ?? 'v1';
		$this->load_required_files();
	}
  private function load_required_files() {
   //Add Required Files to Load
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/base-referrals.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/get-referrals.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/create-referrals.php';
//    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/update-referrals.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/delete-referrals.php';
  }
	
}


$Rimplenet_Base_Referrals = new Rimplenet_Base_Referrals();