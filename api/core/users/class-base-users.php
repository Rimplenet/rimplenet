<?php

class Rimplenet_Base_Users_Api {

	public function __construct() {
		$this->load_required_files();
	}

    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/create-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/delete-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/get-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/search-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/login.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/update-users.php';
    }
	
}

$Rimplenet_Base_Users_Api = new Rimplenet_Base_Users_Api();