<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.koombea.com/
 * @since      0.1.0
 *
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Kili_Instagram
 * @subpackage Kili_Instagram/public
 * @author     Koombea, Rhonalf Martinez <rhonalf.martinez@koombea.com>
 */
class Kili_Instagram_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kili-instagram-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kili-instagram-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Fetch data from an url
	 *
	 * @param string $url The url
	 * @return string The data returned by the url
	 */
	private static function kili_fetch_data( $url ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
		$result = curl_exec( $ch );
		curl_close( $ch );
		return $result;
	}

	/**
	 * Read instagram posts using the instagram api
	 *
	 * @param integer $count How many posts to show. Overriden by customizer setting. Default: 20.
	 * @param string  $cache_directory Where to put cache file.
	 * @param string  $cache_time How much time keep the cache file. Default: '-1 hour'
	 * @return object JSON object with the posts data
	 */
	public static function get_instagram_feed( $count=20, $cache_directory = '', $cache_time = '-1 hour' ) {
		$theme_mods = get_theme_mods();
		$json_data = json_decode( '{"pagination": {}, "data": [{"id": "0", "user": {"id": "0", "full_name": "Kili Instagram Feed", "profile_picture": "https://via.placeholder.com/350x350", "username": "instagram"}, "images": {"thumbnail": {"width": 150, "height": 150, "url": "https://via.placeholder.com/150x150"}, "low_resolution": {"width": 320, "height": 320, "url": "https://via.placeholder.com/320x320"}, "standard_resolution": {"width": 640, "height": 640, "url": "https://via.placeholder.com/640x640"}}, "created_time": "1510092225", "caption": {"id": "0", "text": "Kili Instagram Placeholder", "created_time": "1510092225", "from": {"id": "0", "full_name": "Kili Instagram Feed", "profile_picture": "https://via.placeholder.com/350x350", "username": "instagram"}}, "user_has_liked": false, "likes": {"count": 0}, "tags": [], "filter": "Normal", "comments": {"count": 0}, "type": "image", "link": "https://github.com/koombea/kili-instagram", "location": null, "attribution": null, "users_in_photo": []}], "meta": {"code": 200}}' );
		if ( isset( $theme_mods['kili_instagram_token'] ) ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$count = isset( $theme_mods['kili_instagram_posts'] ) ? $theme_mods['kili_instagram_posts'] : $count;
			$access_token = $theme_mods['kili_instagram_token'];
			$splitted_token = explode( '.', $access_token );
			$user_id = $splitted_token[0];
			$url = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $access_token . '&count=' . $count;
			if ( strcasecmp( $cache_directory, '' ) == 0 ) {
				$cache_directory = wp_upload_dir()['basedir'] . '/cache/kili-instagram/';
			}
			$cache_file = $cache_directory.sha1( $url ).'.json';
			if ( file_exists( $cache_file ) && filemtime( $cache_file ) > strtotime( $cache_time ) ) {
				$json_data = json_decode( file_get_contents( $cache_file ) );
			}
			else {
				if ( ! $wp_filesystem->is_dir( $cache_directory ) ) {
					wp_mkdir_p( $cache_directory, FS_CHMOD_DIR );
				}
				self::kili_delete_files( $cache_directory );
				$json_response = self::kili_fetch_data( $url );
				if ( strcasecmp( $json_response[0], '{' ) === 0 ) {
					$json_data = json_decode( $json_response );
				}
				$json_encode = json_encode( $json_data );
				if ( ! $wp_filesystem->put_contents( $cache_file, $json_encode, FS_CHMOD_FILE ) ) {
					return;
				}
			}
		}
		return $json_data;
	}

	/**
	 * Delete all files inside a directory
	 *
	 * @param string $directory The directory where the files will be deleted
	 * @return void
	 */
	private static function kili_delete_files( $directory ) {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		if ( $wp_filesystem->is_dir( $directory ) ) {
			$files = glob( $directory . '*' );
			foreach ( $files as $file ) {
				if( is_file( $file ) ) {
					$wp_filesystem->delete( $file, true );
				}
			}
		}
	}

}
