<?php

class Rimplenet_Admin_Base_Credits
{


	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'credits/class-tab-manager.php';
	}
}

$Rimplenet_Admin_Base_Credits = new Rimplenet_Admin_Base_Credits();
