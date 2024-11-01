<?php

/**
 * WTH Travel Button renderer.
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/includes
 */
class WTH_Travel_Button_Renderer {


	public function __construct($arg) {

		$this->plugin_pref     		= $arg['plugin_pref'];

		$this->button_config_url	= $arg['button_config_url'];
		$this->style_config_url		= $arg['style_config_url'];
		$this->search_engine_url	= $arg['search_engine_url'];
		$this->button_load_url      = $arg['button_load_url'];

		global $wpdb;

		$this->db = new WTH_Travel_Button_DB();
		$this->buttonsTable = $wpdb->prefix . $this->plugin_pref . '_buttons';
		$this->stylesTable = $wpdb->prefix . $this->plugin_pref . '_styles';
	}

	/**
	 * Build button load url.
	 *
	 * @since    1.0.4
	 */
	public function buttonLoadUrl() {
		// prefetch Explore App from We Travel Hub
		$preferences = get_option( $this->plugin_pref );
		if ($preferences){
			if ($preferences['disable_prefetch'] == 'on'){
				return $this->button_load_url . '?prefetch=false';
			} else {
				return $this->button_load_url . '?prefetch=true';
			}
		}
		return $this->button_load_url;
	}

	/**
	 * Build style configuration url.
	 *
	 * @since    1.0.0
	 */
	public function configStyleUrl($cfg, $atk) {
		return $this->style_config_url . '&lang=' . __('en', 'the-travel-button');
	}

	/**
	 * Build button locations configuration url.
	 *
	 * @since    1.0.0
	 */
	public function configLocationsUrl($cfg, $atk) {
		return $this->button_config_url . '&lang=' . __('en', 'the-travel-button');
	}

	/**
	 * Build button locations configuration message.
	 *
	 * @since    1.0.0
	 */
	public function configLocationsMessage($cfg, $atk) {

		$config = array(
				'action' => 'initplugin',
				'lang' =>  __('en', 'the-travel-button')
		);

		if ($cfg && !empty($cfg)){
			$buttonModel = json_decode($cfg);
			$locations= array();
			if (!empty($buttonModel->location)){
				$locations['location']=$buttonModel->location;
			}
			if (!empty($buttonModel->locationList)){
				$locations['locationList']=$buttonModel->locationList;
			}
			if (!empty($buttonModel->locationAsOrigin)){
				$locations['locationAsOrigin']=$buttonModel->locationAsOrigin;
			}
			$config['cfg']= base64_encode(json_encode($locations));
		}

		if ($atk && !empty($atk)){
			$config['atk'] = $atk;
		}

		$apiKey = get_option($this->plugin_pref . '_apikey');

		if ($apiKey && !empty($apiKey)){
			$config['apiKey'] = $apiKey;
		}

		return json_encode($config);
	}

	/**
	 * Build button style configuration message.
	 *
	 * @since    1.0.0
	 */
	public function configStyleMessage($cfg, $atk) {

		$config = array(
				'action' => 'initplugin',
				'lang' =>  __('en', 'the-travel-button')
		);

		if ($cfg && !empty($cfg)){
			$config['cfg']= base64_encode($cfg);
		}

		if ($atk && !empty($atk)){
			$config['atk'] = $atk;
		}

		$apiKey = get_option($this->plugin_pref . '_apikey');

		if ($apiKey && !empty($apiKey)){
			$config['apiKey'] = $apiKey;
		}

		return json_encode($config);
	}


	/**
	 * Build style preview.
	 *
	 * @since    1.0.0
	 */
	public function renderStylePreview($cfg) {
		return $this->renderButton($cfg, null, null, true, null, null);
	}

	/**
	 * Build button preview.
	 *
	 * @since    1.0.0
	 */
	public function renderButtonPreview($cfg, $styleId) {

		if (!$styleId || empty($styleId)){
		  return '';
		}

		$style = $this->db->loadById($this->stylesTable, $styleId);

		if (!$style || empty($style->config)){
		 return '';
		}

		return $this->renderButtonWithStyle($cfg, $style->config, null, null, true, null, null, null);
	}

