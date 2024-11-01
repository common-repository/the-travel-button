<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin
 */
class WTH_Travel_Button_Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wth_travel_button       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
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
		$this->account_url  	 	= $arg['account_url'];

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wth-travel-button-db-tools.php';

		global $wpdb;

		$this->db = new WTH_Travel_Button_DB();

		$this->renderer = new WTH_Travel_Button_Renderer($this->plugin_info);

		$this->buttonsTable = $wpdb->prefix . $this->plugin_pref . '_buttons';
		$this->stylesTable = $wpdb->prefix . $this->plugin_pref . '_styles';
		$this->websiteCodesTable = $wpdb->prefix . $this->plugin_pref . '_website_codes';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug . '-css-uikit', plugin_dir_url( __FILE__ ) . 'css/uikit.hwt.min.css', array(), $this->plugin_version, 'all' );

		wp_enqueue_style( $this->plugin_slug . '-css-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->plugin_version, 'all' );

		wp_enqueue_style( $this->plugin_slug . '-css-admin', plugin_dir_url( __FILE__ ) . 'css/wth-travel-button-admin.css', array(), $this->plugin_version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-js-uikit', plugin_dir_url( __FILE__ ) . 'js/uikit.min.js', array( 'jquery' ), $this->plugin_version, false );

		wp_enqueue_script( $this->plugin_slug . '-js-uikit-icons', plugin_dir_url( __FILE__ ) . 'js/uikit-icons.min.js', array( 'jquery' ), $this->plugin_version, false );

		wp_enqueue_script( $this->plugin_slug . '-js-select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), $this->plugin_version, false );

		wp_enqueue_script( $this->plugin_slug . '-js-button-load-admin', $this->renderer->buttonLoadUrl(), array( 'jquery' ), $this->plugin_version, false );
	}


	public function add_menu()
	{
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		// see https://wordpress.org/support/article/roles-and-capabilities/
		$this->loadPreferences();

		// only content editors (edit_posts capability) and admin can access to buttons and styles views
		if ( $this->userCan('view_buttons') ){
			$icon = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfNiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMDU1IDIwNTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDIwNTUgMjA1NTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkud3RoLWdyZWVue2ZpbGw6I2E3YWFhZDt9DQoJLnd0aC1yZWR7ZmlsbDojYTdhYWFkO30NCgkud3RoLXllbGxvd3tmaWxsOiNhN2FhYWQ7fQ0KCS53dGgtYmx1ZXtmaWxsOiNhN2FhYWQ7fQ0KPC9zdHlsZT4NCgk8bWV0YWRhdGE+DQogICAgQ29weXJpZ2h0IChjKSAyMDIxIFdlIFRyYXZlbCBIdWIsIFMuTC4sIEFMTCBSSUdIVFMgUkVTRVJWRUQNCiAgPC9tZXRhZGF0YT4NCjxnIGlkPSJJc290aXBvIj4NCgk8cGF0aCBjbGFzcz0id3RoLWdyZWVuIiBkPSJNNzcyLjYsNjEuMkM1MjYuNSw5OS45LDM4Mi41LDIxOC40LDM3OC43LDIxMS4zYy0yLjUtNC42LDc4LjgtNTguOSwxNzQuOC0xMDQuMQ0KCQljNDcuNy0yMi40LDEyMy45LTU0LjksMjEzLjQtNzYuMWMzMjMuOS03Ni45LDU4MSwxOS44LDYwMywyNy41YzgxLDI4LjQsMTg2LjMsNzcuNSwyODkuMSwxNjQuNA0KCQljMjY4LjEsMjI2LjgsMzE5LjMsNTQyLjUsMzMxLjEsNjM5LjFjMCwwLDU4LjYsMzMzLTc3LjYsNjM0LjljLTIuOSw2LjUtMS4xLDE0LjMsNC43LDE4LjVjMC4xLDAuMSwwLjIsMC4xLDAuMywwLjINCgkJYzgsNS44LDE4LjgsMTcuMywyMi43LDIxLjVjMS4xLDEuMiwyLDIuNiwyLjcsNC4xYzIuMiw0LjksOSwxMSwwLjcsMjYuMWMtMC41LDAuOS0xMS40LDE3LjctMTMuNiwyMC4yDQoJCWMtMTQuMiwxNi4xLTE0MS4zLDE2OS0zNjguMSwyNjAuMmMtMC42LDAuMy0xLjMsMC41LTEuOSwwLjljLTMuNiwyLTI1LjgsOS4zLTQyLjMsMy4yYy0xMS4yLTQuMi0xNS0xNC41LTIzLjktMjguMw0KCQljLTAuMi0wLjMtMC4zLTAuNi0wLjUtMC44Yy02LTEwLjQtMTE4LjQtMjA5LjMtMTEyLjMtMzkzLjVjMCwwLDAtMzIuMSw1MC0yOC4xbDkwLjgsMi45YzQuOSwwLjIsOS42LTIuMSwxMi42LTYNCgkJYzMxLjItNDAuNSwxOTYtMjgwLjQsMTIyLjEtNzQzYzAsMC05NS4yLTU0MS45LTYwMS43LTU5Ny41Yy0wLjUtMC4xLTEtMC4xLTEuNS0wLjNDMTA0Myw1NS4yLDkzNy42LDM1LjIsNzcyLjYsNjEuMnoiLz4NCgk8cGF0aCBjbGFzcz0id3RoLXJlZCIgZD0iTTE0OTIuOCwxOTA5LjVjMCwwLTI3MC4xLDExNC4yLTQ2MC44LDI0LjRjLTAuMS0wLjEtMC4zLTAuMS0wLjQtMC4yYy02LTMuNy0xMjIuMS01OC4xLTI1LjItMjUzLjUNCgkJYzAuMS0wLjEsMC4xLTAuMiwwLjItMC4zYzIuOS02LjYsMTAxLjYtMjI2LjksMzEyLjctMzg4LjJjMC4yLTAuMiwwLjUtMC4zLDAuNy0wLjVjMi45LTEuNSwxNi4xLTE1LjIsNDIuOS0xLjYNCgkJYzAuMiwwLjEsMC40LDAuMiwwLjYsMC4ybDQwLjYsMjIuMmMwLDAsMjUuMiwxOCw0MS0xMC41YzE1LjUtMjIsMTE2LjItMjAxLjksMTE1LjEtNDE0LjNjMC0wLjIsMC0wLjUsMC0wLjcNCgkJYy0wLjQtNS4xLDQuNi0yOS45LTQ0LjUtNDAuNmMwLDAtMjczLjEtNDQuMi00NDQuNCwzMy4zYy0wLjcsMC4zLTEuMiwwLjktMS44LDEuM2MtMTAuNyw3LTE4LjQsNy42LTAuOSw0Ny4xbDExLjcsMjAuOQ0KCQljMC4xLDAuMSwwLjMsMC4zLDAuMywwLjRjMS4yLDAuOSw5LjcsMTMtNS41LDI2LjVjLTE2LDE0LjItMzM5LjEsMzg4LjYtMzQ2LjgsNzE5LjhjMCwwLjEsMCwzLjgsMCwzLjkNCgkJYzAuMiw3LjgtNi42LDI3My4xLDIxMS40LDM0MC44YzAuMSwwLDkuNCwyLjQsOS41LDIuNGM3LjQsMS43LDI5Ni41LDYyLjIsNTQwLjItMTE2LjNjMCwwLDYuMy00LjEsMTAuMi04LjhjMy43LTQuNC0xLTEwLTYuMy03LjkNCgkJQzE0OTMuMiwxOTA5LjQsMTQ5Mi45LDE5MDkuNSwxNDkyLjgsMTkwOS41eiIvPg0KCTxwYXRoIGNsYXNzPSJ3dGgteWVsbG93IiBkPSJNNDAzLjgsMTgxMC42YzAsMC0xNjEuMi0yNDUuNS0xMzAuMS02MTYuNmMwLDAsMy41LTIxNi42LDIwMy41LTUzOC45YzAsMCwzLjktOS42LDE2LjgtOC4yDQoJCWMwLDAsMzUuNyw1LjgsNDMuMyw0LjRjMCwwLDE2LjUsMi45LDEyLjYtMzNjLTMuOC0zNS45LTE5LjEtMTc5LjctNjcuOC0yODEuNWMwLDAtNi4zLTI1LjEtNjEuNy05LjNjMCwwLTE5Ny41LDcxLjEtMjc0LjMsMTM1LjUNCgkJYzAsMC0xMi4xLDkuNi0xMC4xLDE4LjJjMS4yLDQuOSw4LjEsMTAuOSwxNi40LDE0LjhjMCwwLDIyLjYsOCw5LjEsMzQuM2MwLDAtMTQyLDI2OC0xMDIsNjIyLjQNCgkJQzU5LjQsMTE1Mi44LDg5LjcsMTUyMi45LDQwMy44LDE4MTAuNnoiLz4NCgk8cGF0aCBjbGFzcz0id3RoLWJsdWUiIGQ9Ik04MDguMSwyMDExLjFjMCwwLTI0OS43LTE0Mi4xLTE0Ny42LTU3NC4xYzAsMCw2MS44LTM2MC44LDQ0Ni4zLTY5Mi41YzAsMCwxMi4xLTEyLjksMjkuOC0yDQoJCWMwLDAsNDkuNCwyMi42LDc2LjIsMjYuNWMwLDAsMTguNSw1LjEsMjguMS0xNS45YzguMy0xNy45LDY5LjktMjQ1LjEsMjAtNDc0LjFjLTMuNC0xMS0xMi45LTMyLjQtMjEuOC0zNg0KCQljLTEzLjctNS42LTI2LjUtOC4yLTU2LjUtNi40YzAsMC0yOTMuOSwxOC43LTQ2NS43LDEzMS4yYy0wLjksMC42LTguNiw4LjktOS41LDEyLjFjLTEuNSw1LjQsMC44LDE0LjgsMTkuOSwzNC44DQoJCWMxMS40LDExLjksMTYuMywyMi4xLDE4LjUsMjkuNmMwLDcuNS01LjksMTkuOC03LjgsMjIuNGMtODUuMSwxMTYuNi0xNjYuNSwyMzcuNS0yMjguOSwzNjcuOGMtNjQuMiwxMzQtMTQyLjMsMzAxLjQtMTQ4LjYsNTIxLjYNCgkJYy0yLjQsODMuNiw2LjUsMTQyLjQsOSwxNTguMmMyNS4yLDE1OCw5My43LDI3NC43LDE0MS4zLDM0Mi4xQzUxMC44LDE4NTYuMyw2MTcuMiwxOTg2LjUsODA4LjEsMjAxMS4xeiIvPg0KPC9nPg0KPC9zdmc+DQo=";
			add_menu_page( $this->plugin_name , $this->plugin_menu , 'edit_posts', $this->plugin_slug, array( $this, 'admin_page' ), $icon);
		}
	}

	public function userCan($action){

		if ( $this->currentUserSec['profile'] == 'super_admin' || $this->currentUserSec['profile'] == 'admin' ){
			return true;
		}

		if ( $this->preferences[$this->currentUserSec['profile'] . '_' . $action] == 'on'){
			return true;
		}

		return false;
	}

	public function loadPreferences(){

			$this->preferences = get_option( $this->plugin_pref );

			if (! $this->preferences){
				// set default preferences
				$defaultPreferences = array();
				$defaultPreferences['editor_view_buttons'] = 'on';
				$defaultPreferences['author_view_buttons'] = 'on';
				$defaultPreferences['contributor_view_buttons'] = 'on';
				$defaultPreferences['editor_create_buttons'] = 'on';
				$defaultPreferences['author_create_buttons'] = 'on';
				$defaultPreferences['editor_create_styles'] = 'on';
				$defaultPreferences['author_create_styles'] = 'on';
				// set default tracking code preferences
				$defaultPreferences['use_default_atk'] = 'on';
				// set prefetch, enabled by default
				$defaultPreferences['disable_prefetch'] = '';
				// set other preferences
				$defaultPreferences['per_page'] = 5;
				$this->preferences = get_option( $this->plugin_pref, $defaultPreferences);
			}

			// see https://wordpress.org/support/article/roles-and-capabilities/
			// super_admin -> manage_network
			// admin -> manage_options
			// editor -> publish_pages
			// author -> publish_posts
			// contributor -> edit_posts

			$this->currentUserSec = array();

			if (current_user_can( 'manage_network' )){
				$this->currentUserSec['profile'] = 'super_admin';
			} else if (current_user_can( 'manage_options' )){
                $this->currentUserSec['profile'] = 'admin';
			} else if (current_user_can( 'publish_pages' )){
                $this->currentUserSec['profile'] = 'editor';
			} else if (current_user_can( 'publish_posts' )){
                $this->currentUserSec['profile'] = 'author';
			} else if (current_user_can( 'edit_posts' )){
                $this->currentUserSec['profile'] = 'contributor';
            }

            return $this->preferences;
	}

	function wth_travelbutton_allow_include_file( $fileName ) {

		if ( !$fileName || empty($fileName) ){
			return null;
		}
		// as we include dinamically files we want to control allowed filenames to load
		switch ( $fileName )
		{
			case 'about_wth';
			case 'buttons';
			case 'new_button';
			case 'new_style';
		    case 'preferences';
		    case 'starting';
		    case 'styles';
				return $fileName;
			default;
				return null;
		}
	}


	public function admin_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/main.php';
	}

	public function post_proccessor() {

		$this->loadPreferences();

		if ( ! empty( $_POST )) {
			if (isset( $_POST[ $this->plugin_slug . '_button_nonce' ] ) && wp_verify_nonce( $_POST[ $this->plugin_slug . '_button_nonce' ], $this->plugin_slug . '_button_action' ) ){
				self:: save_button();
			} else if (isset( $_POST[ $this->plugin_slug . '_style_nonce' ] ) && wp_verify_nonce( $_POST[ $this->plugin_slug . '_style_nonce' ], $this->plugin_slug . '_style_action' ) ){
                 self:: save_style();
			} else if (isset( $_POST[ $this->plugin_slug . '_preferences_nonce' ] ) && wp_verify_nonce( $_POST[ $this->plugin_slug . '_preferences_nonce' ], $this->plugin_slug . '_preferences_action' ) ){
                 self:: save_preferences();
            }
       }
	}

	public function save_button() {
		if ($this->userCan('create_buttons') && isset( $_POST['submit'] ) ) {
				$info  = array();

				$info['id'] = sanitize_text_field($_POST['id']);
				$info['name'] = sanitize_text_field($_POST['name']);
				$info['atk'] = sanitize_text_field($_POST['atk']);
				$info['desc'] = sanitize_textarea_field($_POST['desc']);
				$info['style'] = absint(sanitize_text_field($_POST['style']));
				$info['config'] = sanitize_textarea_field($_POST['config']);

        		$form_id       = sanitize_text_field($_POST['form_id']);
        		$action = sanitize_text_field( $_POST['act'] );
        		$paged = isset( $_POST['paged']) ? '&paged=' . sanitize_text_field( $_POST['paged'] ):'';
        		$filter = isset( $_POST['s']) ? '&s=' . sanitize_text_field( $_POST['s'] ):'';
				$isNew = $this->db->upsertRecord( $this->buttonsTable, $info , 'id');
				wp_redirect('admin.php?page=' . $this->plugin_slug . '&tab=buttons&act=' . $action . '&id=' . $form_id . '&info=' . ( $isNew ? 'create' : 'update') . $paged . $filter);
				exit;
		}
	}

	public function save_style() {
		if ($this->userCan('create_styles') && isset( $_POST['submit'] ) ) {
				$info  = array();

				$info['id'] = sanitize_text_field($_POST['id']);
				$info['name'] = sanitize_text_field($_POST['name']);
				$info['desc'] = sanitize_textarea_field($_POST['desc']);
				$info['css'] = sanitize_text_field($_POST['css']);
				$info['config'] = sanitize_textarea_field($_POST['config']);

        		$form_id       = sanitize_text_field($_POST['form_id']);
        		$action = sanitize_text_field( $_POST['act'] );
        		$paged = isset( $_POST['paged']) ? '&paged=' . sanitize_text_field( $_POST['paged'] ):'';
        		$filter = isset( $_POST['s']) ? '&s=' . sanitize_text_field( $_POST['s'] ):'';
				$isNew = $this->db->upsertRecord( $this->stylesTable, $info , 'id');
				wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&tab=styles&act=' . $action . '&id=' . $form_id . '&info=' . ( $isNew ? 'create' : 'update') . $paged . $filter);
				exit;
		}
	}

	public function save_preferences() {
		// This is an administrative operation (only admin users can save preferences)
		if ($this->userCan('manage_options') && isset( $_POST['submit'] )) {
				if (isset( $_POST['param'] ) && is_array( $_POST['param'] ) ){
					// generic sanitized text parameter (all preferences are strings)
					$_pref  = array();
					$param = $_POST['param'];
					foreach ( $param as $key => $value ) {
						$_pref[$key] = sanitize_text_field($value);
					}
					// end sanitize
					$_pref['apikey'] = $_pref['apikey'] == get_option($this->plugin_pref . '_apikey', null) ? $_pref['apikey'] : null;
					$_pref['atk'] = $_pref['apikey'] == get_option($this->plugin_pref . '_apikey', null) ? $_pref['atk'] : null;
					update_option( $this->plugin_pref , $_pref );
					$this->loadPreferences();
					$active_subtab = ( isset( $_POST['sub_tab'] ) ) ? sanitize_text_field( $_POST['sub_tab'] ) : 0;
					wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&tab=preferences&info=save&sub_tab=' . $active_subtab  );
				}
				exit;
		}
		if ($this->userCan('manage_options') && isset( $_POST['load_website_codes'] )) {
				if (isset( $_POST['param'] ) && is_array( $_POST['param'] ) ){
					$this->loadPreferences();
					// generic sanitized text parameter (all preferences are strings)
					$_pref  = array();
					$param = $_POST['param'];
					foreach ( $param as $key => $value ) {
						$_pref[$key] = sanitize_text_field($value);
					}
					// end sanitize
					$message = '';
					if (isset($_pref['apikey']) && !empty($_pref['apikey']) ){
						// load tracking codes from WeTravelHub
						$http_args = array(
							'headers' => array(
								'Accept' => 'application/vnd.hth.api.app.v1.0.0+json'
							),
							'httpversion' => '1.0',
							'timeout' => 15
						);

						$url = $this->atk_url . '?apiKey=' .  urlencode($_pref['apikey']);
						$response = wp_remote_get( $url, $http_args );
						$code =  wp_remote_retrieve_response_code( $response );

						if ( empty($code) || $code != '200' || empty($response['body']) || $response['body'] == '[]') {
							// nothing to do, no tracking codes found
							$message = '&error=nowebsitecodes';
						} else {
							$websitecodes = json_decode( $response['body'], false);
							// clean old tracking codes
							$this->db->delete($this->websiteCodesTable);
							// insert tracking codes into db
							foreach ( $websitecodes as $value ) {
								$this->db->upsertRecord($this->websiteCodesTable, array('id' => $value->atk, 'website' =>  $value->domain), null);
							}
							$verifiedApiKey = $_pref['apikey'];
							$_pref = $this->loadPreferences();
							if ($verifiedApiKey != $_pref['apikey']){
								// clean default atk
								$_pref['atk'] = null;
							}
							$_pref['apikey'] = $verifiedApiKey;
							update_option($this->plugin_pref . '_apikey' , $verifiedApiKey );
							update_option( $this->plugin_pref , $_pref );
							$message = '&info=save';
						}
					} else {
						if (! get_option($this->plugin_pref . '_apikey')){
							delete_option($this->plugin_pref . '_apikey');
							$_pref = $this->loadPreferences();
							$_pref['apikey'] = null;
							$_pref['atk'] = null;
							update_option( $this->plugin_pref , $_pref );
						}
					}

					$active_subtab = ( isset( $_POST['sub_tab'] ) ) ? sanitize_text_field( $_POST['sub_tab'] ) : 0;
					wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&tab=preferences&sub_tab=' . $active_subtab . $message  );
				}
				exit;
		}
	}

}
