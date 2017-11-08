<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.koombea.com/
 * @since      0.1.0
 *
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/admin
 * @author     Koombea, Rhonalf Martinez <rhonalf.martinez@koombea.com>
 */
class Kili_Instagram_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kili_Instagram_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kili_Instagram_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kili-instagram-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kili_Instagram_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kili_Instagram_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kili-instagram-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add options to theme customizer
	 *
	 * @param object $wp_customize WordPress customizer object
	 * @return void
	 */
	public function kili_customizer( $wp_customize ) {
		$wp_customize->add_panel( 'kili_instagram_theme_panel', array(
			'title' => esc_html__('Kili Instagram options','kili-instagram'),
			'description' => esc_html__('Kili Instagram options','kili-instagram'),
			'priority' => 10
		));

		//Access token
		$wp_customize->add_section( 'kili_instagram_settings', array(
			'title' => esc_html__( 'Access token', 'kili-instagram' ),
			'panel' => 'kili_instagram_theme_panel',
			'priority' => 10
		));
		$wp_customize->add_setting( 'kili_instagram_token', array( 'sanitize_callback' => 'wp_filter_nohtml_kses' ) );
		$wp_customize->add_control( 'kili_instagram_access_token', array(
			'label' => esc_html__( 'Access token string', 'kili-instagram' ),
			'section' => 'kili_instagram_settings',
			'settings' => 'kili_instagram_token',
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'kili_instagram_posts', array( 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( 'kili_instagram_access_posts', array(
			'label' => esc_html__( 'Number of posts to show', 'kili-instagram' ),
			'section' => 'kili_instagram_settings',
			'settings' => 'kili_instagram_posts',
			'type' => 'number',
		) );
	}

	/**
	 * Copy the block json definition to the active theme.
	 * The theme must be a child of kiliframework in order to work
	 *
	 * @return void
	 */
	public function kili_copy_json_to_theme() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$json_filename = 'instagram-feed.json';
		$theme_data_dir = get_stylesheet_directory() . '/data/blocks/pages';
		$theme_json_file = $theme_data_dir . '/' . $json_filename;
		if ( ! $wp_filesystem->is_dir( $theme_data_dir ) ) {
			wp_mkdir_p( $theme_data_dir, FS_CHMOD_DIR );
		}
		if ( ! $wp_filesystem->is_file( $theme_json_file ) ) {
			$json_content = $wp_filesystem->get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/' . $json_filename );
			if ( ! $wp_filesystem->put_contents( $theme_json_file, $json_content, FS_CHMOD_FILE ) ) {
				return;
			}
		}
	}

}
