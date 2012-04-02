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
 *
 *
 *
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
/*	
	if (		bp_is_current_component($bp->review->slug)
			//&&  bp_is_current_action('screen-two')													//SCREEN-TWO o CREATE
			//&&  bp_is_current_action('create')													// CREATE
			&&  bp_is_current_action('screen-one')													//SCREEN-ONE
			&&	!empty($_POST['review-submit'])
		)										
*/		
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_review_new_review' );			
		
		//recupera i valori inviati dal FORM
		$content  		= $_POST['review-content'];				//TESTO Review
		$title  		= $_POST['review-title'];				//TITOLO Review
			
		$voto_prezzo    = $_POST['prezzo'];						//PARAMETRI voto
		$voto_servizio  = $_POST['servizio'];	
		
		//...
		

		// [R] move ---> spostare questa CONDIZIONE al livello superiore! ---lo screen 'scrivi_review' non deve comparire proprio sul mio profilo!!!
		if ( bp_is_my_profile() ) 
		{			
			bp_core_add_message( __( 'non puoi mandare review a te stesso', 'reviews' ));			
		} 
		else 
		{
			// [I] change --> per ora lasciamo stare...supponiamo vada tutto bene!
			
			//
			if( 	empty( $content ) 
				|| 	empty( $voto_prezzo ) 
				|| 	empty( $voto_servizio ) 
				|| 	empty( $title ) 
			)
			{
			
				//
				//
				//
						
				if ( empty( $content ) ) 
				{
					bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
					bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-two' );				//screen TWO
				}	

				if ( empty( $voto_prezzo ) ) 	// non va! 
												// --1-- provo isset( $_POST[   ] )----??!
												// --2---o confronto col default value?
				{
					bp_core_add_message( __( 'Assegna un voto al prezzo', 'reviews' ),'error' );
					bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-two' );				//screen TWO
				}	
		
				if ( empty( $voto_servizio) )   // non va!
				{
					bp_core_add_message( __( 'Assegna un voto al servizio', 'reviews' ),'error' );
					bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-two' );				//screen TWO				
				}	
			}	
			
			//----------------------------------------------------------------------------------------------------------------------------------
			
			//funzione del FILE 'bp-review-functions.php' - se restituisce true � OK!									
			$result = bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id(), $title, $content, $voto_prezzo, $voto_servizio );					// [C] 	Rating																														
																															// [I] magari un array?! :D
						
			// [W] - ATTENZIONE: la funzione 'bp_review_send_review()' al momento restituisce sempre TRUE!!! -- controllo non funzionante!
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
	
		//---------REDIRECT------------
		//bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-two' );		//VECCHIO
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/my-reviews' );			//NUOVO -OK - ha funzionato
		//bp_core_redirect(wp_get_referer()); 		
		
	}	
}

//IMPORTANTE!
add_action( 'bp_actions', 'salva' );
?>