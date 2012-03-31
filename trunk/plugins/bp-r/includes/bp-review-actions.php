<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	'bp_is_review_component()' 	in 'bp-review-classes()'
	'bp_get_review_slug()'		in 
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_is_current_action()
	bp_is_action_variable()
	bp_core_add_message()
	bp_core_redirect()	
	bp_is_my_profile()
	bp_displayed_user_domain()
	bp_displayed_user_id()
	bp_loggedin_user_id()
	
	
	'bp_actions' --> 'salva()'
	
-----------------------------------------
global $bp
-----------------------------------------


*/

/**
 *	NOTA BENE: 
 *  
 *  non � richiamata direttamente da NEssuno
 *  ma viene richiamata perch� � stata aggiunta 
 *  con 'add_action' all HOOK: 'bp-actions'
 *
 */
function salva() 
{
	global $bp;
	
	if ( isset( $_POST['review-submit'] ) && bp_is_active( 'review' ) ) 
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_review_new_review' );			
		
		$content = $_POST['review-content'];		

		if ( bp_is_my_profile() ) 
		{			
			bp_core_add_message( __( 'non puoi mandare review a te stesso', 'reviews' ));			
		} 
		else 
		{
			if ( empty( $content ) ) 
			{
				bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
				bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );	//screen one		
			}	
			
			//----------------------------------------------------------------------------------------------------------------------------------
			//funzione del FILE 'bp-review-functions.php' - se restituisce true � OK!									
			$result = bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id(), $content );			
						
			// ATTENZIONE: la funzione 'bp_review_send_review()' al momento restituisce sempre TRUE!!! -- controllo non funzionante!
			if($result)
			{				
				bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );
			}
			else 
			{
				//ATTENZIONE: non ci va mai qui!
				bp_core_add_message( __( 'Review non inviata', 'reviews' ) );			
			}	
			//----------------------------------------------------------------------------------------------------------------------------------	
		}

		//REDIRECT su screen-one
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );				//screen one		
	}	
}

//IMPORTANTE!
add_action( 'bp_actions', 'salva' );
?>