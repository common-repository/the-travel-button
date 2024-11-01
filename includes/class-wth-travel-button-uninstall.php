<?php
/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button_Uninstaller {

	/**
	 * Uninstall plugin
	 *
	 * Remove all plugin data (styles, buttons, website codes...)
	 *
	 * @since    1.0.0
	 */
	public static function uninstall($arg) {

		if ( !current_user_can('manage_options') ) {
			exit;
		}

		global $wpdb;

		// Drop plugin tables tables
		$table_buttons = $wpdb->prefix . $arg['plugin_pref']  . '_buttons';
		$wpdb->query("DROP TABLE " . $table_buttons);

		$table_styles = $wpdb->prefix . $arg['plugin_pref']  . '_styles';
		$wpdb->query("DROP TABLE " . $table_styles);

		$table_web_codes = $wpdb->prefix . $arg['plugin_pref']  . '_website_codes';
		$wpdb->query("DROP TABLE " . $table_web_codes);

		// Delete plugin preferences
		delete_option($arg['plugin_pref']);
		delete_option($arg['plugin_pref'] . '_starting');
		delete_option($arg['plugin_pref'] . '_apikey');
	}

}
