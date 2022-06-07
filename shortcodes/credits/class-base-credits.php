<?php

class Rimplenet_Shortcodes_Create_Credits
{


	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'credits/create-credits.php';
	}
}

$Rimplenet_Shortcodes_Create_Credits = new Rimplenet_Shortcodes_Create_Credits();
