<?php

class Rimplenet_Base_Transfers {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/base-transfers.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/create-transfers.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/get-transfers.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/delete-transfers.php';
    }
	
}


$Rimplenet_Base_Transfers = new Rimplenet_Base_Transfers();