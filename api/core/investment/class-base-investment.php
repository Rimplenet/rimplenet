<?php

class Rimplenet_Base_Investment_Api
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
		
	}
}


$Rimplenet_Base_Investment_Api = new Rimplenet_Base_Investment_Api();
