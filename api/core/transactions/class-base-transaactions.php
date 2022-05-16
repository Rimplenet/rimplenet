<?php

class Rimplenet_Base_Wallets_Api {

	private $plugin_name;

	private $version;

	public function __construct() {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_required_files();
	}
  private function load_required_files() {
   //Add Required Files to Load
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transactions/get-transactions.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transactions/create-transactions.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transactions/delete-transactions.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transactions/update-transactions.php';
  }
	
}


$Rimplenet_Base_Wallets_Api = new Rimplenet_Base_Wallets_Api();