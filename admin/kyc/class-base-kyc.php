<?php

class Rimplenet_Admin_Get_Kyc_Users
{


	public function __construct()
	{
        add_action('admin_enqueue_scripts', array($this, 'load_assets'));

		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'kyc/load_class.php';
	}

	public function load_assets()
	{
		// wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom_script.js', array( 'jquery' ) );

		wp_enqueue_style( 'rimplenet-users', plugin_dir_url( __FILE__ ) . 'assets/css/lightbox.css');
		wp_enqueue_script( 'rimplenet-users', plugin_dir_url( __FILE__ ) . 'assets/js/lightbox.js', [], false, true);
		wp_enqueue_script( 'rimplenet-users', plugin_dir_url( __FILE__ ) . 'assets/js/tailwind.js', [], false, true);

	}

}

$Rimplenet_Admin_Get_Kyc_Users = new Rimplenet_Admin_Get_Kyc_Users();
