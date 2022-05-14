<?php

class Rimplenet_Base_Txn {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'txn/base-txn.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'txn/create-txn.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'txn/get-txn.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'txn/update-txn.php';
    }
	
}


$Rimplenet_Base_Txn = new Rimplenet_Base_Txn();