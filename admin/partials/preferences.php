<?php
/**
 * Preferences view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */

if (!$this->userCan('manage_options')) exit;

$param = $this->preferences;

$subtab = ( isset( $_REQUEST['sub_tab'] ) ) ? sanitize_text_field( $_REQUEST['sub_tab'] ) : 0;

$info = ( isset( $_REQUEST['info'] ) ) ? sanitize_text_field( $_REQUEST['info'] ) : '';

$error = ( isset( $_REQUEST['error'] ) ) ? sanitize_text_field( $_REQUEST['error'] ) : '';

$showApiAlert = !$p_apikey || empty($p_apikey);
$showAtkAlert = (!$p_atk || empty($p_atk)) && ! $showApiAlert;

$badgeDanger = ' <span class="hwt-badge hwt-label-danger">1</span>';

$badgeGeneral = ($showApiAlert || $showAtkAlert) ? $badgeDanger : '';
$badgeApiKey = $showApiAlert ? $badgeDanger : '';
$badgeAtk = $showAtkAlert ? $badgeDanger : '';

if ( !empty($info) ) {
	if ( $info == 'save' ) {
		echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: check\'></span> ' . __( 'stored', 'the-travel-button' ) . '", {status:\'success\', pos: \'top-center\'});</script>';
	}
}

if ( !empty($error) ) {
	if ( $error == 'nowebsitecodes' ) {
		echo '<script>hwtUIkit.notification("<span hwt-icon=\'icon: close\'></span> ' . __( 'No tracking codes found', 'the-travel-button' ) . '", {status:\'danger\', pos: \'top-center\'});</script>';
	}
}

$secProfiles = array(
	'editor'      => __( 'Editor', 'the-travel-button' ),
	'author'      => __( 'Author', 'the-travel-button' ),
	'contributor'      => __( 'Contributor', 'the-travel-button' )
);


$secActions = array(
	'view_buttons'      => __( 'Allow Travel Buttons to be displayed and added to pages and posts', 'the-travel-button' ),
	'create_buttons'      => __( 'Allow Travel Buttons to be created and edited', 'the-travel-button' ),
	'change_websitecode_buttons'      => __( 'Allow tracking code to be changed when creating or editing Travel Buttons', 'the-travel-button' ),
	'delete_buttons'      => __( 'Allow delete Travel Buttons', 'the-travel-button' ),
	'create_styles'      => __( 'Allow create and edit styles', 'the-travel-button' ),
	'delete_styles'      => __( 'Allow delete styles', 'the-travel-button' )
);

$results = $this->db->data($this->websiteCodesTable, null, null, null, null);

$websitecodes = array();

if ( $results ) {
	foreach ( $results as $key => $value ) {
		$websitecodes[] = array(
			'id'        => $value->id,
			'name'     =>  $value->website
		);
	}
}

