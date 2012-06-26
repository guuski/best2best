<?php
/*
Plugin Name: BP Better Friendship
Plugin URI: 
Description: Aggiunge la differenziazione del tipo di rapporto commerciale
Version: 1.0
Author: Giambattista Pisasale
Author URI: http://www.insolaria.it/?insoftware
Licence: GPLv3
Network: true
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

define( 'BPBF_VERSION'	  , '1.0' );
define( 'BPBF_TEXTDOMAIN' , 'bpbf');

define( 'BPBF_PLUGINDIR', 'wp-content/plugins' ); // Relative to ABSPATH.  For back compat.

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

/**
 * Loads BP Better Friendship files only if BuddyPress is present
 *
 * @package BP Better Directories
 * @since 1.0
 */
function bpbf_init() 
{
	if ( !function_exists( 'bp_is_active' ) || !bp_is_active( 'xprofile' ) )
		return;
	
	require( dirname( __FILE__ ) . '/bpbfriendship.php' );					//REQUIRE	
	$bpbf = new BP_better_friendship;												//OBJ

	
}

//ACTION
add_action( 'bp_include', 'bpbf_init' );

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	LOCALIZZAZIONE / TRADUZIONE 	(Action: init)
//--------------------------------------------------------------------------------------------------------------------------------------------------		

/**
 *
 */
function bpbf_load_my_textdomain()
{
	//load_plugin_textdomain( 'bpbd', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain(BPBF_TEXTDOMAIN, BPBF_PLUGINDIR.'/bp-better-friendship/languages','bp-better-friendship/languages');
	
	//load_plugin_textdomain(CSP_PO_TEXTDOMAIN, PLUGINDIR.'/codestyling-localization/languages','codestyling-localization/languages');
}
//ACTION
add_action( 'init', 'bpbf_load_my_textdomain');
?>
