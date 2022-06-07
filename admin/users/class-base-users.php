<?php

class Rimplenet_Admin_Base_Users
{


	public function __construct()
	{
        add_action('admin_enqueue_scripts', array($this, 'enqueue_docs'));

		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'users/class-tab-manager.php';
	}

	public function enqueue_docs()
	{
		// wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom_script.js', array( 'jquery' ) );

		wp_enqueue_style( 'rimplenet-users', plugin_dir_url( __FILE__ ) . 'assets/css/style.css');
		wp_enqueue_script( 'rimplenet-users', plugin_dir_url( __FILE__ ) . 'assets/js/users.js', [], false, true);

	}
}

$Rimplenet_Admin_Base_Users = new Rimplenet_Admin_Base_Users();
