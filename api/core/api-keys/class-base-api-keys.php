<?php

class Rimplenet_Base_API_KEYS_Api
{
	public function __construct()
	{
		$this->load_required_files();
		add_action('rimplenet_api_request_started', array($this, 'validate_api_key'), 1, 3);
	}
	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/create-api-keys.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/get-api-keys.php';
	}
	public function validate_api_key($request, $allowed_roles, $action)
	{
		// if(empty($allowed_roles)) return;

		// $headers = getallheaders();
		// [$a, $b] = explode(' ', $headers['Authorization']);
		// if($a == 'Basic' || $a == 'Bearer'):
		// 	if($a == 'Basic'): 
		// 		// echo json_encode($b);
		// 	endif;
		// else:
		//     echo json_encode("No token");
		//     exit;
		// endif;

	}
}


$Rimplenet_Base_API_KEYS_Api = new Rimplenet_Base_API_KEYS_Api();
