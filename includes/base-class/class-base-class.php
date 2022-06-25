<?php

class Rimplenet_Base_Controller_Class {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'base-class/base-controller.php';
    }
	
}


$Rimplenet_Base_Controller_Class = new Rimplenet_Base_Controller_Class();