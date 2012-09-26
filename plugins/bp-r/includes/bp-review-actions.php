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
	
	
	'bp_actions' --> 'invia_nuova_review()'
	
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

//------------------------------------------------------------------------------------------------------------------------------------
// INVIA Nuova REVIEW - non e' richiamata direttamente da NEssuno  ma viene richiamata perche' e' stata aggiunta con 'add_action' all HOOK: 'bp-actions'
//------------------------------------------------------------------------------------------------------------------------------------

//[?] se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'invia_nuova_review' );

/**
 *
 */
function invia_nuova_review() 
{
	global $bp;
	
	//
	if ( 		isset( $_POST['review-submit'] ) 
			&& 	bp_is_active( 'review' ) 
		) 	
	{		
		// [WPNONCE]
		check_admin_referer( 'new_review_action' );			
		
		//recupera i valori inviati dal FORM
		$content  				= $_POST['review-content'];				
		$title  				= $_POST['review-title'];				
		$tipologia_rapporto		= $_POST['tipologia_rapporto'];	 				
		
		//....consigliato....		
		
		$voto_prezzo    		= $_POST['prezzo'];					
		$voto_servizio  		= $_POST['servizio'];	
		$voto_qualita			= $_POST['qualita'];
		$voto_puntualita		= $_POST['puntualita'];
		$voto_affidabilita		= $_POST['affidabilita'];				
		
		$data_rapporto	    	= $_POST['datepicker'];				 
		$giudizio_review    	= $_POST['giudizio_review'];						
		$tipo_review_negativa 	= $_POST['tipo_review_negativa'];	 
		
		//....disclaimer....		
	
	
		if ( empty($title)) 			//empty
		{
			bp_core_add_message( __( 'Inserisci il titolo', 'reviews' ),'error' );						
			return;
		}		
	
		if (empty($content))	//empty							
		{
			bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );						
			return;
		}	
		
		if ( empty($tipologia_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica la tipologia del rapporto commerciale', 'reviews' ),'error' );					
			return;
		}	
		
		//.......consigliato........
		
		if ( 		check_voto( $voto_prezzo ) 
				|| 	check_voto( $voto_qualita)
				|| 	check_voto( $voto_puntualita)
				||  check_voto( $voto_affidabilita)
				||  check_voto( $voto_servizio)
			) 	
		{
			bp_core_add_message( __( 'Assegna tutti i voti', 'reviews' ),'error' );						
			return;
		}			
				
		if ( empty($data_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica la data rapporto commerciale', 'reviews' ),'error' );						
			return;
		}	
		
		//---------------------------------------------------------------------------------------------------------
		if ( empty($giudizio_review)) 	//empty
		{
			bp_core_add_message( __( 'Assegna un giudizio complessivo alla review', 'reviews' ),'error' );									
			return;
		}	
		else if ($giudizio_review != 'negativo')			//non è ncessario forse!
		{
			$tipo_review_negativa = "";											//$tipo_review_negativa 
		}
		
		if ($giudizio_review == 'negativo'  && $tipo_review_negativa = "") 	
		{
			bp_core_add_message( __( 'Specifica il tipo di review negativa', 'reviews' ),'error' );									
			return;
		}
		//---------------------------------------------------------------------------------------------------------
		
		
		// FUNCTION call ---> result var [vd FILE 'bp-review-functions.php']
		$review_sent_result = bp_review_send_review
		(	
			bp_displayed_user_id(), 
			bp_loggedin_user_id(), 			
			$title, 
			$content, 
			$giudizio_review,
			$data_rapporto,
			$tipologia_rapporto,																
			array(
				'prezzo'		=> $voto_prezzo, 
				'servizio'		=> $voto_servizio,
				'qualita'		=> $voto_qualita,
				'puntualita'	=> $voto_puntualita,
				'affidabilita'	=> $voto_affidabilita
			),			
			$tipo_review_negativa
		);																																						

		// result var <---							
		if($review_sent_result)
		{	
			if($tipo_review_negativa == "anonimo")
				bp_core_add_message( __( 'Review Negativa (anonima) inviata correttamente...in attesa di essere moderata', 'reviews' ) );
			else if ($tipo_review_negativa == "registrato")
				bp_core_add_message( __( 'Review Negativa (non anonima) inviata correttamente...in attesa di essere moderata', 'reviews' ) );
			else
				bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );			
		}
		else 
		{			
			bp_core_add_message( __( 'Review non inviata', 'reviews' ) );			
			
			//ERRORE nel salvataggio della Review!
			
			//LOG 2/2 (vd riga 162 di bp-review-functions)
		}	
			
		// fa il REDIRECT
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/my-reviews' );			
		//bp_core_redirect(wp_get_referer());  	//[ALT] - ma non pu� reindirizzarti alla scheda 'my-reviews' mi sa!																	
	}	
}

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
			
		// FUNCTION call (1)---> result var 			
		$post_status_result = bp_review_change_post_status($id_post, 'publish');
					
		// result var 1/2 <---			
		if($post_status_result)	
		{		
			//get META TAGs
			$tipo_review_negativa = get_post_meta($id_post, "tipo_review_negativa", true);
			$giudizio_review	  = get_post_meta($id_post, "giudizio_review", true);
			
		 //------------------------------------------------------------------------------------------------------------------------------------				
		 //
		 // TODO: test box
		 //
		 // if($giudizio_review == "negativo") 
		 // {  
		 //
		 //------------------------------------------------------------------------------------------------------------------------------------				

				if($tipo_review_negativa == "anonimo") 		
				{								
				// ----- 1) per le Review NEGATIVE del tipo "anonimo"  ----- 
					
					
					//-------------------- aggiorno AUTORE ----------------------------------------
					
					// AUTORE attuale
					//$obj_post 		 = get_post($id_post);			
					//$post_author_id  = $obj_post->post_author;					
					
					//------ parte 1 ------
					
					// AUTORE nuovo
					$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");
					$id_staff   = $user_staff->ID;										
					error_log("id_staff =>  ______".$id_staff);  //LOG
					$new_author_id = $id_staff;				
					
					// FUNCTION call (2) ---> result var 			
					$post_author_result = bp_review_change_post_author($id_post, $new_author_id);
					
					// result var <---		
					if(!$post_author_result) 
					{
						//break;
						
						//FLAG 
						
						//ERRORE msg "autore non cambiato"
					}					
					else
					{
						//------ parte 2 ------		(TODO: risolvere def. cancelladno '..reviewer_id...' dal plugin
						
						//set META TAGS - aggiorno il tag "reviewer" (AUTORE della review...coincide con post->author)
						update_post_meta($id_post, "bp_review_reviewer_id", $id_staff);
						
						//(*)
						$to_user_id   = get_post_meta($id_post, "bp_review_recipient_id", true); //---il destinatario della Revew
						//(*)
						//$from_user_id = $id_staff;

						// -------------------------------------- NOTIFICA, ATTIVITA, MAIL  -----------------------------------------------
/*						
						// - NOTIFICA - (1) - 
						$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");
						$id_staff   = $user_staff->ID;		
						//$from_user_id = $id_staff;	//l'autore è il MODERATORE					(*)
						bp_core_add_notification( $from_user_id,  		, $bp->review->slug, 'negative_review_accepted'); //	
															
*/						
						// - ACTIVITY - (1) - 										
						$to_user_link   = bp_core_get_userlink( $to_user_id ); //non posso cambiarlo sopra (*)
						//$from_user_link = bp_core_get_userlink( $from_user_id ); //non posso cambiarlo sopra (*)	//non serve se usa la parola "qualcuno"!
						bp_review_record_activity( array
						(
							'type' => 'rejected_terms',
							'action' => apply_filters( 'bp_review_new_review_anonima_activity_action', sprintf( __( 'Qualcuno ha scritto una review negativa per %s!', 'reviews' ), $to_user_link ), $to_user_link ),
							'item_id' => $to_user_id,
						) );
			
						// - MAIL - (1) - 								
							// - 1 - //do_action( 'bp_review_send_review', $to_user_id, $from_user_id);		
							// - 2 - //bp_review_send_review_notification($to_user_id, $from_user_id);		
							// - 3 -
							bp_review_send_review_anonima_notification($to_user_id, $from_user_id);		
					}		
				}		
				else  
				{
				// ----- 2) per le Review  NEGATIVE del tipo "registrato"  ----- 		

				  // -------------------------------------- NOTIFICA, ATTIVITA, MAIL  -----------------------------------------------
				  // NOTA BENE: questa parte è uguale a quella delle Review POSITIVE e NEUTRE (vd riga 159 di 'bp-review-functions') 
				  // ---------------------------------------------------------------------------------------------------------------
					
				  // - NOTIFICA - (2) - 
					
					//la notifica nel caso di Review NEGATIVA non cambia --- è sempre una generica "new_review"
					bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review' ); 	
															
				  // - ACTIVITY - (2) - 		
					
					//ricava gli utenti
					$to_user_id		= get_post_meta($id_post, "bp_review_recipient_id", true);
					$from_user_id   = get_post_meta($id_post, "bp_review_reviewer_id", true);					
					
					//ricava i link utenti
					$to_user_link   = bp_core_get_userlink( $to_user_id );
					$from_user_link = bp_core_get_userlink( $from_user_id );		
					bp_review_record_activity( array
					(
						'type' => 'rejected_terms',
						'action' => apply_filters( 'bp_review_new_review_activity_action', sprintf( __( '%s ha scritto una review per %s!', 'reviews' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
						'item_id' => $to_user_id,
					) );

				  // - MAIL - (2) - 						
						// - 1 - //do_action( 'bp_review_send_review', $to_user_id, $from_user_id);		
						// - 2 - 
						bp_review_send_review_notification($to_user_id, $from_user_id);								

				}		
			//------------------------------------------------------------------------------------------------------------------------------------				
			//}
			//------------------------------------------------------------------------------------------------------------------------------------				
		}	
		else
		{
			
			//lo stato del post è rimasto "pending". non è riuscito a cambiarlo!
						
			
		
		}			
			
		// result var 2/2 <---
		if($post_status_result)	
		{				
			bp_core_add_message( __( 'Review Negativa pubblicata','reviews' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'Si &egrave; verificato un errore durante l\'invio della Review... ', 'reviews' ) );			
		}	
			
		//SCREEN 4
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-four' );		
	}	
	/*	
	else 
	{
		//esce dalla funzione senza aver fatto niente
	}
	*/	
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
		$post_status_result = change_referral_post_status($id_post, 'trash');				
									
		// - NOTIFICA - 
		
		//ricava gli utenti
		$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");
		$id_staff   = $user_staff->ID;		
		$from_user_id = $id_staff;	//l'autore della notifica è il MODERATORE		
		$to_user_id	= get_post_meta($id_post, "bp_review_reviewer_id", true); //dest notifica è l'autore della Review (REVIEWER_ID) 
		//--- oppure AUTHOR post----
		
		bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'negative_review_refused' );  
		
		// - MAIL -
		
			// mandare una mail all'autore!	
		
		//-----------------------------------------------------------------------------------------------------------------
		
		// result var <---
		if($post_status_result)	
		{				
			bp_core_add_message( __( 'Review Negativa cestinata','reviews' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'Si &egrave; verificato un errore... ', 'reviews' ) );			
		}	
		
		//SCREEN 4
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-four' );				
	}	
}
?>