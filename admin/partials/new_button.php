<?php
/**
 * Create/Edit button view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */
	$table = $this->buttonsTable;

	include_once ( 'load-db-record.php' );

	$page_number = 1;

	$allowChangeWebsiteCode = $this->userCan('change_websitecode_buttons') && $this->preferences['use_default_atk'] != 'on';

	if ($result){
		$name = $result->name;
		$desc = $result->desc;
		$atk = $result->atk;
		$style = $result->style;
		$config = $result->config;
		$page_number = ( isset( $_REQUEST['paged'] ) ) ? sanitize_text_field( $_REQUEST['paged'] ) : 1;
	} else {
		$name = "";
		$desc = "";
		$atk = "";
		$style = "";
		$config = "";
	}
	$title = __('Button', 'the-travel-button') . ' #' . $form_id;

	$results = $this->db->data($this->stylesTable, null, null, 1, null);

	$styles = array();

	$directLinkSortCode = "[" . $this->shortcode . " id='" . $form_id . "' mode_url='%s'/]";

	$customStyleSortCode = "[" . $this->shortcode . " id='" . $form_id . "' mode_custom='%s']%s[/" . $this->shortcode . "]";

	if ( $results ) {
		foreach ( $results as $key => $value ) {
			$styles[] = array(
				'id'        => $value->id,
				'name'      => $value->id . ' - ' . (!empty( $value->name ) ? $value->name : __('Untitled', 'the-travel-button')),
				'config'    => $value->config
			);
		}
	}

	$results = $this->db->data($this->websiteCodesTable, null, null, 1, null);

	$websitecodes = array();

	if ( $results ) {
		foreach ( $results as $key => $value ) {
			$websitecodes[] = array(
				'id'        => $value->id,
				'name'      =>  $value->website
			);
		}
	}

?>

