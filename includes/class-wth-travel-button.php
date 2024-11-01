<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WTH_Travel_Button_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct($arg) {

		$this->plugin_info = $arg;

		$this->plugin_name     		= $arg['plugin_name'];
		$this->plugin_menu     		= $arg['plugin_menu'];
		$this->plugin_home_url 		= $arg['plugin_home_url'];
		$this->plugin_version  		= $arg['plugin_version'];
		$this->plugin_file     		= $arg['plugin_file'];
		$this->plugin_slug     		= $arg['plugin_slug'];
		$this->plugin_dir      		= $arg['plugin_dir'];
		$this->plugin_url      		= $arg['plugin_url'];
		$this->plugin_pref     		= $arg['plugin_pref'];

		$this->author_url      		= $arg['author_url'];
		$this->button_url      		= $arg['button_url'];

		$this->button_load_url      = $arg['button_load_url'];
		$this->button_config_url	= $arg['button_config_url'];
		$this->style_config_url		= $arg['style_config_url'];
		$this->atk_url         		= $arg['atk_url'];

		$this->shortcode       		= $arg['shortcode'];
		$this->config_origin   		= $arg['config_origin'];

		$this->doc_url   			= $arg['doc_url'];
		$this->support_url   		= $arg['support_url'];
		$this->tutorial_url  	 	= $arg['tutorial_url'];
		$this->account_url  	    = $arg['account_url'];

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WTH_Travel_Button_Loader. Orchestrates the hooks of the plugin.
	 * - WTH_Travel_Button_i18n. Defines internationalization functionality.
	 * - WTH_Travel_Button_Admin. Defines all hooks for the admin area.
	 * - WTH_Travel_Button_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wth-travel-button-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wth-travel-button-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wth-travel-button-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wth-travel-button-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wth-travel-button-renderer.php';

		$this->loader = new WTH_Travel_Button_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WTH_Travel_Button_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WTH_Travel_Button_i18n( $this->plugin_info );

		$this->loader->add_action( 'init', $plugin_i18n, 'load_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WTH_Travel_Button_Admin( $this->plugin_info );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'plugin_action_links', $this, 'plugin_action_links' , 10, 2 );
		$this->loader->add_filter( 'wth_travelbutton_include_file', $plugin_admin, 'wth_travelbutton_allow_include_file');

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );

		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'post_proccessor' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WTH_Travel_Button_Public( $this->plugin_info );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}


	public function plugin_action_links( $links, $plugin_file ) {

		if ( false === strpos( $plugin_file, $this->plugin_slug ) ) {
			return $links;
		}

		$settings_link = '<a href="admin.php?page=' . $this->plugin_slug . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WTH_Travel_Button_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Oppps, something went wrong!!', 'the-travel-button' ), $this->plugin_version );
	}

	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Oppps, something went wrong!!', 'the-travel-button' ), $this->plugin_version );
	}
}
