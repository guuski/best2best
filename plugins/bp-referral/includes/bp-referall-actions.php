<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
			
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

-----------------------------------------
global $bp
-----------------------------------------

*/


/**
 *
 *
 *
 *	NOTA BENE: 
 *  
 *  non e' richiamata direttamente da NEssuno
 *  ma viene richiamata perche' e' stata aggiunta 
 *  con 'add_action' all HOOK: 'bp-actions'
 *
 */
function salva() 
{
	global $bp;
	
	if ( isset( $_POST['referral-submit'] ) && bp_is_active( 'referral' ) ) 	
	{		
		// [WPNONCE]
			//check_admin_referer( 'bp_ref_new_referral' );			

	/*
		$result = bp_ref_send_referral
		(	
			bp_displayed_user_id(), 
			bp_loggedin_user_id(), 
			.....
			
		);																																						
			
		
		if($result)
		{				
			bp_core_add_message( __( 'referral inviato correttamente', 'referral' ) );
		}
		else 
		{
			//[W] - ATTENZIONE: non ci va mai qui!
			bp_core_add_message( __( 'referral non inviato', 'referral' ) );			
		}	
			
		// fa il REDIRECT
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_referral_slug() . '/my-rev' );			
	*/											
	}	
}

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'salva' );

?>