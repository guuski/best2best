<?php
/*
Plugin Name: bp-referral
Plugin URI:
Description: 
Version: 0.0.1
Revision Date: MMMM DD, YYYY
Requires at least: 
Tested up to: 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: 
Author URI:
Network: true
*/


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
	
//contiene il path alla cartella del plugin 
define( 'BP_REFERRAL_PLUGIN_DIR', dirname( __FILE__ ) );

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'bp_include', 'bp_ref_init' );

/**
 * Carica il plguin solo se BuddyPress e' presente.
 * 
 */
function bp_ref_init() 
{
	// poiche' il plugin usa BP_Component, richiede la versione di BP 1.5 o successiva.
	if ( version_compare( BP_VERSION, '1.5', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-referral-loader.php' );
}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'init', 'bp_ref_load_my_textdomain');


/**
 *
 * @see http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function bp_ref_load_my_textdomain()
{
	load_plugin_textdomain( 'referral', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
add_action( 'wp_print_scripts'  		, 'bp_ref_add_css');					

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function bp_ref_add_css()																					
{
	//CSS
	wp_enqueue_style ('referral',  plugin_dir_url (__FILE__).'/includes/referral.css');
}  


//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_head' , 'bp_ref_add_js');					

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */
function bp_ref_add_js()
{		
	//JS
	wp_enqueue_script('referral',  plugin_dir_url (__FILE__).'/includes/referral.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
}  


?>