<?php

class Rimplenet_Base_Shortcodes {

	public function __construct() {
		$this->load_required_files();
	}
	
    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/shortcodes/debits/class-base-debits.php';
     require_once plugin_dir_path(dirname( __FILE__ ) ) . '/shortcodes/credits/class-base-credits.php';
	//  require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/users/class-base-users.php';
	//  require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/referrals/class-base-referrals.php';
	
    }
	
}


$Rimplenet_Base_Shortcodes = new Rimplenet_Base_Shortcodes();

?>