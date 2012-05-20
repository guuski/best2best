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
Author: Andrea Sangiorgio
Author URI:
Network: true
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// COSTANTI
//--------------------------------------------------------------------------------------------------------------------------------------------------		
		
define( 'BP_EXAMPLE_PLUGIN_DIR', dirname( __FILE__ ) );										//EXAMPLE --> REFERRAL
//define( 'BP_REFERRAL_PLUGIN_DIR', dirname( __FILE__ ) );


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
add_action( 'bp_include', 'bp_referral_init' );

function bp_referral_init() 
{
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-referral-loader.php' );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	LOCALIZZAZIONE / TRADUZIONE 
//--------------------------------------------------------------------------------------------------------------------------------------------------		
add_action( 'init', 'bp_referral_load_my_textdomain');

function bp_referral_load_my_textdomain()
{
	load_plugin_textdomain( 'referrals', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

//-----------------------------------------------------------------------------------------------------------------------------------------------
//	JAVASCRIPT
//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_head' , 'add_js_referral');					

function add_js_referral ()
{		
	wp_enqueue_script('referral',  plugin_dir_url (__FILE__).'/includes/referral.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
}  

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	CSS	
//--------------------------------------------------------------------------------------------------------------------------------------------------		
add_action( 'wp_print_scripts'  		, 'add_css_referral');					

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function add_css_referral()																						//---usa il metodo add_JS
{
	wp_enqueue_style ('referral',  plugin_dir_url (__FILE__).'/includes/referral.css');		
}  

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	---> SPOSTA nel file 'bp-referral-functions.php'	(non ha ACTION!)
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

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// 	IMPORTANTE 
//
//	il link! ---> example/screen-two			SCREEN-TWO  
//--------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_member_header_actions'	, 'add_referral_button',1);				

/**	 
 * 	
 *																								//EXAMPLE --> REFERRAL
 */
function add_referral_button()																	
{
	if(referral_current_user_can_write())
	{
		echo '
		<div class = "add-referrals" >
		<a
		class = "add-referrals button"
		title = "Chiedi un Referral all\'utente."
		href="'.bp_get_displayed_user_link().'example/screen-two#user-activity"											 
		>
		'.__('Add Referral','referrals').'
		</a>
		</div>';
	}
}
?>
