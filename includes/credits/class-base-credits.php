<?php

class Rimplenet_Base_Credits {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/base-credits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/create-credits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/update-credits.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/get-credits.php'; 
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'credits/delete-credits.php'; 
    }
	
}


$Rimplenet_Base_Credits = new Rimplenet_Base_Credits();