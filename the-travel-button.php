<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wetravelhub.com
 * @since             1.0.0
 * @package           WTH_Travel_Button
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>

 * @wordpress-plugin
 * Plugin Name:       The Travel Button®
 * Plugin URI:        https://wordpress.org/plugins/the-travel-button/
 * Description:       Monetize your travel content with your very own travel search engine. Help your audience plan their trips with customizable travel buttons.
 * Version:           1.0.8
 * Author:            We Travel Hub
 * Author URI:        https://www.wetravelhub.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-travel-button
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WTH_TRAVEL_BUTTON_VERSION', '1.0.8' );

/**
 * Currently plugin info.
 */
define('WTH_TRAVEL_BUTTON_PLUGIN_INFO', json_encode(array(
		'plugin_name'     => 'The Travel Button®',
		'plugin_menu'     => 'The Travel Button',
		'plugin_home_url' => 'https://wordpress.org/plugins/the-travel-button',
		'plugin_version'  =>  WTH_TRAVEL_BUTTON_VERSION,
		'plugin_file'     => basename( __FILE__ ),
		'plugin_slug'     => dirname( plugin_basename( __FILE__ ) ),
		'plugin_dir'      => plugin_dir_path( __FILE__ ),
		'plugin_url'      => plugin_dir_url( __FILE__ ),
		'plugin_pref'     => 'wth_travel_button',
		'author_url'      => 'https://www.wetravelhub.com',
		'button_url'      => 'https://www.wetravelhub.com',
		'button_load_url'   => 'https://button.wetravelhub.com/assets/js/hth-load.js',
		'button_config_url' => 'https://www.wetravelhub.com/plugin/makeit/locations?ver=' . WTH_TRAVEL_BUTTON_VERSION,
		'style_config_url' => 'https://www.wetravelhub.com/plugin/makeit/styles?ver=' . WTH_TRAVEL_BUTTON_VERSION,
		'config_origin'    => 'https://www.wetravelhub.com',
		'search_engine_url' => 'https://explore.wetravelhub.com',
		'doc_url'          => 'https://help.wetravelhub.com%s/',
		'tutorial_url'     => 'https://help.wetravelhub.com%s/general/tutorials/doc/wordpress/',
		'support_url'      => 'https://www.wetravelhub.com/contact',
		'account_url'    => 'https://my.wetravelhub.com',
		'atk_url' 		   => 'https://front.wetravelhub.com/api/app/websitecodes',
		'shortcode'        => 'WTH-TravelButton'
	)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wth-travel-button-activator.php
 */
function activate_wth_travel_button() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wth-travel-button-activator.php';
	WTH_Travel_Button_Activator::activate(json_decode(WTH_TRAVEL_BUTTON_PLUGIN_INFO, true));
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wth-travel-button-deactivator.php
 */
function deactivate_wth_travel_button() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wth-travel-button-deactivator.php';
	WTH_Travel_Button_Deactivator::deactivate(json_decode(WTH_TRAVEL_BUTTON_PLUGIN_INFO, true));
}

/**
 * The code that runs during plugin uninstallation.
 * This action is documented in includes/class-wth-travel-button-uninstall.php
 */
function uninstall_wth_travel_button() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wth-travel-button-uninstall.php';
	WTH_Travel_Button_Uninstaller::uninstall(json_decode(WTH_TRAVEL_BUTTON_PLUGIN_INFO, true));
}

register_activation_hook( __FILE__, 'activate_wth_travel_button' );
register_deactivation_hook( __FILE__, 'deactivate_wth_travel_button' );
register_uninstall_hook( __FILE__, 'uninstall_wth_travel_button' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wth-travel-button.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wth_travel_button() {
	$plugin = new WTH_Travel_Button(json_decode(WTH_TRAVEL_BUTTON_PLUGIN_INFO, true));
	$plugin->run();
}
run_wth_travel_button();
