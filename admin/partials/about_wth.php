<?php
/**
 * About view
 *
 * @since             1.0.0
 * @copyright   	  Copyright (c) 2021, We Travel Hub, S.L.
 * @author     		  We Travel Hub <it@wetravelhub.com>
 * @license			  http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package    WTH_Travel_Button
 * @subpackage WTH_Travel_Button/admin/partials
 */

$social = array(
	"twitter" =>  'https://twitter.com/WeTravelHub',
	"facebook" =>  'https://www.facebook.com/wetravelhub',
	"instagram" =>  'https://www.instagram.com/wetravelhub',
	"linkedin" =>  'https://www.linkedin.com/company/wetravelhub',
	"youtube" =>  'https://www.youtube.com/channel/UCzaP-RlfI_qR9zLj69jtTgQ'
);

?>
<div id="modal-about" class="hwt-scoped" hwt-modal>
    <div class="hwt-modal-dialog">
       <button class="hwt-modal-close-outside" type="button" hwt-close></button>
        <div class="hwt-modal-header">
			<h2 class="hwt-modal-title"><img data-src="<?php echo $this->plugin_url; ?>resources/logo-wth.png" class="wth-logo"  alt="" hwt-img>
			 <?php _e( 'The Travel Button®', 'the-travel-button' ); ?></h2>
        </div>
		<div class="hwt-width-auto@m hwt-modal-body hwt-padding-remove-bottom">
			<ul class="hwt-tab" hwt-tab="connect: #component-tab; animation: hwt-animation-fade">
				<li><a href="#"><?php _e( 'About', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><?php _e( 'Copyright', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><?php _e( 'License', 'the-travel-button' ); ?></a></li>
				<li><a href="#"><?php _e( 'Release notes', 'the-travel-button' ); ?></a></li>
			</ul>
		</div>
        <div class="hwt-modal-body" hwt-overflow-auto>
        	<ul id="component-tab" class="hwt-switcher" >
        		<li>
        			<div class="hwt-position-relative hwt-visible-toggle hwt-light" tabindex="-1" hwt-slideshow="autoplay: true">

                        <ul class="hwt-slideshow-items">
                            <li>
                                <img src="<?php echo $this->plugin_url; ?>resources/screenshot-1.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Intuitive editor to customize your travel buttons', 'the-travel-button' ); ?></p>
								</div>
                            </li>
                            <li>
                                <img src="<?php echo $this->plugin_url; ?>resources/screenshot-2.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Link travel buttons to locations', 'the-travel-button' ); ?></p>
								</div>
                            </li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-3.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( "Add travel buttons adapted to your website's style", 'the-travel-button' ); ?></p>
								</div>
							</li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-4.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( "Don't waste time! Change travel button styles without re-publishing your posts", 'the-travel-button' ); ?></p>
								</div>
							</li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-5.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Accommodation', 'the-travel-button' ); ?></p>
								</div>
							</li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-6.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Transport', 'the-travel-button' ); ?></p>
								</div>
							</li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-7.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Restaurants', 'the-travel-button' ); ?></p>
								</div>
							</li>
							<li>
								<img src="<?php echo $this->plugin_url; ?>resources/screenshot-8.png" alt="" hwt-cover>
								<div class="hwt-overlay-small hwt-overlay-primary hwt-position-bottom hwt-text-center hwt-dark">
									<p class="hwt-margin-remove"><?php _e( 'Activities', 'the-travel-button' ); ?></p>
								</div>
							</li>
                        </ul>

                        <a class="hwt-position-center-left hwt-position-small hwt-hidden-hover" href="#" hwt-slidenav-previous hwt-slideshow-item="previous"></a>
                        <a class="hwt-position-center-right hwt-position-small hwt-hidden-hover" href="#" hwt-slidenav-next hwt-slideshow-item="next"></a>

                    </div>
					<p><?php _e( "With our plugin for WordPress, you can easily customize and manage different travel buttons to use in your posts.", 'the-travel-button' ); ?></p>
					<p><?php _e( "The Travel Button® helps you monetize your travel content effectively while giving your visitors access to a global tourism and travel-related services search engine. It is an elegant advertising solution, fully adaptable to your website's design, with customizable style and placement.", 'the-travel-button' ); ?></p>
					<p><?php _e( "It saves visitors time when, inspired by the content you have published about a destination, they want to find useful information to plan a trip. With a single click and without leaving your website.", 'the-travel-button' ); ?></p>
				</li>
				<li>
					<pre>
						<?php echo esc_html(file_get_contents($this->plugin_dir . 'COPYRIGHT.txt')); ?>
					</pre>
				</li>
				<li>
					<pre>
						<?php  echo esc_html(file_get_contents($this->plugin_dir . 'LICENSE.txt')); ?>
					</pre>
				</li>
				<li>
					<pre>
						<?php  echo esc_html(file_get_contents($this->plugin_dir . 'RELEASE_NOTES.txt')); ?>
					</pre>
				</li>
        </div>
        <div class="hwt-modal-footer hwt-text-right">
			<div class="hwt-iconnav hwt-flex-center">
				<?php foreach ($social as $icon => $url) :?>
				<a href="<?php echo $url; ?>" target="_blank"><span class="hwt-icon-button hwt-margin-small-right" hwt-icon="<?php echo $icon; ?>"></span></a>
				<?php endforeach;?>
			</div>
        </div>
    </div>
</div>
<script>
'use strict';
function hideMoreTab(){
	hwtUIkit.dropdown('#help_tab').hide(0);
}
function showAbout(){
	hideMoreTab();
	hwtUIkit.modal("#modal-about").show();
}
</script>
