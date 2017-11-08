<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.koombea.com/
 * @since             0.1.0
 * @package           Kili_Instagram
 *
 * @wordpress-plugin
 * Plugin Name:       Kili Instagram
 * Plugin URI:        https://github.com/koombea/kili-instagram
 * Description:       Generate instagram feed object. Use the function get_instagram_feed()
 * Version:           0.1.0
 * Author:            Koombea, Rhonalf Martinez
 * Author URI:        https://www.koombea.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kili-instagram
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kili-instagram-activator.php
 */
function activate_kili_instagram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kili-instagram-activator.php';
	Kili_Instagram_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kili-instagram-deactivator.php
 */
function deactivate_kili_instagram() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kili-instagram-deactivator.php';
	Kili_Instagram_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kili_instagram' );
register_deactivation_hook( __FILE__, 'deactivate_kili_instagram' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kili-instagram.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_kili_instagram() {

	$plugin = new Kili_Instagram();
	$plugin->run();

}
run_kili_instagram();


/**
 * Read instagram posts using the instagram api
 *
 * @param integer $count How many posts to show. Overriden by customizer setting. Default: 20.
 * @param string  $cache_directory Where to put cache file.
 * @param string  $cache_time How much time keep the cache file. Default: '-1 hour'
 * @return object JSON object with the posts data
 */
function get_instagram_feed( $count=20, $cache_directory = '', $cache_time = '-1 hour' ) {
	return Kili_Instagram::get_instagram_feed( $count, $cache_directory, $cache_time );
}
