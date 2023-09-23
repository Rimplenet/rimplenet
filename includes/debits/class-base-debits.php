<?php

class Rimplenet_Base_Debits {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/base-debits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/create-debits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/update-debits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/get-debits.php'; 
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/delete-debits.php'; 
    }
	
}


$Rimplenet_Base_Debits = new Rimplenet_Base_Debits();