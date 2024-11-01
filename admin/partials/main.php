<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */

	$current_tab = ( isset( $_REQUEST["tab"] ) ) ? sanitize_text_field( $_REQUEST["tab"] ) : 'buttons';

	$current_modal = ( isset( $_REQUEST["modal"] ) ) ? sanitize_text_field( $_REQUEST["modal"] ) : '';

	$action = ( isset( $_REQUEST["act"] ) ) ? sanitize_text_field( $_REQUEST["act"] ) : '';

	// to load starting page
	$current_buttons = $this->db->count($this->buttonsTable, null, null);
	$current_styles = $this->db->count($this->stylesTable, null, null);
	$starting =  ($current_buttons == 0 || $current_styles == 0) && !get_option($this->plugin_pref . '_starting');

	$menu_tabs= array();

	if ($starting){
		$current_tab = "starting";
		$menu_tabs = array(
							'starting'      => __( 'Starting', 'the-travel-button' )
						);
	} else {
		// disable statirg page
		update_option( $this->plugin_pref . '_starting', true);
		$menu_tabs= array(
    						'buttons'      => __( 'Buttons', 'the-travel-button' ),
    						'styles'      => __( 'Styles', 'the-travel-button' )
    					);
    	// add settings page
		if ($this->userCan('manage_options')){
			$menu_tabs['preferences'] = __( 'Settings', 'the-travel-button' );
		}
	}

	$menu_icons= array(
			'starting'    => 'bolt',
			'buttons'     => 'wth-button',
			'styles'      => 'paint-bucket',
			'preferences' => 'cog'
	);

	// share plugin social
	$share = array(
		"facebook" =>  'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($this->plugin_home_url),
		"twitter" =>  'https://twitter.com/share?url=' . urlencode($this->plugin_home_url) . '&text=' . urlencode($this->plugin_name),
		"linkedin" =>  'https://www.linkedin.com/shareArticle?url=' . urlencode($this->plugin_home_url) . '&title=' . urlencode($this->plugin_name),
		"pinterest" =>  'https://pinterest.com/pin/create/button/?url=' . urlencode($this->plugin_home_url),
		"reddit" =>  'http://www.reddit.com/submit?url=' . urlencode($this->plugin_home_url) . '&title=' . urlencode($this->plugin_name)
	);

	$preferences = $this->preferences;

	$p_atk = $preferences["atk"];
	$p_apikey = $preferences["apikey"];

	$langHelp = __('en', 'the-travel-button');
	$langHelp = empty ($langHelp) || $langHelp == 'en' ? '' : '/' . $langHelp;
?>

<div class="wrap hwt-scoped">
  <div class="hwt-align-left">
  	<div class="hwt-logo">
		<img data-src="<?php echo $this->plugin_url; ?>resources/logo-wth.png" class="wth-logo"  alt="" hwt-img>
		<h1 class="wp-heading-inline"><?php _e( 'The Travel Button®', 'the-travel-button' ); ?><span style="font-size: 16px;"> v. <?php echo $this->plugin_version; ?> </span></h1>
		<?php if (!$starting) :?>
			<?php if ($this->userCan('create_buttons')) :?>
				<a href="?page=<?php echo $this->plugin_slug; ?>&tab=buttons&modal=new_button" class="hwt-button hwt-button-default"><span class="hwt-margin-small-right" hwt-icon="plus-circle"></span><?php _e( 'New button', 'the-travel-button' ); ?></a>
			<?php endif;?>
			<?php if ($this->userCan('create_styles')) :?>
				<a href="?page=<?php echo $this->plugin_slug; ?>&tab=styles&modal=new_style" class="hwt-button hwt-button-default"><span class="hwt-margin-small-right" hwt-icon="plus-circle"></span><?php _e( 'New style', 'the-travel-button' ); ?></a>
			<?php endif;?>
		<?php endif;?>
	</div>
  </div>

   <hr class="wp-header-end">

   <ul class="hwt-tab hwt-width1-1">
	<?php
		foreach ( $menu_tabs as $tab => $name ) {
			$class = ( $tab === $current_tab ) ? 'class="hwt-active"' : '';
			$badge = ( $tab === 'preferences' && ( !$p_atk || empty($p_atk) || !$p_apikey || empty($p_apikey) ) ) ? ' <span class="hwt-badge hwt-label-danger">1</span>':'';
			echo '<li ' .  $class . '><a href="?page=' . $this->plugin_slug . '&tab=' . esc_attr( $tab ) . '"><span class="hwt-margin-small-right" hwt-icon="' . $menu_icons[$tab]. '"></span>' . esc_attr( $name ) . $badge .'</a></li>';
		}
	 ?>

	<li>
		<a href="#"><span class="hwt-margin-small-right" hwt-icon="lifesaver"></span><?php _e( 'Help', 'the-travel-button' ); ?> <span class="hwt-margin-small-left" hwt-icon="icon: triangle-down"></span></a>
		<div id="help_tab" hwt-dropdown="mode: click">
			<ul class="hwt-nav hwt-dropdown-nav">
				<li><a href="#" onclick="showAbout()"><?php esc_html_e( 'About this plugin', 'the-travel-button' ); ?></a></li>
				<li class="hwt-nav-divider"></li>
				<li><a href="<?php echo sprintf($this->doc_url, $langHelp); ?>" target="_blank"><?php esc_html_e( 'Documentation & FAQs', 'the-travel-button' ); ?></a></li>
				<li><a href="<?php echo sprintf($this->tutorial_url, $langHelp); ?>" target="_blank"><?php esc_html_e( 'Tutorials', 'the-travel-button' ); ?></a></li>
				<li><a href="<?php echo $this->support_url; ?>" target="_blank"><?php esc_html_e( 'Contact', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'Share', 'the-travel-button' ); ?><span class="hwt-margin-small-left" hwt-icon="icon: triangle-right"></span></a>
					<div id="share_tab" hwt-dropdown="mode: click;pos: right-top">
						<ul class="hwt-nav hwt-dropdown-nav">
								<?php foreach ($share as $icon => $url) :?>
								<li>
								<a href="<?php echo $url; ?>" target="_blank"><span class="hwt-icon-button hwt-margin-small-right" hwt-icon="<?php echo $icon; ?>"></span><?php echo $icon; ?></a>
								</li>
								<?php endforeach;?>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</li>

	</ul>
	<?php
		// load tab
		$file = apply_filters( 'wth_travelbutton_include_file', $current_tab );
		if ($file){
			include_once( $file . '.php' );
		}
		// load modal
		if ( $current_modal != '' ) {
			$file = apply_filters( 'wth_travelbutton_include_file', $current_modal );
			if ($file){
				include_once( $file . '.php' );
			}
		}
		// load about view
		include_once('about_wth.php' );
	?>
</div>
<script type="text/javascript">
'use strict';

jQuery(document).ready(function($) {
	registerEventListener();
});


function registerEventListener(){
	if (window.addEventListener) {
		window.addEventListener("message", onMessage, false);
	}
	else if (window.attachEvent) {
		window.attachEvent("onmessage", onMessage, false);
	}
}

function onMessage(event) {
<?php if ($this->userCan('create_buttons') || $this->userCan('create_styles')) :?>
    if (event.origin !== "<?php echo $this->config_origin; ?>") return;
    var data = event.data;
    if (typeof(window[data.func]) == "function" && data.func == "updateWTHButton" ) {
        window[data.func].call(null, data.message);
    }
<?php endif;?>
}
</script>
