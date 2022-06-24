<?php

class Rimplenet_Base_API_KEYS_Api
{

	private $plugin_name;

	private $version;

	public function __construct()
	{
		$this->plugin_name = $plugin_name ?? 'RimplenetIn';
		$this->version = $version ?? 'v1';
		$this->load_required_files();
	}
	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/create-api-keys.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/get-api-keys.php';
	}
}


$Rimplenet_Base_API_KEYS_Api = new Rimplenet_Base_API_KEYS_Api();
