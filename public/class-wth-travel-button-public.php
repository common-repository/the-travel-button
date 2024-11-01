<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/public
 */
class WTH_Travel_Button_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wth_travel_button       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $arg) {

		$this->plugin_info = $arg;

		$this->plugin_version  		= $arg['plugin_version'];
		$this->plugin_slug     		= $arg['plugin_slug'];
		$this->plugin_pref     		= $arg['plugin_pref'];
		$this->shortcode       		= $arg['shortcode'];

		add_shortcode( $this->shortcode, array( $this, 'shortcode' ) );

		global $wpdb;

		$this->buttonsTable = $wpdb->prefix . $this->plugin_pref . '_buttons';
		$this->stylesTable = $wpdb->prefix . $this->plugin_pref . '_styles';

		$this->renderer = new WTH_Travel_Button_Renderer($this->plugin_info);
	}

	function shortcode( $atts, $content = "", $shortcode_tag = null ) {
		ob_start();
		require plugin_dir_path( __FILE__ ) . 'shortcode.php';
		$shortcode = ob_get_contents();
		ob_end_clean();
		return $shortcode;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug . '-css-public', plugin_dir_url( __FILE__ ) . 'css/wth-travel-button-public.css', array(), $this->plugin_version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-js-button-load-public', $this->renderer->buttonLoadUrl(), array( 'jquery' ), $this->plugin_version, false );
	}

}
