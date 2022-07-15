<?php

class Rimplenet_Base_Withdrawals {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'withdrawals/base-withdrawal.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'withdrawals/create-withdrawals.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'withdrawals/get-withdrawals.php';
    }
	
}


$Rimplenet_Base_Withdrawals = new Rimplenet_Base_Withdrawals();