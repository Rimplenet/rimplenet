<?php

class Rimplenet_Admin_Base_Transfers
{


	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue_docs'));

		$this->load_required_files();
	}

	private function load_required_files()
	{
		//Add Required Files to Load
		require_once plugin_dir_path(dirname(__FILE__)) . 'transfers/class-tab-manager.php';
	}

	public function enqueue_docs()
	{
		// wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom_script.js', array( 'jquery' ) );

		wp_enqueue_style('rimplenet-transfers', plugin_dir_url(__FILE__) . 'assets/css/style.css');
		wp_enqueue_script('rimplenet-transfers', plugin_dir_url(__FILE__) . 'assets/js/transfers.js', ['jquery'], false, true);
		wp_localize_script(
			'rimplenet-transfers',
			'rimplenet_transfers_ajax_object',
			array(
				'ajaxurl' => plugin_dir_url(dirname(__FILE__)),
				'data_var_1' => 'value 1',
				'data_var_2' => 'value 2',
			)
		);
	}
}

$Rimplenet_Admin_Base_Transfers = new Rimplenet_Admin_Base_Transfers();
