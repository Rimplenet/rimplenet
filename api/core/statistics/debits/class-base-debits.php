<?php

class Rimplenet_Base_Debits_Count_Api {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	    //Add Required Files to Load
	    // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/get-debit-count.php';
    }
	
}


$Rimplenet_Base_Debits_Count_Api = new Rimplenet_Base_Debits_Count_Api();