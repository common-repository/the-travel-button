<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button_i18n {


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wth_travel_button       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($arg) {
		$this->plugin_slug     		= $arg['plugin_slug'];
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			$this->plugin_slug,
			false,
			$this->plugin_slug  . '/languages'
		);

	}



}
