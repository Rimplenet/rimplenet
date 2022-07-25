<?php

class Rimplenet_Base_API_KEYS_Api
{
	public function __construct()
	{
		$this->load_required_files();
	}
	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/permission.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/create-api-keys.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/get-api-keys.php';
	}
}


$Rimplenet_Base_API_KEYS_Api = new Rimplenet_Base_API_KEYS_Api();
