<?php

class Rimplenet_Base_Admin {

	public function __construct() {
		$this->load_required_files();
	}
	
    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/wallets/class-base-wallets.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/withdrawals/class-base-withdrawals.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/credits/class-base-credits.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/debits/class-base-debits.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/users/class-base-users.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/referrals/class-base-referrals.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/transfers/class-base-transfers.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/api-keys/class-base-api-keys.php';
	//  require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/general/class-base-general-settings.php';
	
    }
	
}


$Rimplenet_Base_Admin = new Rimplenet_Base_Admin();

?>