<div id="new-button" class="hwt-scoped" hwt-modal>
	 <div class="hwt-modal-dialog">
		<button class="hwt-modal-close-default" type="button" hwt-close></button>
		<div class="hwt-modal-header">
			<h2 class="hwt-modal-title"><span class="hwt-margin-small-right" hwt-icon="icon: wth-button; ratio: 2"></span><?php echo $title; ?></h2>
		</div>
		<div class="hwt-width-auto@m hwt-modal-body hwt-padding-remove-bottom">
			<ul class="hwt-tab" hwt-tab="connect: #component-tab-left; animation: hwt-animation-fade">
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="table"></span><?php esc_html_e( 'General', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="location"></span><?php esc_html_e( 'Add destinations', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="link"></span><?php esc_html_e( 'Direct links', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="image"></span><?php esc_html_e( 'Own style', 'the-travel-button' ); ?></a></li>
			</ul>
		</div>
		<form id="new_button" action="admin.php?page=<?php echo $this->plugin_slug;?>" method="post" name="post" >
		<div class="hwt-modal-body" hwt-overflow-auto>
			 <ul id="component-tab-left" class="hwt-switcher" >
					<li>
						<fieldset class="hwt-fieldset">

							<legend class="hwt-legend"><?php esc_html_e( 'Name', 'the-travel-button' ); ?></legend>
							<div class="hwt-margin">
								<input class="hwt-input" type="text" id="name" name="name" placeholder="<?php esc_html_e( 'enter a short descriptive name', 'the-travel-button' ); ?>" value="<?php echo esc_html( $name ); ?>">
							</div>

							<legend class="hwt-legend"><?php esc_html_e( 'Additional information', 'the-travel-button' ); ?></legend>
							<div class="hwt-margin">
								<textarea id="desc" id="desc" name="desc" class="hwt-textarea" rows="5" placeholder="<?php esc_html_e( 'enter additional information', 'the-travel-button' ); ?>"><?php echo esc_html( $desc ); ?></textarea>
							</div>

							<?php if ($allowChangeWebsiteCode) :?>
							<legend class="hwt-legend"><?php esc_html_e( 'Tracking code', 'the-travel-button' ); ?></legend>
							<div class="hwt-margin">
								<select class="hwt-select" name="atk" id="atk" oninput="jQuery('.outputatk').html(this.value);" style="width:100%">
									<option value=""><?php esc_html_e( 'Default tracking code', 'the-travel-button' ); ?></option>
									<?php foreach ($websitecodes as $row) :?>
									<option <?php echo $row['id'] == $atk ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"><?php echo esc_html($row['name']); ?></option>
									<?php endforeach;?>
								</select>
								<output class="hwt-text-italic hwt-text-truncate outputatk"><?php echo $atk; ?></output>
							</div>
							<?php endif;?>

							<legend class="hwt-legend"><?php esc_html_e( 'Style', 'the-travel-button' ); ?></legend>
							<div class="hwt-margin">
								<select class="hwt-select" name="style" id="style" style="width:100%">
									<?php foreach ($styles as $row) :?>
										<option <?php echo $row['id'] == $style ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"><?php echo esc_html($row['name']); ?></option>
									<?php endforeach;?>
								</select>
							</div>

						</fieldset>
					</li>
					<li style="height: calc(100vh - 400px); max-height: 600px;">
					   <iframe id="config-iframe" src="<?php echo $this->renderer->configLocationsUrl($config, null); ?>" onload="loadConfiguration(this)" scrolling="yes" allow="fullscreen" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox" style="width: 100% !important; height: 100% !important; overflow: hidden; border: none;"></iframe>
					</li>
					<li>
						<p><?php esc_html_e( 'You can use these shortcodes to generate URLs for this Travel Button and use them with your own content as custom links, images, banners... for example:', 'the-travel-button' ); ?></p>
						<p><code><?php echo esc_html( "<a href=\"" . sprintf( $directLinkSortCode, 'go' ) . "\">go!!</a>"); ?></code></p>
						<dl class="hwt-description-list">
							<dt><?php esc_html_e( 'All tourism services', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'all' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Accommodation', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'dream' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Restaurants', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'eat' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Activities', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'do' ); ?></pre></dd>
						</dl>
						<p class="hwt-text-large"><?php esc_html_e( 'Transport specific', 'the-travel-button' ); ?></p>
						<dl class="hwt-description-list">
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Flights', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go-plane' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Train', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go-train' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Car', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go-car' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Bus', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go-bus' ); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Vacation packages', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php printf( $directLinkSortCode, 'go-packages' ); ?></pre></dd>
						</dl>
					</li>
					<li>
						<p><?php esc_html_e( 'You can use these shortcodes to make your html content, with your own style, launches the travel button when user click on it, for example:', 'the-travel-button' ); ?></p>
						<dl class="hwt-description-list">
							<dt><?php esc_html_e( 'Accommodation', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'dream', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Restaurants', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'eat', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Activities', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'do', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL)); ?></pre></dd>
						</dl>
						<p class="hwt-text-large"><?php esc_html_e( 'Transport specific', 'the-travel-button' ); ?></p>
						<dl class="hwt-description-list">
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Flights', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go-plane', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Train', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go-train', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Car', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go-car', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Bus', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go-bus', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
							<dt><?php esc_html_e( 'Transport', 'the-travel-button' ); echo ' - '; esc_html_e( 'Vacation packages', 'the-travel-button' ); ?></dt>
							<dd><pre style="color: #bc242f;"><?php echo esc_html(sprintf( $customStyleSortCode, 'go-packages', PHP_EOL.'    <div id="this_is_my_custom_html_content"><p>Click!</p></div>'.PHP_EOL )); ?></pre></dd>
						</dl>
					</li>
				</ul>
				<textarea id="config" name="config" style="display:none"><?php echo esc_html( $config ); ?></textarea>
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>" />
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="act" id="act" value="<?php echo $action; ?>" />
				<input type="hidden" name="paged" id="paged" value="<?php echo $page_number; ?>" />
				<input type="hidden" name="s" id="s" value="<?php echo $filterValue; ?>" />
				<?php wp_nonce_field( $this->plugin_slug . '_button_action', $this->plugin_slug . '_button_nonce' ); ?>
			</div>
			<?php if ($this->userCan('create_buttons')) :?>
				<div class="hwt-modal-footer hwt-text-right">
					<button class="hwt-button hwt-button-default hwt-modal-close" type="button"><?php esc_html_e( 'Cancel', 'the-travel-button' ); ?></button>
					<input name="submit" id="submit" class="hwt-button hwt-button-primary" value="<?php esc_html_e( 'Save', 'the-travel-button' ); ?>" type="submit">
				</div>
			<?php endif;?>
		</form>
	</div>
</div>
<script>
'use strict';
jQuery(document).ready(function($) {
	// customized select component
	jQuery('.hwt-select').select2({theme: "wtb"});
	// open modal editor
 	hwtUIkit.modal("#new-button").show();
});

function loadConfiguration(configIframe) {
	configIframe.contentWindow.postMessage(<?php echo $this->renderer->configLocationsMessage($config, null); ?>,"<?php echo $this->config_origin; ?>");
}

function updateWTHButton(message) {
    <?php if ($this->userCan('create_buttons')) :?>
     jQuery('#config').text(message);
    <?php endif;?>
}
</script>
