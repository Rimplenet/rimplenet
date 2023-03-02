<?php

class Rimplenet_Base_Transfers_Api {

	private $plugin_name;

	private $version;

	public function __construct()
	{
		$this->plugin_name = $this->plugin_name ?? 'RimplenetIn';
		$this->version = $this->version ?? 'v1';
		$this->load_required_files();
	}
  private function load_required_files() {
   //Add Required Files to Load
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/create-transfers.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/get-transfers.php';
   require_once plugin_dir_path( dirname( __FILE__ ) ) . 'transfers/delete-transfers.php';
  }
	
}


new Rimplenet_Base_Transfers_Api();