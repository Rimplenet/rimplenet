<?php
require 'jwt.php';

class Rimplenet_Base_Users_Api {

	private $plugin_name;

	private $version;

	public function __construct() {
		$this->plugin_name = $plugin_name ?? '';
		$this->version = $version ?? '';
		$this->load_required_files();
	}
    private function load_required_files() {
   	 //Add Required Files to Load
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/create-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/delete-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/get-users.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/login.php';
	 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'users/update-users.php';
    }
	
}


$Rimplenet_Base_Users_Api = new Rimplenet_Base_Users_Api();