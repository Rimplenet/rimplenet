<?php

class Rimplenet_Base_Investment_Group {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   		//Add Required Files to Load
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'investments-group/class-base-investments-group.php';
    }
	
}


$Rimplenet_Base_Investment_Group = new Rimplenet_Base_Investment_Group();