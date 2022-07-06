<?php

class Rimplenet_Base_MLM_Matrix_Group {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mlm-matrix/base-mlm-matrix-group.php';
	 
    }
	
}


$Rimplenet_Base_MLM_Matrix = new Rimplenet_Base_MLM_Matrix();