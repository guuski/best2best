<?php
/*
Plugin Name: bp-referral - sk
*/


define( 'BP_REFERRAL_PLUGIN_DIR', dirname( __FILE__ ) );

		define( 'BP_EXAMPLE_PLUGIN_DIR', dirname( __FILE__ ) );


function bp_referral_init() 
{
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-referral-loader.php' );
}
add_action( 'bp_include', 'bp_referral_init' );

?>
