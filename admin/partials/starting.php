<?php
/**
 * First installation starting view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */
	$step1 = $current_styles == 0;
	$step2 = $current_buttons == 0 && !$step1;
?>
<div class="hwt-placeholder hwt-text-center">
	<div class="hwt-align-center">
		<?php if ( $this->userCan('create_styles') && $step1 ) :?>
			<h2 class="hwt-heading-small"><?php _e( "let's get started! set up your first Travel Button", 'the-travel-button' ); ?></h2>
			<h3 class="hwt-text-italic"><?php _e( "do you like it in this style?", 'the-travel-button' ); ?></h3>
			<div class="hth-button wth-tb-align-center" hth-type="multiple" hth-size="4" hth-orientation="horizontal" hth-separation="0.5" hth-position="fixed" hth-location="eyJpZCI6ImIxNTY0NGM5LWI3NmItMzcyOC1hMDUxLTkwOTRhZTZkZmFhYyIsInRpbGVSZWZlcmVuY2UiOjY4NzE5NDc2NzU2fQ=="></div>
			<a href="?page=<?php echo $this->plugin_slug; ?>&tab=styles&modal=new_style" class="hwt-button hwt-button-primary hwt-margin"><?php _e( 'find your style', 'the-travel-button' ); ?></a>
		<?php endif;?>
		<?php if ( $this->userCan('create_buttons') && $step2 ) :?>
			<h2 class="hwt-heading-small"><?php _e( 'you are almost done!', 'the-travel-button' ); ?></h2>
			<a href="?page=<?php echo $this->plugin_slug; ?>&tab=buttons&modal=new_button" class="hwt-button hwt-button-primary hwt-margin"><?php _e( 'Create your first Travel Button', 'the-travel-button' ); ?></a>
		<?php endif;?>
		<?php if ( !$this->userCan('create_styles') && $step1 ) :?>
			<h2 class="hwt-heading-small"><?php _e( 'you are not allowed to create Travel Button styles<br/>Please, contact with the admin.', 'the-travel-button' ); ?></h1>
		<?php endif;?>
		<?php if ( !$this->userCan('create_buttons') && $step2 ) :?>
			<h2 class="hwt-heading-small"><?php _e( 'you are not allowed to create Travel Buttons<br/>Please, contact with the admin.', 'the-travel-button' ); ?></h1>
		<?php endif;?>
	</div>
</div>