	/**
	 * Build button.
	 *
	 * @since    1.0.0
	 */
	public function renderButtonWithStyle($cfg, $styleCfg, $additionalCSS, $atkButton, $isPreview, $mode_url, $mode_custom, $content) {

		if (!$styleCfg || empty($styleCfg)){
		 return '';
		}

		$styleModel = json_decode($styleCfg, true);

		$atk = '';
		$preferences = get_option( $this->plugin_pref );
		if ($preferences){
			if($preferences['use_default_atk'] == 'on' || !$atkButton || empty($atkButton)){
				$atk = $preferences['atk'];
			} else {
			    $atk = $atkButton;
			}
		}

		if ( !$isPreview && (!$atk || empty($atk)) && $preferences['hide_buttons_no_atk'] == 'on'){
		 	return '';
		}

		// direct link mode render
		if ($mode_url && !empty($mode_url)){
			// validate supported url mode render
			$modes = explode("-", $mode_url);

			switch ( $modes[0] ) {
				case 'all';
				case 'dream';
				case 'go';
				case 'eat';
				case 'do';
					$location = '';
					$locationList = '';
					$locationAsOrigin = false;

					if ($cfg && !empty($cfg)){
						$buttonModel = json_decode($cfg);
						if (!empty($buttonModel->location)){
							$location = $buttonModel->location;
						}
						if (!empty($buttonModel->locationList)){
							$locationList = $buttonModel->locationList;
						}
						if (!empty($buttonModel->locationAsOrigin)){
							$locationAsOrigin = $buttonModel->locationAsOrigin;
						}
					}

					$href_url = $this->search_engine_url;

					if ($modes[0] != 'all'){
						$href_url .= '/search/' . $modes[0];
					} else {
						if (!empty($locationList)) {
						  $href_url .= '/search/multilocation';
						} else if (!empty($location)) {
						  $href_url .= '/search';
						}
					}

					$href_url .= '?atk=' . urlencode($atk);

					if (!empty($location)) {
					  // LOCATION
					  $href_url .= "&location=" . urlencode($location);
					} else if (!empty($locationList)) {
					  // LOCATION LIST
					  $href_url .= "&locationList=" . urlencode($locationList);
					}

					// LOCATION AS ORIGIN
					if ($locationAsOrigin){
					  $href_url .= "&locationAsOrigin=true";
					}

					// DISABLE ELEMENTS
					if (!$styleModel['elements']['accommodation']){
					  $href_url .= "&hth-no-accommodation=true";
					}
					if (!$styleModel['elements']['transport']){
					  $href_url .= "&hth-no-transport=true";
					}
					if (!$styleModel['elements']['restaurants']){
					  $href_url .= "&hth-no-restaurants=true";
					}
					if (!$styleModel['elements']['activities']){
					  $href_url .= "&hth-no-activities=true";
					}
					// sub-type
					if (count($modes) > 1){
						$href_url .= "&ao_type=" . $modes[1];
					}

					return $href_url;
				default;
					return '';
			}
		}

		if ($cfg && !empty($cfg)){
			$buttonModel = json_decode($cfg);
			if (!empty($buttonModel->location)){
				$styleModel['location'] = $buttonModel->location;
			}
			if (!empty($buttonModel->locationList)){
				$styleModel['locationList'] = $buttonModel->locationList;
			}
			if (!empty($buttonModel->locationAsOrigin)){
				$styleModel['locationAsOrigin'] = $buttonModel->locationAsOrigin;
			}
		}

		return WTH_Travel_Button_Renderer :: renderButton(json_encode($styleModel), $atk, $additionalCSS, $isPreview, $mode_custom, $content);
	}

