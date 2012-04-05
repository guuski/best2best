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

function check_voto($voto) {
	return (empty($voto) || $voto==0);
}

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
		$voto_qualita	= $_POST['qualita'];
		$voto_puntualita= $_POST['puntualita'];
		$voto_affidabilita=$_POST['affidabilita'];

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
				|| 	check_voto( $voto_prezzo ) 
				|| 	check_voto( $voto_servizio ) 
				|| 	empty( $title )
				|| 	check_voto( $voto_qualita)
				|| 	check_voto( $voto_puntualita)
				|| 	check_voto( $voto_affidabilita)
					
			)
			{
						
				if ( empty( $content ) || 	empty( $title )) 
				{
					bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
				}	

				if ( check_voto( $voto_prezzo ) 
						|| 	check_voto( $voto_qualita)
						|| 	check_voto( $voto_puntualita)
						|| check_voto( $voto_affidabilita)
						|| check_voto( $voto_servizio)) 	{
					bp_core_add_message( __( 'Assegna tutti i voti', 'reviews' ),'error' );
				}	
				return;
			}	
			
			//----------------------------------------------------------------------------------------------------------------------------------
			
			//funzione del FILE 'bp-review-functions.php' - se restituisce true e' OK!									
			$result = bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id(), $title, $content, 
					array('prezzo'=>$voto_prezzo, 
						'servizio'=>$voto_servizio,
						'qualita'=>$voto_qualita,
						'puntualita'=>$voto_puntualita,
						'affidabilita'=>$voto_affidabilita)
			);																																			
			// [C] 	Rating													// [I] magari un array?! :D
						
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

// se stacco l'action funziona lo stesso secondo me!


?>