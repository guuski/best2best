<?php
/*
Plugin Name: bp-referral - sk
*/


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
define( 'BP_REFERRAL_PLUGIN_DIR', dirname( __FILE__ ) );


		//CANCELLARE appena possible!
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
// vd BP-REFERRAL-LOADER all'inizio prima di ATTIVARE questa!!!!
//--------------------------------------------------------------------------------------------------------------------------------------------------		
/*
add_action( 'init', 'bp_referral_load_my_textdomain');


function bp_referral_load_my_textdomain()
{
	load_plugin_textdomain( 'referrals', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
*/

//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_head' , 'add_js_referral');					

function add_js_referral ()
{	
	//JS
	wp_enqueue_script('referral',  plugin_dir_url (__FILE__).'/includes/referral.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
}  

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
