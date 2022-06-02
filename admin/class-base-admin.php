<?php

class Rimplenet_Base_Admin {

	public function __construct() {
		$this->load_required_files();
	}
	
    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/wallets/class-base-wallets.php';
	 require_once plugin_dir_path(dirname( __FILE__ ) ) . '/admin/users/class-base-users.php';
	
    }
	
}


$Rimplenet_Base_Admin = new Rimplenet_Base_Admin();

?>