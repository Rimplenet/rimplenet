<?php

class Rimplenet_Base_Statistics_Api {


	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	    //Add Required Files to Load
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'statistics/get-statistics-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'statistics/get-statistics-user-by-date.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'statistics/get-statistics-sitewide-by-date.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'statistics/get-statistics-sitewide.php';
    }
	
}


$Rimplenet_Base_Statistics_Api = new Rimplenet_Base_Statistics_Api();