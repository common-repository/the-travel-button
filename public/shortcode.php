<?php
/**
 * Process WTH button shortcode.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/public
 */

extract( shortcode_atts( array( 'id' => "", 'mode_url' => "", 'mode_custom' => "", ), $atts ) );

if ( ! empty( $atts['id'] ) ) {
	$id = $atts['id'];
} else {
	return false;
}

// generate only button url
$mode_url = null;
if ( ! empty( $atts['mode_url'] ) ) {
	$mode_url = $atts['mode_url'];
}

// generate button mode custom
$mode_custom = null;
if ( ! empty( $atts['mode_custom'] ) ) {
	$mode_custom = $atts['mode_custom'];
}

global $wpdb;

// load button definition (button style + button locations)
$pSQL = $wpdb->prepare( "SELECT B.config, B.atk, S.config as styleconfig, S.css FROM " . $this->buttonsTable . " B LEFT JOIN " . $this->stylesTable . " S ON S.id = B.style WHERE B.id = %d", absint($id));

$results = $wpdb->get_results( $pSQL );

// render button DIV (or button url)
if ( count( $results ) > 0 ) {
	foreach ( $results as $key => $value ) {
		echo do_shortcode($this->renderer->renderButtonWithStyle($value->config, $value->styleconfig, $value->css, $value->atk, false, $mode_url, $mode_custom, $content));
	}
}

return;
