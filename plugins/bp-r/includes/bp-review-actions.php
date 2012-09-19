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
		
		$giudizio_review    = $_POST['giudizio_review'];			
		$data_rapporto	    = $_POST['datepicker'];				 
		$tipologia_rapporto	= $_POST['tipologia'];	 
		
		$tipo_review_negativa = $_POST['tipo_review_negativa'];	 
					
		//			
		if ( empty($content) || empty($title)) 	//if ( check_text($content,20) || check_text($title,5)) 
		//if ( check_text($content) || check_text($title)) 
		{
			bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
			
			// importante!!! - se fuori dall IF principale
			return;
		}	
		
		if ( empty($giudizio_review)) 	//empty
		{
			bp_core_add_message( __( 'Assegna un giudizio alla review', 'reviews' ),'error' );						
			//bp_core_add_message( __( $giudizio_review, 'reviews' ),'error' );						
			return;
		}	
		else if ($giudizio_review != 'negativo')
		{
			$tipo_review_negativa = "";
		}
		
		if ( empty($data_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica la data rapporto commerciale', 'reviews' ),'error' );						
			return;
		}	
		
		if ( empty($tipologia_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica la tipologia del rapporto commerciale', 'reviews' ),'error' );					
			return;
		}			

		//
		if ( 		check_voto( $voto_prezzo ) 
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
			$giudizio_review,
			$data_rapporto,
			$tipologia_rapporto,
			
			////////////////////////						
			$tipo_review_negativa,
			////////////////////////
			
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
			if($tipo_review_negativa == "anonimo")
				bp_core_add_message( __( 'Review NEGATIVA anonima inviata correttamente...in attesa di essere moderata', 'reviews' ) );
			else if ($tipo_review_negativa == "registrato")
				bp_core_add_message( __( 'Review NEGATIVA inviata correttamente...in attesa di essere moderata', 'reviews' ) );
			else
				bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );			
		}
		else 
		{
			//[W] - ATTENZIONE: non ci va mai qui!
			bp_core_add_message( __( 'Review non inviata', 'reviews' ) );			
		}	
			
		// fa il REDIRECT
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/my-reviews' );			
		
		//[ALT] - ma non pu� reindirizzarti alla scheda 'my-reviews' mi sa!
			//bp_core_redirect(wp_get_referer()); 																	
	}	
}

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'salva' );


/**
 *
 *
 *
 */
 /*
function show_all_reviews() 
{
	global $bp;
	
	//
	if ( isset( $_POST['review-show-all-reviews'] ) && bp_is_active( 'review' ) ) 	
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_review_show_all_reviews' );			
		
		//recupera i valori inviati dal FORM
		$content  			= $_POST['  '];				
		
			
		// fa il REDIRECT
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() .		 '/my-reviews' );			
		
		//[ALT] - ma non pu� reindirizzarti alla scheda 'my-reviews' mi sa!
			//bp_core_redirect(wp_get_referer()); 																	
	}	
}

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'show_all_reviews' );


*/


//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	ACCETTA review Negativa
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_actions', 'accetta_review_negativa' );

/**
 *
 */
function accetta_review_negativa() 
{
	global $bp;
	
	if ( isset( $_POST['accetta-review-negativa'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( 'accetta-review-negativa' );			

		// [POST_vars]
		$id_post = $_POST['id-post'];					
			
		// FUNCTION call 			
		$result = bp_review_change_post_status($id_post, 'publish');
		
		//--------------------------------------------------------------------------------------------------------
		
		$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");
		$id_staff = $user_staff->ID;
		error_log("id_staff =>  ______".$id_staff);
		
		//aggiorno autore
		$my_post = array();
		$my_post['post_author'] =$id_staff;
		wp_update_post( $my_post );
		
		//aggiorno reviewer meta
		update_post_meta($id_post, "bp_review_reviewer_id", $id_staff);
		
		//TODO ------ cambiare il reviewer id (post-meta)
		//------ inviare notifiche e mail
		$to_user_id = get_post_meta($id_post, "bp_review_recipient_id", true);
		$from_user_id = $id_staff;
		
		bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review' );
		
		$to_user_link = bp_core_get_userlink( $to_user_id );
		$from_user_link = bp_core_get_userlink( $from_user_id );
		
		//
		bp_review_record_activity( array
				(
						'type' => 'rejected_terms',
						'action' => apply_filters( 'bp_review_new_review_activity_action', sprintf( __( '%s ha scritto una review per %s!', 'reviews' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
						'item_id' => $to_user_id,
				) );
		
		/* We'll use this do_action call to send the email notification. See bp-example-notifications.php */
		do_action( 'bp_review_send_review', $to_user_id, $from_user_id);
		
		//--------------------------------------------------------------------------------------------------------
		
		if($result)	
		{				
			bp_core_add_message( __( 'Review NEGATIVA pubblicata','reviews' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'Si &egrave; verificato un errore durante l\'invio della Review... ', 'review' ) );			
		}	
		
		//SCREEN 4
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-four' );		
	}	
}



//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	RIFIUTA Review Negativa
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_actions', 'rifiuta_review_negativa' );

/**
 *
 */
function rifiuta_review_negativa() 
{
	global $bp;
	
	if ( isset( $_POST['rifiuta-review-negativa'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( 'rifiuta-review-negativa');			

		// [POST_vars]
		$id_post = $_POST['id-post'];		

		// FUNCTION call 
		$result = change_referral_post_status($id_post, 'trash');		
		
		if($result)	
		{				
			bp_core_add_message( __( 'Review NEGATIVA cestinata','reviews' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'Si &egrave; verificato un errore... ', 'review' ) );			
		}	
		
		//SCREEN 4
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-four' );				
	}	
}
?>