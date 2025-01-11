<?php
/**
 * Plugin Name:       Add Old School Elements Icons Module for WPBakery Page Builder
 * Plugin URI:        https://github.com/OlegApanovich/add-old-school-elements-icons-module-for-wpbakery-page-builder
 * Description:       Add a WPBakery Page Builder module that restore elements icons to their pre-version 8.0 style.
 * Author:            OlegApanovich
 * Author URI:        https://github.com/OlegApanovich
 * Text Domain:       add-wpbakery-page-builder-old-school-elements-icons-module
 * Domain Path:       /languages
 * Requires at least: 4.7
 * Requires PHP:      5.6
 * Version:           1.0
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Requires at least: 4.9
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Plugin Class.
 *
 * @since 1.0
 */
class Wpbmod_Old_School_Elements_Icons_Module {
	/**
	 * The single instance of the class.
	 *
	 * @since  1.0
	 * @var Wpbmod_Old_School_Elements_Icons_Module|null
	 */
	public static $instance = null;

	/**
	 * Main plugin instance.
	 *
	 * Ensures only one instance of plugin is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @return object Plugin main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Wpbmod_Old_School_Elements_Icons_Module Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();

		if ( ! $this->is_wpbakery() ) {
			return;
		}

		$this->init_hooks();
	}

	/**
	 * Check if wpbakery already activated.
	 *
	 * @since 1.0
	 */
	public function is_wpbakery() {
		return wpbmod_validate_dependency_plugin(
			'Wpbakery Old School Elements Icons Module',
			'WPBakery Page Builder',
			'js_composer/js_composer.php',
			'8.0'
		);
	}

	/**
	 * Include required plugin core files.
	 *
	 * @since 1.0
	 */
	public function includes() {
		require_once WPBMOD_INCLUDES_DIR . '/helpers.php';
	}

	/**
	 * Define plugin constants.
	 *
	 * @since 1.0
	 */
	private function define_constants() {
		define( 'WPBMOD_PLUGIN_VERSION', '1.0' );
		define( 'WPBMOD_PLUGIN_FILE', __FILE__ );
		define( 'WPBMOD_URI', plugins_url( '', WPBMOD_PLUGIN_FILE ) );
		define( 'WPBMOD_URI_ABSPATH', __DIR__ . '/' );
		define( 'WPBMOD_TEMPLATES_DIR', __DIR__ . '/templates' );
		define( 'WPBMOD_INCLUDES_DIR', __DIR__ . '/includes' );
		define( 'WPBMOD_MODULES_DIR', __DIR__ . '/modules' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0
	 */
	private function init_hooks() {
		add_action( 'init', [ $this, 'init' ], 0 );
		add_filter( 'vc_third_party_modules_list', [ $this, 'add_modules_to_wpbakery' ] );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {
		// Before init action.
		do_action( 'before_wpbmod_old_school_elements_icons_mod' );
		// Set up localisation.
		$this->load_plugin_textdomain();
		// After init action.
		do_action( 'after_wpbmod_old_school_elements_icons_mod' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones
	 * if the same translation is present.
	 *
	 * @since 1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'add-wpbakery-page-builder-old-school-elements-icons-module',
			false,
			WPBMOD_URI_ABSPATH . '/languages'
		);
	}

	/**
	 * Add our modules to WPBakery Page Builder third party modules list.
	 *
	 * @since 1.0
	 */
	public function add_modules_to_wpbakery( $module_list ) {
		return array_merge( $module_list, $this->get_module_list() );
	}

	/**
	 * Get list of our modules that we integrate to WPBakery Page Builder modules system.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_module_list() {
		return apply_filters(
			'wpbmod_modules_list',
			[
				'wpbmod-old-school-elements-icons' => [
					'name'           => esc_html__( 'Old School Elements Icons', 'js_composer' ),
					'main_file_path' => WPBMOD_MODULES_DIR . '/old-school-elements-icons/module.php',
					'module_class'   => 'Wpbmod_Old_School_Elements_Icons',
					'is_active'      => true,
				],
			]
		);
	}
}

Wpbmod_Old_School_Elements_Icons_Module::instance();
