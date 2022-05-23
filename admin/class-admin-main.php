<?php
//This file is included at includes/rimplenet.php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bunnyviolablue.com
 * @since      1.0.0
 *
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/admin
 * @author     Tech Celebrity <techcelebrity@bunnyviolablue.com>
 */
class Rimplenet_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	
		/**
		 * The class responsible for Setting Admin Menu
		 * core plugin.
		 */
		
		//Include class-file to display Rimplenet Sidebar menu on WP Admin 
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/class-admin-sidebar-menu-settings.php';
		
		//Include class-file to displays Wallet Settings as Metabox 
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/class-admin-wallets.php';
		
		//Include class-file to displays Matrix Settings as Metabox 
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/class-admin-matrix.php';
        
        //tgmpa_register Shows message for installing required plugins
        add_action( 'tgmpa_register', array( $this,  'required_plugins' ));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rimplenet_Mlm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rimplenet_Mlm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rimplenet-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rimplenet_Mlm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rimplenet_Mlm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rimplenet-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	 
    private function required_plugins() {
 
        $plugins = array(
           array(
              'name'      => 'WooCommerce',
              'slug'      => 'woocommerce',
              'required'  => true, // this plugin is required
            )
         );

        
        $config = array(
        'id'           => 'rimplenet-plugin-actiavtor', // your unique TGMPA ID
        'default_path' => get_stylesheet_directory() . '/lib/plugins/', // default absolute path
        'menu'         => 'rimplenet-install-required-plugins', // menu slug
        'has_notices'  => true, // Show admin notices
        'dismissable'  => true, // the notices are dismissable
        'dismiss_msg'  => 'The following plugins are needed to enhance Rimplenet e.g Woocommerce Payments', // this message will be output at top of nag
        'is_automatic' => true, // automatically activate plugins after installation
        'message'      => 'Install & Activate the following Plugins', // message to output right before the plugins table
         );
         
        tgmpa( $plugins, $config );
     
   }

}
