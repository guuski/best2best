<?php 
/**
Plugin Name: bp-wp-profile-sync
Plugin URI: 
Description: 
Version: 0.1
Author: Andrea Sangiorgio
License:GPL2
**/

function bp_wpps_init() 
{
	require( dirname( __FILE__ ) . '/bp-wp-profile-sync.php' );
}
add_action( 'bp_include', 'bp_wpps_init' );

?>