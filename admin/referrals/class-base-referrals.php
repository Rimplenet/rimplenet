<?php

class Rimplenet_Admin_Base_Referrals {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/base-wallet.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/class-tab-manager.php';
	 // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/get-referrals.php';
	 // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/update-referrals.php';
	 // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'referrals/delete-referrals.php';
    }
	
}

$Rimplenet_Admin_Base_Referrals = new Rimplenet_Admin_Base_Referrals();