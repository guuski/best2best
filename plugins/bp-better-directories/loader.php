<?php
/*
Plugin Name: BP Better Directories - MOD Andrea
Plugin URI: http://github.com/boonebgorges/bp-better-directories
Description: Adds sophisticated search and filters to your BuddyPress member directory
Version: 1.0
Author: Boone B Gorges, Andrea Sangiorgio
Author URI: http://boonebgorges.com
Licence: GPLv3
Network: true
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

define( 'BPBD_VERSION'	  , '1.0' );
define( 'BPBD_TEXTDOMAIN' , 'bpbd');

define( 'BPBD_PLUGINDIR', 'wp-content/plugins' ); // Relative to ABSPATH.  For back compat.

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

/**
 * Loads BP Better Directories files only if BuddyPress is present
 *
 * @package BP Better Directories
 * @since 1.0
 */
function bpbd_init() 
{
	if ( !function_exists( 'bp_is_active' ) || !bp_is_active( 'xprofile' ) )
		return;
	
	require( dirname( __FILE__ ) . '/bpbd.php' );					//REQUIRE	
	$bpbd = new BPBD;												//OBJ

	if ( is_admin() ) 
	{
		require( dirname( __FILE__ ) . '/includes/admin.php' );		//REQUIRE		
		$bpbd_admin = new BPBD_Admin;								//OBJ
	}
}

//ACTION
add_action( 'bp_include', 'bpbd_init' );

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	LOCALIZZAZIONE / TRADUZIONE 	(Action: init)
//--------------------------------------------------------------------------------------------------------------------------------------------------		

/**
 *
 */
function bpbd_load_my_textdomain()
{
	//load_plugin_textdomain( 'bpbd', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain(BPBD_TEXTDOMAIN, BPBD_PLUGINDIR.'/bp-better-directories/languages','bp-better-directories/languages');
	
	//load_plugin_textdomain(CSP_PO_TEXTDOMAIN, PLUGINDIR.'/codestyling-localization/languages','codestyling-localization/languages');
}
//ACTION
add_action( 'init', 'bpbd_load_my_textdomain');
?>
