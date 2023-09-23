<?php

class Rimplenet_Admin_Base_Api_Keys
{


	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue_docs'));

		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/class-tab-manager.php';
	}

	public function enqueue_docs()
	{
		// wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom_script.js', array( 'jquery' ) );

		wp_enqueue_style('rimplenet-api-keys', plugin_dir_url(__FILE__) . 'assets/css/style.css');
		wp_enqueue_script('rimplenet-api-keys', plugin_dir_url(__FILE__) . 'assets/js/api-keys.js', ['jquery'], false, true);
		// wp_localize_script(
		// 	'rimplenet-api-keys',
		// 	'rimplenet_api_keys_ajax_object',
		// 	array(
		// 		'ajaxurl' => plugin_dir_url(dirname(__FILE__)),
		// 		'data_var_1' => 'value 1',
		// 		'data_var_2' => 'value 2',
		// 	)
		// );
	}
}

$Rimplenet_Admin_Base_Api_Keys = new Rimplenet_Admin_Base_Api_Keys();