$langHelp = __('en', 'the-travel-button');
$langHelp = empty ($langHelp) || $langHelp == 'en' ? '' : '/' . $langHelp;
?>
<div class="wrap hwt-scoped">
	<div hwt-grid>
		<div class="hwt-width-auto@m">
			<ul id="preferences-tab" class="hwt-tab-left" hwt-tab="connect: #preferences-tab-left; animation: hwt-animation-fade">
				<li <?php echo ($subtab == 0 ? 'class="hwt-active"':''); ?>><a href="#" onclick="jQuery('#sub_tab').val('0');"><span class="hwt-margin-small-right" hwt-icon="settings"></span><?php _e( 'General', 'the-travel-button' ); ?> <?php echo $badgeGeneral; ?></a></li>
				<li <?php echo ($subtab == 1 ? 'class="hwt-active"':''); ?>><a href="#" onclick="jQuery('#sub_tab').val('1');"><span class="hwt-margin-small-right" hwt-icon="lock"></span><?php _e( 'Security', 'the-travel-button' ); ?></a></li>
			</ul>
		</div>
		<div class="hwt-width-expand@m">
			<form id="preferences" action="admin.php?page=<?php echo $this->plugin_slug;?>" method="post" name="post">
			<ul id="preferences-tab-left" class="hwt-switcher">
				<li>
						<?php if ($showApiAlert) :?>
						<div class="hwt-alert-danger" hwt-alert>
							<a class="hwt-alert-close" hwt-close></a>
							<p><a href="<?php echo sprintf($this->tutorial_url, $langHelp); ?>" target="_blank"><?php esc_html_e( 'Please, load your We Travel Hub tracking codes.', 'the-travel-button' ); ?></a></p>
							<p><?php _e( 'We need to know your tracking code to keep track of transactions made by your visitors through The Travel Button® and make sure you earn your affiliate commissions.', 'the-travel-button' ); ?></p>
							<a href="<?php echo $this->account_url; ?>" target="_blank" class="hwt-button hwt-button-danger"><?php _e( 'Manage your account at We Travel Hub', 'the-travel-button' ); ?></a>
						</div>
						<?php endif;?>

						<?php if ($showAtkAlert) :?>
						<div class="hwt-alert-danger" hwt-alert>
							<a class="hwt-alert-close" hwt-close></a>
							<p><a href="<?php echo sprintf($this->tutorial_url, $langHelp); ?>" target="_blank"><?php esc_html_e( 'Please, configure your default tracking code.', 'the-travel-button' ); ?></a></p>
							<p><?php _e( 'We need to know your tracking code to keep track of transactions made by your visitors through The Travel Button® and make sure you earn your affiliate commissions.', 'the-travel-button' ); ?></p>
							<a href="<?php echo $this->account_url; ?>" target="_blank" class="hwt-button hwt-button-danger"><?php _e( 'Manage your account at We Travel Hub', 'the-travel-button' ); ?></a>
						</div>
						<?php endif;?>
						<fieldset class="hwt-fieldset">
									<legend class="hwt-legend"><?php esc_html_e( 'API Key', 'the-travel-button' ); ?> <?php echo $badgeApiKey; ?></legend>
									<div class="hwt-margin">
										<div class="hwt-grid-small" hwt-grid>
											<div class="hwt-width-1-2@s">
												<input class="hwt-input" type="text" placeholder="<?php esc_html_e( 'Enter your We Travel Hub account API Key and load your tracking codes', 'the-travel-button' ); ?>" name="param[apikey]" value="<?php echo $param['apikey']; ?>">
											</div>
											<div class="hwt-width-1-2@s">
												 <input  name="load_website_codes" id="load_website_codes" class="hwt-button hwt-button-secondary" value="<?php esc_html_e( 'Load tracking codes', 'the-travel-button' ); ?>" type="submit">
											</div>
										</div>
									</div>

									<legend class="hwt-legend"><?php esc_html_e( 'Default tracking code', 'the-travel-button' ); ?> <?php echo $badgeAtk; ?></legend>
									<div class="hwt-margin">
										<div class="hwt-grid-small" hwt-grid>
											<div class="hwt-width-1-2@s">
												<select class="hwt-select" name="param[atk]" style="width:100%" value="<?php echo $param['atk']; ?>" <?php echo $showApiAlert ? 'disabled':''; ?> onchange="jQuery('.outputatk').html(this.value);">
													<option value="" <?php echo empty($param['atk']) ? 'selected': ''; ?>><?php esc_html_e( 'Tracking code is not configured', 'the-travel-button' ); ?></option>
													<?php foreach ($websitecodes as $row) :?>
													<option <?php echo $row['id'] == $param['atk'] ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"><?php echo esc_html($row['name']); ?></option>
													<?php endforeach;?>
												</select>
												<output class="hwt-text-italic hwt-text-truncate outputatk" ><?php echo $param['atk']; ?></output>
											</div>
										</div>
									</div>

									<div class="hwt-margin">
										<label><input type="checkbox" name="param[use_default_atk]" <?php echo $param['use_default_atk']=='on'?'checked':''; ?>><?php esc_html_e( 'Use default tracking code on all Travel Buttons, otherwise the tracking code defined on each Travel button will be used', 'the-travel-button' ); ?></label>
									</div>
									<div class="hwt-margin">
										<label><input type="checkbox" name="param[hide_buttons_no_atk]" <?php echo $param['hide_buttons_no_atk']=='on'?'checked':''; ?>><?php esc_html_e( 'Hide Travel Buttons if the tracking code is not configured', 'the-travel-button' ); ?></label>
									</div>
									<div class="hwt-margin">
										<label><input type="checkbox" name="param[disable_prefetch]" <?php echo $param['disable_prefetch']=='on'?'checked':''; ?>><?php esc_html_e( 'Disable resource preload (NOT RECOMMENDED). By default, resource preload is enabled to improve user experience', 'the-travel-button' ); ?></label>
									</div>
									<legend class="hwt-legend"><span><?php esc_html_e( 'Items per page', 'the-travel-button' ); ?></span>: <span class="items-per-page"><?php echo $param['per_page']; ?></span></legend>
									<div class="hwt-margin">
										<div class="hwt-grid-small" hwt-grid>
											<div class="hwt-width-1-2@s">
												<label>
												<input class="hwt-range" type="range" min="5" max="50" step="5" name="param[per_page]" value="<?php echo $param['per_page']; ?>" oninput="jQuery('.items-per-page').html(this.value);">
												</label>
											</div>
										</div>
									</div>
					</fieldset>
				</li>
				<li>
					<table class="hwt-table hwt-table-hover hwt-table-divider">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Permissions', 'the-travel-button' ); ?></th>
								<?php foreach ($secProfiles as $s_profile => $p_name) :?>
								<th><?php echo $p_name; ?></th>
								<?php endforeach;?>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($secActions as $s_action => $a_name) :?>
							<tr>
								<td><?php echo $a_name; ?></td>
								<?php foreach ($secProfiles as $s_profile => $p_name) :?>
								<td><input type="checkbox" name="param[<?php echo $s_profile . '_' . $s_action; ?>]" <?php echo $param[$s_profile . '_' . $s_action]=='on'?'checked':''; ?>></td>
								<?php endforeach;?>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
				</li>
			</ul>
			<input type="hidden" name="sub_tab" id="sub_tab" value="<?php echo $subtab; ?>" />
			<?php wp_nonce_field( $this->plugin_slug . '_preferences_action', $this->plugin_slug . '_preferences_nonce' ); ?>
			<input name="submit" id="submit" class="hwt-button hwt-button-primary" value="<?php esc_html_e( 'Save', 'the-travel-button' ); ?>" type="submit">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
'use strict';
jQuery(document).ready(function($) {
	// customized select component
	jQuery('.hwt-select').select2({theme: "wtb"});
	// select tab
	hwtUIkit.tab("#preferences-tab").show(<?php echo $subtab; ?>);
});
</script>
