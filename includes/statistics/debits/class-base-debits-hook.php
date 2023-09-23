<?php

class Rimplenet_Base_Debits_Hook {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	    //Add Required Files to Load
		   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/BaseDebitHook.php';
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/rimplenet-add-debit-hook.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'debits/rimplenet-count-debit-hook.php';
    }
	
}


$Rimplenet_Base_Debits_Hook = new Rimplenet_Base_Debits_Hook();