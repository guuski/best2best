<?php
/*
Plugin Name: bp-referral - sk
*/


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
define( 'BP_REFERRAL_PLUGIN_DIR', dirname( __FILE__ ) );

		define( 'BP_EXAMPLE_PLUGIN_DIR', dirname( __FILE__ ) );


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
function bp_referral_init() 
{
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-referral-loader.php' );
}
add_action( 'bp_include', 'bp_referral_init' );



//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	---> SPOSTA nel file 'bp-referral-functions.php'
//--------------------------------------------------------------------------------------------------------------------------------------------------		

//can user ask for a referral
function referral_current_user_can_write()
{
	$can_write=false;
	 
	if(is_user_logged_in()&&!bp_is_my_profile()
		//	&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id())
		)
		$can_write=true;

	return apply_filters('bp_referral_can_user_write',$can_write);
}

?>
