<?php

class Rimplenet_Base_User_Wallet_Balance {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'user-wallet-balance/base-user-wallet-balance.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'user-wallet-balance/user-wallet-balance.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'user-wallet-balance/user-wallet-balance-multi.php';
    }
	
}


$Rimplenet_Base_User_Wallet_Balance = new Rimplenet_Base_User_Wallet_Balance();