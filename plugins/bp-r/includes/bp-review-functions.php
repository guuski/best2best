<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
	
	(137) 	$review = new Review( $db_args );											//istanzia oggetto della classe 'Review'
	
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

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// VALIDAZIONE 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 *
 *
 */
function bp_review_check_voto($voto) 
{
	return (empty($voto) || $voto==0);
}

/*
function check_text($tt,$ll) 
{
	$ll = 5;
	return (empty($text) || !(countchar($tt) < $ll) ); //!(isValidLength($text, $length)) );
}
*/

/*
function isValidLength($t, $l)
{
    return (count(explode(" " , $t)) < $l);
}
*/

/*
function countchar ($string) 
{ 
    $result = strlen ($string)  -   substr_count($string, ' '); 
	echo $result;  
} 
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (usata dal FORM)
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

function bp_review_form_action() 
{
	echo site_url() . remove_query_arg( array('s','snptcat','n') );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (usata dal FORM)
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------		

function bp_review_form_action_screen_two()
{
    global $bp;

	echo apply_filters('bp_review_form_action_screen_two',  $bp->displayed_user->domain.$bp->review->slug."/screen-two/"); 		//la S  //CREATE
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (usata dal FORM)  viene chiamata dal metodo '    ()' (in 'bp-review-actions.php') dopo che e' stato inviato  il FORM dello SCREEN 4
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

function bp_review_form_action_screen_four()			 																						
{
    global $bp;
		
	// SCREEN 4		
	echo apply_filters('bp_reviews_post_form_action_SCREEN_4',  $bp->displayed_user->domain.$bp->review->slug."/screen-four/"); 		
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// SEND Review 
//
// viene chiamata dal metodo 'salva()' (in 'bp-review-actions.php') dopo che e' stato inviato il FORM]
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 * Manda un messaggio di review all'utente.
 *
 * ----- parte 1 --------
 *
 *
 * ----- parte 2 --------
 *
 *  - Registra una notifica per l'utente con il "notifications menu" 
 *  - Registra un "activity stream item" col seguente testo: "Utente 1 ha inviato una review a Utente 2".
 *  - manda una MAIL all utente.
 *
 * @see http://codex.wordpress.org/Function_Reference/check_admin_referer
 * @see http://codex.wordpress.org/Function_Reference/delete_user_meta
 * @see http://codex.wordpress.org/Function_Reference/maybe_unserialize
 *
 */
