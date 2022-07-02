<?php

class Rimplenet_Base_MLM_Matrix_Api
{

	private $plugin_name;

	private $version;

	public function __construct()
	{
		$this->load_required_files();
	}
	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'mlm-matrix/create-mlm-matrix.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'mlm-matrix/update-mlm-matrix.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'mlm-matrix/get-mlm-matrix.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'mlm-matrix/delete-mlm-matrix.php';
	}
}


$Rimplenet_Base_MLM_Matrix_Api = new Rimplenet_Base_MLM_Matrix_Api();