	/**
	 * Build button.
	 *
	 * @since    1.0.0
	 */
	public static function renderButton($cfg, $atk, $additionalCSS, $isPreview, $mode_custom, $content) {

		if (!$cfg || empty($cfg)){
		 return '';
		}
		$hthButtonModel = json_decode($cfg);

		// custom mode will be enabled by configured style o by mode_custom attribute on shortcode
		// this allow to use WTH styled buttons with own style
		$enabledModeCustom = $hthButtonModel->type == "custom" || ($mode_custom && !empty($mode_custom));

		if ($enabledModeCustom && !$isPreview){
			// validate supported custom mode render
			$modes = explode("-", $mode_custom);

			switch ( $modes[0] ) {
				case 'dream';
				case 'go';
				case 'eat';
				case 'do';
					break;
				default;
					return '';
			}
		}

		// START DIV
        $divScript = "<div ";

		if ($enabledModeCustom){
			// force custom TYPE
			$divScript .= " hth-type=\"custom\"";

			if (!$isPreview){
				// OPTION CUSTOM
				$divScript .= "hth-option=\"$mode_custom\"";
			}

		} else {
			// TYPE
			$divScript .= " hth-type=\"$hthButtonModel->type\"";
		}

		//ADDITIONAL CSS CLASSES
        if ($isPreview){
         	if ($enabledModeCustom){
         	   // add a default preview style when mode custom enabled
         	   $divScript .= " class=\"hth-button wth-preview-custom-style\"";
         	} else {
         		$divScript .= " class=\"hth-button\"";
         	}
        } else {
         	$divScript .= " class=\"hth-button" . ( empty($additionalCSS) ? '':' ' . $additionalCSS ) . "\"";
        }

		// TRAVEL ELEMENTS
		$divScript .= (!$hthButtonModel->elements->accommodation ? " hth-no-accommodation" : '');
		$divScript .= (!$hthButtonModel->elements->transport ? " hth-no-transport" : '');
		$divScript .= (!$hthButtonModel->elements->restaurants ? " hth-no-restaurants" : '');
		$divScript .= (!$hthButtonModel->elements->activities ? " hth-no-activities" : '');

		if (!$enabledModeCustom){
			// COLORS
			if ($hthButtonModel->type == "original" ) {
			  $divScript .= " hth-theme=\"" . $hthButtonModel->colors->theme . "\"";
			}
			else if ($hthButtonModel->type == "go") {
			  $divScript .= " hth-color-type=\"" . $hthButtonModel->colors->type . "\"";
			  $divScript .= " hth-primary-color=\"" . $hthButtonModel->colors->primaryColor . "\"";
			  $divScript .= " hth-secondary-color=\"" . $hthButtonModel->colors->secondaryColor . "\"";
			  $divScript .= " hth-icon-color=\"" . $hthButtonModel->colors->iconColor . "\"";
			} else if ($hthButtonModel->type == "multiple" && $hthButtonModel->colors->customColors) {
			  $divScript .= ($hthButtonModel->colors->customColors ? " hth-custom-colors" : '');
			  $divScript .= " hth-color-type=\"" . $hthButtonModel->colors->type . "\"";
			  $divScript .= " hth-primary-color=\"" . $hthButtonModel->colors->primaryColor . "\"";
			  $divScript .= " hth-secondary-color=\"" . $hthButtonModel->colors->secondaryColor . "\"";
			  $divScript .= " hth-icon-color=\"" . $hthButtonModel->colors->iconColor . "\"";
			}
			// SHADOW
			if ($hthButtonModel->shadow){
				if (!$hthButtonModel->shadow->visible){
					$divScript .= " hth-no-shadow";
				} else {
					$divScript .= " hth-shadow-color=\"" . $hthButtonModel->shadow->color . "\"";
				}
			}
			// SIZE
			$divScript .= " hth-size=\"$hthButtonModel->size\"";
			// POSITION
			if ($hthButtonModel->type != "multiple") {
			  $divScript .= " hth-direction=\"" . $hthButtonModel->position->direction . "\"";
			} else {
			  $divScript .= " hth-orientation=\"" . $hthButtonModel->position->orientation . "\"";
			  $divScript .= " hth-separation=\"" . $hthButtonModel->position->separation . "\"";
			}
			if ($isPreview){
				$divScript .= " hth-position=\"fixed\"";
			} else {
				$divScript .= " hth-position=\"" . $hthButtonModel->position->position . "\"";
				if ($hthButtonModel->position->position == "float") {
				  $divScript .= " hth-float-left=\"" . $hthButtonModel->position->floatLeft . "\"";
				  $divScript .= " hth-float-bottom=\"" . $hthButtonModel->position->floatBottom . "\"";
				}
			}
		}
		// LOCATION
		if ($hthButtonModel->location) {
		  $divScript .= " hth-location=\"$hthButtonModel->location\"";
		}
		// LOCATIONLIST
		if ($hthButtonModel->locationList) {
		  $divScript .= " hth-location-list=\"$hthButtonModel->locationList\"";
		}
		// LOCATION AS ORIGIN
		$divScript .= (!empty($hthButtonModel->locationAsOrigin) ? " hth-location-as-origin" : '');
		// ATK
		if ($atk && ! empty($atk)) {
		  $divScript .= " hth-atk=\"$atk\"";
		}
		// Just a default content for preview custom style
		if ($enabledModeCustom && $isPreview){
	 		$divScript .= ' hth-option="dream"><p style="color: white">CUSTOM</p></div>';
	 		return $divScript;
		}
		if ($enabledModeCustom){
			// END DIV with content only available with custom style
			$divScript .= ">" . $content . "</div>";
		} else {
			// END DIV
			$divScript .= "></div>";
		}
		return $divScript;
	}
}
