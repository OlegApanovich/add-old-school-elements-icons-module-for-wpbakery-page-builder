<?php
/**
 * Module Name: Old School Elements Icons.
 * Description: Restore the icons for WPBakery Page Builder editor elements to the style used before version 8.0.
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Module entry point.
 *
 * @since 1.0
 */
class Wpbmod_Old_School_Elements_Icons {

	/**
	 * Module name.
	 *
	 * @var string
	 */
	public $module_name = 'old-school-elements-icons';

	/**
	 * Init module implementation.
	 *
	 * @since 1.0
	 */
	public function init() {
		add_action( 'wpb_after_register_frontend_editor_css', [ $this, 'register_module_css' ] );
		add_action( 'wpb_after_register_backend_editor_css', [ $this, 'register_module_css' ] );

		add_filter( 'wpb_enqueue_frontend_editor_css', [ $this, 'enqueue_module_css' ] );
		add_filter( 'wpb_enqueue_backend_editor_css', [ $this, 'enqueue_module_css' ] );

		add_filter( 'vc_add_element_box_buttons', [ $this, 'fix_elements_buttons_output' ] );
	}

	/**
	 * Register module css.
	 *
	 * @since 1.0
	 */
	public function register_module_css() {
		wp_register_style(
			'wpb-module-old-school-elements-icons',
			plugins_url( 'modules/' . $this->module_name . '/assets/css/module.css', WPBMOD_PLUGIN_FILE ),
			[],
			WPBMOD_PLUGIN_VERSION
		);
	}

	/**
	 * Enqueue module css.
	 *
	 * @since 1.0
	 */
	public function enqueue_module_css( $styles ) {
		$styles[] = 'wpb-module-old-school-elements-icons';
		return $styles;
	}

	/**
	 * Return elements button html output to plugin WPBakery Page Builder plugin version 7.9
	 *
	 * @since 1.0
	 */
	public function fix_elements_buttons_output( $output ) {
		$output = str_replace( 'wpb-layout-element-button', 'wpb-layout-element-button vc_col-xs-12 vc_col-sm-4 vc_col-md-3 vc_col-lg-2 ', $output );

		$output = str_replace( '><a id="', '><div class="vc_el-container"><a id="', $output );
		return str_replace( '</a></li><li', '</a></div></li><li', $output );
	}
}
