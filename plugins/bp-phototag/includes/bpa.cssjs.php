<?php

/**
 * NOTE: You should always use the wp_enqueue_script() and wp_enqueue_style() functions to include
 * Javascript and CSS files.
 */

/**
 * bp_album_add_js()
 *
 * This function will enqueue the components Javascript file, so that you can make
 * use of any Javascript you bundle with your component within your interface screens.
 * 
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_add_js() {
    
	global $bp;

	if ( $bp->current_component == $bp->album->slug )
		wp_enqueue_script( 'bp-phototag-js', WP_PLUGIN_URL .'/bp-phototag/includes/js/general.js' );
}
// add_action( 'template_redirect', 'bp_album_add_js', 1 );

/**
 * bp_album_add_css()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_add_css() {
    
	global $bp;

		wp_enqueue_style( 'bp-phototag-css', WP_PLUGIN_URL .'/bp-phototag/includes/css/general.css' );
		wp_print_styles();	
}

add_action( 'wp_head', 'bp_album_add_css' );

?>