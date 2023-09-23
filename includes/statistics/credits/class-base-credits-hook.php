<?php

class Rimplenet_Base_Credits_Hook {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	    //Add Required Files to Load
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/BaseCreditHook.php';
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/rimplenet-add-credit-hook.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/rimplenet-count-credit-hook.php';
    }
	
}


$Rimplenet_Base_Credits_Hook = new Rimplenet_Base_Credits_Hook();