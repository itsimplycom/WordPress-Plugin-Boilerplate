<?php

/**
 * The options functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The options functionality of the plugin.
 *
 * For full documentation, please visit: http://docs.reduxframework.com/
 * For a more extensive sample-config file, you may look at:
 * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
if (!class_exists('Redux')) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "plugin_name_mode_options";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    'opt_name' => 'plugin_name_mode_options',
    'use_cdn' => true,
    'dev_mode' => false,
    'display_name' => 'WordPress Plugin Boilerplate',
    'display_version' => ITS_VERSION,
    'page_title' => 'WordPress Plugin Boilerplate',
    'update_notice' => true,
    'intro_text' => '',
    'footer_text' => '&copy; ' . date('Y') . '',
    'admin_bar' => false,
    'menu_title' => 'WordPress Plugin Boilerplate',
    'allow_sub_menu' => true,
    'page_parent_post_type' => 'your_post_type',
    'customizer' => false,
    'default_mark' => '*',
    'hints' => array(
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'light',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'duration' => '500',
                'event' => 'mouseleave unfocus',
            ),
        ),
    ),
    'output' => true,
    'output_tag' => true,
    'settings_api' => true,
    'cdn_check_time' => '1440',
    'compiler' => true,
    'page_permissions' => 'manage_options',
    'save_defaults' => true,
    'show_import_export' => true,
    'database' => 'options',
    'transient_time' => '3600',
    'network_sites' => true,
);

Redux::setArgs($opt_name, $args);

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

$tabs = array(
    array(
        'id' => 'help-tab',
        'title' => __('Information', PLUGIN_NAME_TEXT_DOMAIN),
        'content' => __('<p>Need support?.</p>', PLUGIN_NAME_TEXT_DOMAIN)
    ),
);
Redux::setHelpTab($opt_name, $tabs);

// Set the help sidebar
// $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'its-youtube-gallery' );
// Redux::setHelpSidebar( $opt_name, $content );


/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */


Redux::setSection($opt_name, array(
    'title' => __('WordPress Plugin Boilerplate', PLUGIN_NAME_TEXT_DOMAIN),
    'id' => 'general',
    'desc' => __('Need support?', PLUGIN_NAME_TEXT_DOMAIN),
    'icon' => 'el el-home',
));


/*
 * <--- END SECTIONS
 */
