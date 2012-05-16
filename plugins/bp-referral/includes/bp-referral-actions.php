<?php

//----------------------------------------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------------------------------------

function invia_referral() 
{
	global $bp;
	
	if ( isset( $_POST['referral-submit'] ) 
		)//&& bp_is_active( 'example' ) ) 	//&& bp_is_active( 'referral' ) ) 	
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_ref_new_referral' );			

		//$title = $_POST['referral-title'];			
		
		$result = bp_ref_send_referral
		(	
				bp_displayed_user_id()
			, 	bp_loggedin_user_id()
			//, 
			//$title						
		);																																						
		
		if($result)
		{				
			bp_core_add_message( __( 'referral inviato correttamente', 'referrals' ) );
		}
		else 
		{
			//[W] - ATTENZIONE: non ci va mai qui!
			bp_core_add_message( __( 'referral non inviato', 'referrals' ) );			
		}	
			
		// fa il REDIRECT
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_referral_slug() . '/screen-one' );			
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-one' );			//EXAMPLE slug		- SCREEN 1
	
	}	
}

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'invia_referral' );

//----------------------------------------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------------------------------------


function modera_referral() 
{
	global $bp;
	
	if ( isset( $_POST['			referral-moderation			'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( '			bp_ref_referral_moderation			' );			

		//$title = $_POST['referral-title'];			
/*		
		$result = bp_ref_send_referral
		(	
				bp_displayed_user_id()
			, 	bp_loggedin_user_id()
			//, 
			//$title						
		);																																						
*/		
		if($result)
		{				
			bp_core_add_message( __( 'referral msg1		', 'referrals' ) );
		}
		else 
		{
			//[W] - ATTENZIONE: non ci va mai qui!
			bp_core_add_message( __( 'referral 	msg2	', 'referrals' ) );			
		}	
			
		// fa il REDIRECT
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_referral_slug() . '/screen-one' );			
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-one' );			//EXAMPLE slug		- SCREEN 1
	}	
}

add_action( 'bp_actions', 'modera_referral' );
?>