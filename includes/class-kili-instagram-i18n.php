<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.koombea.com/
 * @since      0.1.0
 *
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/includes
 * @author     Koombea, Rhonalf Martinez <rhonalf.martinez@koombea.com>
 */
class Kili_Instagram_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kili-instagram',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
