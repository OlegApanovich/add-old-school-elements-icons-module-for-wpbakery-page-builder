<?php
/**
 * Library of helper functions.
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbmod_validate_dependency_plugin' ) ) :
	/**
	 * Verify if a plugin is active, if not than deactivate the actual our plugin and show an error.
	 *
	 * @since  1.0
	 *
	 * @param string $my_plugin_name The plugin name trying to activate. The name of this plugin.
	 * @param string $dependency_plugin_name The dependency plugin name.
	 * @param string $path_to_plugin Path of the plugin
	 * to verify with the format 'dependency_plugin/dependency_plugin.php'.
	 * @param string $version_to_check Optional, verify certain version of the dependent plugin.
	 *
	 * @return bool
	 */
	function wpbmod_validate_dependency_plugin(
		$my_plugin_name,
		$dependency_plugin_name,
		$path_to_plugin,
		$version_to_check = null
	) {
		$success          = true;
		$template_payload = [];
		// Needed to the function "deactivate_plugins" works.
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( ! is_plugin_active( $path_to_plugin ) ) {
			// Show an error alert on the admin area.
			$template_payload = [
				'my_plugin_name'         => $my_plugin_name,
				'dependency_plugin_name' => $dependency_plugin_name,
				'version_to_check'       => $version_to_check,
			];
			$success          = false;
		} else {
			// Get the plugin dependency info.
			$version =
				wpbmod_get_plugin_version( WP_PLUGIN_DIR . '/' . $path_to_plugin );

			// Compare version.
			$is_required_version = ! version_compare(
				$version,
				$version_to_check,
				'>='
			);

			if ( $is_required_version ) {
				$template_payload = [
					'my_plugin_name'         => $my_plugin_name,
					'dependency_plugin_name' => $dependency_plugin_name,
					'version_to_check'       => $version_to_check,
				];
				$success          = false;
			}
		}

		if ( ! $success ) {
			add_action(
				'admin_notices',
				function () use ( $template_payload ) {
					wpbmod_include_template( 'required-plugin-notification.php', $template_payload );
				}
			);
		}

		return $success;
	}
endif;

if ( ! function_exists( 'wpbmod_get_plugin_version' ) ) :
	/**
	 * Get the plugin version, parsing main plugin file.
	 *
	 * @param string $plugin_file_path
	 *
	 * @return bool|string
	 */
	function wpbmod_get_plugin_version( $plugin_file_path ) {
        // phpcs:ignore:WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$plugin_data = file_get_contents( $plugin_file_path );
		if ( preg_match( '/^[ \t\/*#@]*[Vv]ersion\s*:\s*([^\r\n]+)/m', $plugin_data, $matches ) ) {
			return trim( $matches[1] );
		}
		return false;
	}
endif;

if ( ! function_exists( 'wpbmod_get_template' ) ) :
	/**
	 * Include template from templates dir.
	 *
	 * @param string $template
	 * @param array  $variables - passed variables to the template.
	 *
	 * @param bool   $once
	 *
	 * @return mixed
	 * @since 1.0
	 */
	function wpbmod_include_template( $template, $variables = [], $once = false ) {
        // phpcs:ignore:WordPress.PHP.DontExtract.extract_extract
		is_array( $variables ) && extract( $variables );
		if ( $once ) {
			return require_once wpbmod_template( $template );
		} else {
			return require wpbmod_template( $template );
		}
	}
endif;

if ( ! function_exists( 'wpbmod_get_template' ) ) :
	/**
	 * Output template from templates dir.
	 *
	 * @param string $template
	 * @param array  $variables - passed variables to the template.
	 *
	 * @param bool   $once
	 *
	 * @return string
	 * @since 1.0
	 */
	function wpbmod_get_template( $template, $variables = [], $once = false ) {
		ob_start();
		$output = wpbmod_include_template( $template, $variables, $once );

		if ( 1 === $output ) {
			$output = ob_get_contents();
		}

		ob_end_clean();

		return $output;
	}
endif;

if ( ! function_exists( 'wpbmod_template' ) ) :
	/**
	 * Shorthand for getting to plugin templates.
	 *
	 * @param string $file
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	function wpbmod_template( $file ) {
		return WPBMOD_TEMPLATES_DIR . '/' . $file;
	}
endif;
