<?php

class Rimplenet_Base_Investment_Package {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'investments-packages/base-investments-packages.php';
    }
	
}


$Rimplenet_Base_Investment_Package = new Rimplenet_Base_Investment_Package();