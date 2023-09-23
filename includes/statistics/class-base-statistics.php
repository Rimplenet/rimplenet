<?php

class Rimplenet_Base_Statistics
{


	public function __construct()
	{
		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
        require_once plugin_dir_path(dirname(__FILE__)) . 'statistics/BaseStatistics.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'statistics/statistics.php';
	}
}


$Rimplenet_Base_Statistics = new Rimplenet_Base_Statistics();