function bp_review_send_review	( 
									$to_user_id, $from_user_id, 
									$title, $content, 
									$giudizio_review, $data_rapporto, $tipologia_rapporto, 								
									$voti,
									$tipo_review_negativa,
									$anonymous_reviewer_id									
								)
{
	global $bp;
	
	//TODO: riscrivi da qua fino a $review = new Review( $db_args );
	delete_user_meta( $to_user_id, 'reviews' );	    //cancella il campo 'reviews' associato  all'utente di destinazione! ---> ma picchi? - cmq non si puo' staccare!			
	$existing_reviews = maybe_unserialize( get_user_meta( $to_user_id, 'reviews', true ) );	
		
	if ( !in_array( $from_user_id, (array)$existing_reviews ) ) 
	{
		$existing_reviews[] = (int)$from_user_id;		
		update_user_meta( $to_user_id, 'reviews', serialize( $existing_reviews ) );				
		$db_args = array
		(
			'recipient_id'  => (int)$to_user_id,
			'reviewer_id' 	=> (int)$from_user_id
		);

		//NEW
		$review = new Review( $db_args );		//istanzia oggetto della CLASSE 'Review'		
			
		//Save
		$review_saved_result = $review->save( $title, $content, $giudizio_review, $data_rapporto, $tipologia_rapporto, $voti,
											  $tipo_review_negativa,
											  $anonymous_reviewer_id
											);																							
	}
	//TODO: va esteso forse fino a comprendere la 2 parte che al momento rimane fuori
	
	//---------------------------------------------- 2 parte ---------------------------------------------------------------
	
	//Se la Review � stata salvata correttamente....
	if($review_saved_result) 							
	{			

		// ------ (1) ------ per le Review NEGATIVE del tipo "anonimo" 	(sono in MODERAZIONE)
		if($tipo_review_negativa == "anonimo") 		 
		{		
			//TODO: evidata duplicazione codice...vd ramo 2 if
			
			// - NOTIFICA - (1) - 
				
				//---------------------------------------------------------------------------------------
				//notifica all'autore che la sua review � in MODERAZIONE!					
				$new_from_user_id = $to_user_id;    						//inverti AUTORE e DESTINATARIO
				$new_to_user_id   = $from_user_id;
				bp_core_add_notification( $new_from_user_id, $new_to_user_id, $bp->review->slug, 'new_negative_review_sent' ); 	//new_from_user_id //new_to_user_id													
														
				//notifica allo staff MODERATORE ---- ($to_user_id --> id_staff   )
				$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");			//TODO usa costante/funzione per nome utente Staff Best2best
				$id_staff   = $user_staff->ID;		
				$to_user_id = $id_staff;
				bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review_moderation_request' ); 	//id_staff													
				
				//notific al Destinatario della review ---> NO! perch� � in MODERAZIONE						
				//---------------------------------------------------------------------------------------
				
				
			// - ACTIVITY - (1) -						
				//NO! perch� � in MODERAZIONE						
			
			// - MAIL - (1) - 
					
				//MAIL di notifica allo staff MODERATORE --- c'� una review NEGATIVA da moderare!
					//bp_review_send_review_notification($to_user_id, $from_user_id);								//TODO usa costante/funzione per nome utente Staff Best2best			
					
				// non c'� bisogno di mandare una mail all'autore!	
		}
		else if ($tipo_review_negativa == "registrato")
		{
		// ------ (2) ------ per le Review NEGATIVE del tipo "registrato" (sono in MODERAZIONE)
		
			//TODO: evidata duplicazione codice...vd ramo 1 if
			
			// - NOTIFICA - (2) - 
			
				//---------------------------------------------------------------------------------------
				//notifica all'autore che la sua review � in MODERAZIONE!					
				$new_from_user_id = $to_user_id;    						//inverti AUTORE e DESTINATARIO
				$new_to_user_id   = $from_user_id;
				bp_core_add_notification( $new_from_user_id, $new_to_user_id, $bp->review->slug, 'new_negative_review_sent' ); 	//new_from_user_id //new_to_user_id													
															
				//notifica allo staff MODERATORE ---- ($to_user_id --> id_staff   )
				$user_staff = get_user_by("login", "Staff-Recensioni-Best2Best");				//TODO usa costante/funzione per nome utente Staff Best2best
				$id_staff   = $user_staff->ID;		
				$to_user_id = $id_staff;
				bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review_moderation_request' ); 	//id_staff													
				
				//notific al Destinatario della review ---> NO! perch� � in MODERAZIONE						
				
				//---------------------------------------------------------------------------------------				
				
			// - ACTIVITY - (2) ---> NO! perch� � in MODERAZIONE						
			
			// - MAIL - (2) - 
				
				//MAIL di notifica allo staff MODERATORE --- c'� una review NEGATIVA da moderare!					
					//bp_review_send_review_notification($to_user_id, $from_user_id,   ------- swicht oggetto msg-------);								
			
				// non c'� bisogno di mandare una mail all'autore!
		}
		else 
		{
		// ------ (3) ------ per le Review POSITIVE e NEUTRE		

			// - NOTIFICA - (3) - 
			bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review' ); 										
		
			// - ACTIVITY - (3) - 
			$to_user_link   = bp_core_get_userlink( $to_user_id );
			$from_user_link = bp_core_get_userlink( $from_user_id );									
			bp_review_record_activity( array
			(
				'type' => 'rejected_terms',
				'action' => apply_filters( 'bp_review_new_review_activity_action', sprintf( __( '%s ha scritto una review per %s!', 'reviews' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
				'item_id' => $to_user_id,
			) );			
						
			// - MAIL - (3) - 				
			do_action( 'bp_review_send_review', $to_user_id, $from_user_id); // DO_ACTION -- We'll use this do_action call to send the email notification. See bp-review-notifications.php 																	
		}
	}
	else
	{
		//ERRORE nel salvataggio della la Review!
		
		//LOG 1/2 (vd riga 178 di bp-review-functions)
	}
	
	//RETURN	
	return $review_saved_result;
}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// SEND Review NEGATIVA
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ACCETTA Review NEGATIVA
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// RIFIUTA Review NEGATIVA
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Cambia lo "STATUS" di un POST (in questo di una post di tipo Review)
//

/*
	APPUNTI: 
	
	'new' 		 - When there's no previous status
	'publish' 	 - A published post or page
	'pending' 	 - post in pending review
	'draft' 	 - a post in draft status
	'auto-draft' - a newly created post, with no content
		'future'     - a post to publish in the future
		'private' 	 - not visible to users who are not logged in
	'inherit' 	 - a revision. see get_children.
	'trash' 	 - post is in trashbin. added with Version 2.9.
*/	

/**
 *
 */
function bp_review_change_post_status($id_post, $new_post_status) 
{
	$wp_update_post_args = array
	(
			'ID'			=> $id_post
		,   'post_status'   => $new_post_status
	);
		
	$result = wp_update_post( $wp_update_post_args );

	return $result;	
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Cambia l' AUTHOR di un POST 
//

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 */
function bp_review_change_post_author($id_post, $new_author_id) 
{
	$wp_update_post_args = array
	(
			'ID'			=> $id_post
		,	'post_author'	=> $new_author_id			//o "author"?
	);
		
	$result = wp_update_post( $wp_update_post_args );

	return $result;	
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	verifica se l'utente pu� SCRIVERE una REVIEW per un altro utente
//--------------------------------------------------------------------------------------------------------------------------------------------------		

function bp_review_current_user_can_write()
{
	$can_write = false;
	 
	if(
			is_user_logged_in() &&!bp_is_my_profile()
//		&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id())
	)
		$can_write = true;

	//FILTER
	return apply_filters('bp_review_current_user_can_write',$can_write);					
}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	verifica se l'utente pu� MODERARE le review NEGATIVE	 
//--------------------------------------------------------------------------------------------------------------------------------------------------		

		function bp_review_current_user_can_moderate()									//TODO usa costante/funzione per nome utente Staff Best2best
		{
			$can_moderate = false;

			$user_id   = bp_loggedin_user_id(); 
			$user_name = bp_core_get_user_displayname( $user_id, false );

			// se l'utente si chiama...
			if(	$user_name == "Staff-Recensioni-Best2Best" )		
				$can_moderate = true;
				
			//FILTER	
			return apply_filters('bp_review_current_user_can_moderate',$can_moderate);
		}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	GET STAFF_MEMBER ID 
//--------------------------------------------------------------------------------------------------------------------------------------------------				

//function bp_review_get_staff_member(	id, name)											//TODO usa costante/funzione per nome utente Staff Best2best
																	
																	
																	
																	
//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	IS_STAFF_MEMBER? - (1) DISPLAYED User
//--------------------------------------------------------------------------------------------------------------------------------------------------		

function bp_review_displayed_user_is_staff_member()
{
	$is_staff_member = false;

	$user_id   = bp_displayed_user_id(); //DISPLAYED
	$user_name = bp_core_get_user_displayname( $user_id, false );

	// se l'utente si chiama...
	if(	$user_name == "Staff-Recensioni-Best2Best" )					//TODO usa costante/funzione per nome utente Staff Best2best
		$is_staff_member = true;
		
	//FILTER	
	return apply_filters('bp_review_displayed_user_is_staff_member',$is_staff_member);
}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	IS_STAFF_MEMBER?  - (2) LOGGED IN User
//--------------------------------------------------------------------------------------------------------------------------------------------------		

function bp_review_loggedin_user_is_staff_member()										
{
	$is_staff_member = false;

	$user_id   = bp_loggedin_user_id(); // LOGGED IN 
	$user_name = bp_core_get_user_displayname( $user_id, false );

	// se l'utente si chiama...
	if(	$user_name == "Staff-Recensioni-Best2Best" )									//TODO usa costante/funzione per nome utente Staff Best2best
		$is_staff_member = true;
		
	//FILTER	
	return apply_filters('bp_review_loggedin_user_is_staff_member',$is_staff_member);
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// LISTA ReviewERs per l'utente - (da CANCELLARE) al momento la uso solo per vedere se l'utente ha Reviews
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Restituisce l array costituito dagli ID  degli utenti  che hanno mandato una review all'utente passato come argomento alla funzione
 */
function bp_review_get_reviewers_list_for_user( $user_id ) 
{
	global $bp;

	if ( !$user_id )
		return false;

	return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );					//META
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Restituisce la lista delle review per un dato utente - (da IMPLEMENTARE)
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/*
function bp_review_get_reviews_for_user( $user_id ) 		
{

	global $bp;

	if ( !$user_id )
		return false;

	//----CANBIARE---return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );				

}
*/	


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
// Rimuovi DATA per un Utente eliminato - (NON USATA)
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * bp_review_remove_data()
 *
 */
 
 /*
function bp_review_remove_data( $user_id ) 
{
	delete_user_meta( $user_id, 'bp_review_some_setting' );															//delete USER_META!
	
	//DO ACTION
	do_action( 'bp_review_remove_data', $user_id );
}

add_action( 'wpmu_delete_user', 'bp_review_remove_data', 1 );
add_action( 'delete_user', 'bp_review_remove_data', 1 );
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// LOAD TEMPLATE Filter - importantissima! senza di questa non carica nessun template!!!
//--------------------------------------------------------------------------------------------------------------------------------------------------		

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by your plugin.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */

/**
 *
 */
function bp_review_load_template_filter( $found_template, $templates )
{
	global $bp;

	if ( $bp->current_component != $bp->review->slug )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	//apply_filters
	return apply_filters( 'bp_review_load_template_filter', $found_template );
}

//FILTER
add_filter( 'bp_located_template', 'bp_review_load_template_filter', 10, 2 );						//IMP: "bp_located_template"

?>