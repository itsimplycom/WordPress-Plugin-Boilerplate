<?php

/**
 * Utility functions
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */


/**
 * plugin_name_get_option
 *
 * @param   [type]                       $option [description]
 *
 * @return  [type]                               [description]
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_get_option( $option ) {

	global $plugin_name_mode_options;


	if ( ! is_array( $plugin_name_mode_options ) ) {
		return false;
	}

	if ( ! array_key_exists( $option, $plugin_name_mode_options ) ) {
		return false;
	}

	return $plugin_name_mode_options[ $option ];
}

/**
 * plugin_name_get_option_echo
 *
 * @param   [type]                       $option [description]
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_get_option_echo( $option ) {
	echo plugin_name_get_option($option);
}

/**
 * plugin_name_get_template_part
 *
 * Get template part (for templates like the shop-loop).
 *
 * @param mixed $slug Template slug.
 * @param string $name Template name (default: '').
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_get_template_part( $slug, $name = '' ) {
	$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name, PLUGIN_NAME_VERSION ) ) );
	$template  = (string) wp_cache_get( $cache_key, PLUGIN_NAME_CACHE_GROUP );

	if ( ! $template ) {
		if ( $name ) {
			$template = false ? '' : locate_template(
				array(
					"{$slug}-{$name}.php",
					"plugin-name/" . "{$slug}-{$name}.php",
				)
			);

			if ( ! $template ) {
				$fallback = untrailingslashit( plugin_dir_path( __FILE__ ) ) . "/templates/{$slug}-{$name}.php";
				$template = file_exists( $fallback ) ? $fallback : '';
			}
		}

		if ( ! $template ) {
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/its_woo_brands_carousel/slug.php.
			$template = false ? '' : locate_template(
				array(
					"{$slug}.php",
					"plugin-name/" . "{$slug}.php",
				)
			);
		}

		wp_cache_set( $cache_key, $template, PLUGIN_NAME_CACHE_GROUP );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'plugin_name_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * plugin_name_get_template
 *
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	$cache_key = sanitize_key( implode( '-', array(
		'template',
		$template_name,
		$template_path,
		$default_path,
		PLUGIN_NAME_VERSION
	) ) );
	$template  = (string) wp_cache_get( $cache_key, PLUGIN_NAME_CACHE_GROUP );

	if ( ! $template ) {
		$template = plugin_name_locate_template( $template_name, $template_path, $default_path );
		wp_cache_set( $cache_key, $template, PLUGIN_NAME_CACHE_GROUP );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'plugin_name_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', PLUGIN_NAME_TEXT_DOMAIN ), '<code>' . $template . '</code>' ), '2.1' );

			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling plugin_name_get_template.', PLUGIN_NAME_TEXT_DOMAIN ),
				'3.6.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'plugin_name_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'plugin_name_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * plugin_name_get_template_html
 *
 * Like plugin_name_get_template, but returns the HTML instead of outputting.
 *
 * @param string $template_name Template name.
 * @param array $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	plugin_name_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}

/**
 * plugin_name_locate_template
 *
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @since   1.0.0
 * @version 1.0.0
 */
function plugin_name_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = "plugin-name";
	}

	if ( ! $default_path ) {
		$default_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'plugin_name_locate_template', $template, $template_name, $template_path );
}
?>