<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bunnyviolablue.com
 * @since      1.0.0
 *
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/includes
 * @author     Tech Celebrity <techcelebrity@bunnyviolablue.com>
 */
class Rimplenet_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bnvb-mlm',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
