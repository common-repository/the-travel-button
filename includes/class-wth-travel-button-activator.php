<?php

/**
 * Fired during plugin activation
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button_Activator {

	/**
	 * Activate plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate($arg) {

		global $wpdb;

        // see https://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table

		// load WP db update functions
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$table_styles = $wpdb->prefix . $arg['plugin_pref']  . '_styles';
		$table_buttons = $wpdb->prefix . $arg['plugin_pref']  . '_buttons';
		$table_web_codes = $wpdb->prefix . $arg['plugin_pref']  . '_website_codes';

		// the button styles table
		$sql = "CREATE TABLE " . $table_styles . " (
			id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(200) NOT NULL,
			`desc` LONGTEXT,
			type VARCHAR(200),
			config LONGTEXT,
			css LONGTEXT
		) DEFAULT CHARSET=utf8;";
		dbDelta( $sql );

		// the buttons table
		$sql = "CREATE TABLE " . $table_buttons . " (
			id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(200) NOT NULL,
			`desc` LONGTEXT,
			atk VARCHAR(200),
			style INTEGER NOT NULL,
			config LONGTEXT
		) DEFAULT CHARSET=utf8;";
		dbDelta( $sql );

		// the websites codes
		$sql = "CREATE TABLE " . $table_web_codes . " (
			id VARCHAR(100) NOT NULL PRIMARY KEY,
			website LONGTEXT
		) DEFAULT CHARSET=utf8;";
		dbDelta( $sql );

		// Add foreign key to avoid style deletion whether assigned to buttons
		$wpdb->query("ALTER TABLE " . $table_buttons . " ADD FOREIGN KEY (style) REFERENCES " . $table_styles . " (id);");
	}

}
