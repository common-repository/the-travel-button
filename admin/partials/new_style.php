<?php
/**
 * Create/Edit style view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */
	$table = $this->stylesTable;

	include_once ( 'load-db-record.php' );

	$page_number=1;

	if ($result){
		$name = $result->name;
		$desc = $result->desc;
		$config = $result->config;
		$css = $result->css;
		$page_number = ( isset( $_REQUEST['paged'] ) ) ? sanitize_text_field( $_REQUEST['paged'] ) : 1;
	} else {
		$name = "";
		$desc = "";
		$config = "";
		$css = "";
	}
	$title = __('Style', 'the-travel-button') . ' #' . $form_id;
?>

<div id="new-style" class="hwt-scoped" hwt-modal>
       <div class="hwt-modal-dialog">
        <button class="hwt-modal-close-default" type="button" hwt-close></button>
        <div class="hwt-modal-header">
            <h2 class="hwt-modal-title"><span class="hwt-margin-small-right" hwt-icon="icon: paint-bucket; ratio: 2"></span><?php echo $title; ?></h2>
        </div>
		<div class="hwt-width-auto@m hwt-modal-body hwt-padding-remove-bottom">
			<ul class="hwt-tab" hwt-tab="connect: #component-tab-left; animation: hwt-animation-fade">
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="table"></span><?php esc_html_e( 'General', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><span class="hwt-margin-small-right" hwt-icon="settings"></span><?php esc_html_e( 'Customize', 'the-travel-button' ); ?></a></li>
				<li><a href="#" style="padding: 5px 0px;"><span class="hwt-margin-small-right" hwt-icon="code"></span><?php esc_html_e( 'Predefined CSS classes', 'the-travel-button' ); ?></a></li>
			</ul>
		</div>
	   <form id="new_style" action="admin.php?page=<?php echo $this->plugin_slug;?>" method="post" name="post" >
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

						    <legend class="hwt-legend"><?php esc_html_e( 'Additional CSS classes', 'the-travel-button' ); ?></legend>
							<div class="hwt-margin">
								<input class="hwt-input" type="text" id="css" name="css" placeholder="wth-tb-align-center wth-tb-margin-bottom" value="<?php echo esc_html( $css ); ?>">
								<output class="hwt-text-italic hwt-text-truncate"><?php esc_html_e( 'Separate multiple classes with spaces.', 'the-travel-button' ); ?></output>
							</div>

						</fieldset>
					</li>
					<li style="height: calc(100vh - 400px); max-height: 600px;">
						<div id="config-style-iframe" style="width: 100% !important; height: 100% !important; overflow: hidden; border: none;">
						   <a id="toogle-fullscreen" class="wth-fullscreen-toogle-button" hwt-tooltip="<?php esc_html_e( 'Full screen mode editor', 'the-travel-button' ); ?>"></a>
						   <iframe id="config-iframe" src="<?php echo $this->renderer->configStyleUrl($config, null); ?>" onload="loadConfiguration(this)" scrolling="yes" allow="fullscreen" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox" style="width: 100% !important; height: 100% !important; overflow: hidden; border: none;"></iframe>
						</div>
					</li>
					<li>
						<p><?php esc_html_e( "You can use these predefined CSS classes as additional CSS in your button's style configuration.", 'the-travel-button' ); ?></p>
						<table class="hwt-table hwt-table-divider ux-margin-remove-top">
							<thead>
								<tr>
									<th align="left"><?php esc_html_e( 'Class', 'the-travel-button' ); ?></th>
									<th align="left"><?php esc_html_e( 'Description', 'the-travel-button' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="left"><code>wth-tb-align-center</code></td>
									<td align="left"><?php esc_html_e( 'Centers the button', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-align-left</code></td>
									<td align="left"><?php esc_html_e( 'Floats the button to the left', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-align-right</code></td>
									<td align="left"><?php esc_html_e( 'Floats the button to the right', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-margin</code></td>
									<td align="left"><?php esc_html_e( 'Adds 20px top margin, if it is preceded by another element, and always 20px bottom margin', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-margin-top</code></td>
									<td align="left"><?php esc_html_e( 'Adds 20px top margin', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-margin-bottom</code></td>
									<td align="left"><?php esc_html_e( 'Adds 20px bottom margin', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-margin-left</code></td>
									<td align="left"><?php esc_html_e( 'Adds 20px left margin', 'the-travel-button' ); ?></td>
								</tr>
								<tr>
									<td align="left"><code>wth-tb-margin-right</code></td>
									<td align="left"><?php esc_html_e( 'Adds 20px right margin', 'the-travel-button' ); ?></td>
								</tr>
							</tbody>
						</table>
                    </li>
				</ul>
				<textarea id="config" name="config" style="display:none"><?php echo esc_html( $config ); ?></textarea>
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>" />
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="act" id="act" value="<?php echo $action; ?>" />
				<input type="hidden" name="paged" id="paged" value="<?php echo $page_number; ?>" />
				<input type="hidden" name="s" id="s" value="<?php echo $filterValue; ?>" />
				<?php wp_nonce_field( $this->plugin_slug . '_style_action', $this->plugin_slug . '_style_nonce' ); ?>
        </div>
		<?php if ($this->userCan('create_styles')) :?>
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
	// full screen mode style editor
	var configStyle = jQuery('#config-style-iframe');
	var elem = configStyle.get(0);

	// show only whether full screen mode is supported
	if (elem.requestFullscreen || elem.webkitRequestFullscreen || elem.msRequestFullscreen ){

		var expand = '<span class="hwt-icon-button hwt-margin-small-right" hwt-icon="expand">';
		var shrink = '<span class="hwt-icon-button hwt-margin-small-right" hwt-icon="shrink">';

		jQuery('#toogle-fullscreen').addClass('wth-fullscreen-toogle-button-enabled');
		jQuery('#toogle-fullscreen').html(expand);

		hwtUIkit.util.on('#toogle-fullscreen', 'click', function (e) {
		    e.preventDefault();
			if (configStyle.hasClass("wth-fullscreen-mode")){
			  configStyle.removeClass("wth-fullscreen-mode");
			  jQuery('#toogle-fullscreen').html(expand);
			  if (document.exitFullscreen) {
				document.exitFullscreen();
			  } else if (document.webkitExitFullscreen) { /* Safari */
				document.webkitExitFullscreen();
			  } else if (document.msExitFullscreen) { /* IE11 */
				document.msExitFullscreen();
			  }
			} else {
			  if (elem.requestFullscreen) {
				elem.requestFullscreen();
			  } else if (elem.webkitRequestFullscreen) { /* Safari */
				elem.webkitRequestFullscreen();
			  } else if (elem.msRequestFullscreen) { /* IE11 */
				elem.msRequestFullscreen();
			  }
			  configStyle.addClass("wth-fullscreen-mode");
			  jQuery('#toogle-fullscreen').html(shrink);
		   }
		});
	}
	// open modal editor
	hwtUIkit.modal("#new-style").show();
});

function loadConfiguration(configIframe) {
	configIframe.contentWindow.postMessage(<?php echo $this->renderer->configStyleMessage($config, null); ?>,"<?php echo $this->config_origin; ?>");
}

function updateWTHButton(message) {
    <?php if ($this->userCan('create_styles')) :?>
     jQuery('#config').text(message);
    <?php endif;?>
}
</script>
