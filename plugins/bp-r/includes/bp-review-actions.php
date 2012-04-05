<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	'bp_get_review_slug()'	in 
		
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
 */
function check_voto($voto) 
{
	return (empty($voto) || $voto==0);
}

function check_text($tt,$ll) 
{
	$ll = 5;
	return (empty($text) || !(countchar($tt) < $ll) ); //!(isValidLength($text, $length)) );
}

function isValidLength($t, $l)
{
    return (count(explode(" " , $t)) < $l);
}

function countchar ($string) 
{ 
    $result = strlen ($string)  -   substr_count($string, ' '); 
	echo $result;  
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
	
	//
	if ( isset( $_POST['review-submit'] ) && bp_is_active( 'review' ) ) 	
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_review_new_review' );			
		
		//recupera i valori inviati dal FORM
		$content  			= $_POST['review-content'];				
		$title  			= $_POST['review-title'];				
		$voto_prezzo    	= $_POST['prezzo'];					
		$voto_servizio  	= $_POST['servizio'];	
		$voto_qualita		= $_POST['qualita'];
		$voto_puntualita	= $_POST['puntualita'];
		$voto_affidabilita	= $_POST['affidabilita'];
					
		//			
		if ( empty($content) || empty($title)) 	//if ( check_text($content,20) || check_text($title,5)) 
		//if ( check_text($content) || check_text($title)) 
		{
			bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
			
			// importante!!! - se fuori dall IF principale
			return;
		}	

		//
		if ( 	check_voto( $voto_prezzo ) 
				|| 	check_voto( $voto_qualita)
				|| 	check_voto( $voto_puntualita)
				||  check_voto( $voto_affidabilita)
				||  check_voto( $voto_servizio)
			) 	
		{
			bp_core_add_message( __( 'Assegna tutti i voti', 'reviews' ),'error' );
			
			// importante!!! - se fuori dall IF principale
			return;
		}	
		
		// funzione del FILE 'bp-review-functions.php' - se restituisce true e' OK!			
		// [I] [W] - ATTENZIONE: la funzione 'bp_review_send_review()' al momento restituisce sempre TRUE!!! -- controllo non funzionante!		
		$result = bp_review_send_review
		(	
			bp_displayed_user_id(), 
			bp_loggedin_user_id(), 
			
			$title, 
			$content, 
			
			array(
				'prezzo'		=> $voto_prezzo, 
				'servizio'		=> $voto_servizio,
				'qualita'		=> $voto_qualita,
				'puntualita'	=> $voto_puntualita,
				'affidabilita'	=> $voto_affidabilita
			)
		);																																						
					
		// [W] - ATTENZIONE: la funzione 'bp_review_send_review()' al momento restituisce sempre TRUE!!! -- controllo non funzionante!
		if($result)
		{				
			bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );
		}
		else 
		{
			//[W] - ATTENZIONE: non ci va mai qui!
			bp_core_add_message( __( 'Review non inviata', 'reviews' ) );			
		}	
			
		// fa il REDIRECT
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/my-reviews' );			
		
		//[ALT] - ma non può reindirizzarti alla scheda 'my-reviews' mi sa!
			//bp_core_redirect(wp_get_referer()); 																	
	}	
}

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'salva' );

?>