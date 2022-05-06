<?php

class Rimplenet_Base_Wallets {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/create-wallets.php';
	 //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/get-users.php';
	 //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/update-users.php';
	 //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/delete-users.php';
    }
	
}


$Rimplenet_Base_Wallets = new Rimplenet_Base_Wallets();