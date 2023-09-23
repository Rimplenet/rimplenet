<?php

class Rimplenet_Base_API_KEY
{


	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/base-key.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/create-key.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/get-keys.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/allowed-ip-domains.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/delete-api-keys.php';
	}
}


$Rimplenet_Base_API_KEY = new Rimplenet_Base_API_KEY();
