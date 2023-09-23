<?php

class Rimplenet_Base_Utils {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/base-utils.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/response.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/token.php';
    }
	
}


$Rimplenet_Base_Utils = new Rimplenet_Base_Utils();