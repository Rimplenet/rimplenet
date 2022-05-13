<?php

class Rimplenet_Base_Wallets {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wallets/base-wallet.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wallets/create-wallets.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wallets/get-wallets.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wallets/update-wallets.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wallets/delete-wallets.php';
    }
	
}


$Rimplenet_Base_Wallets = new Rimplenet_Base_Wallets();