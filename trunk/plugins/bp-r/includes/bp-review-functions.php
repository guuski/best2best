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
 *
 * ----- parte 3 --------
 *
 *  - manda una MAIL all utente.
 *
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
									$tipo_review_negativa,																		
									$voti
								)
{
	global $bp;
			
	// [WPNONCE]
	check_admin_referer( 'bp_review_new_review' );
	
	// TODO: riscrivi da qua fino a $review = new Review( $db_args );
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
		$save_result = $review->save( $title, $content, $giudizio_review, $data_rapporto, $tipologia_rapporto, $voti		
									 ,$tipo_review_negativa 		
									);																							
	}//TODO: va esteso forse fino a comprendere la 2 parte che al momento rimane fuori
	
	//---------------------------------------------- 2 parte ---------------------------------------------------------------
	
	if($save_result) 									//Se la Review è stata salvata correttamente....
	{			
		if(!$tipo_review_negativa == "anonimo") 		//Escludi nel caso di Review NEGATIVA tipo Anonimo
		{
			//NOTIFICA del tipo/nome "NEW_REVIEW"
			bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review' ); 
		
			$to_user_link   = bp_core_get_userlink( $to_user_id );
			$from_user_link = bp_core_get_userlink( $from_user_id );
		
			//ACTIVITY
			bp_review_record_activity( array
			(
				'type' => 'rejected_terms',
				'action' => apply_filters( 'bp_review_new_review_activity_action', sprintf( __( '%s ha scritto una review per %s!', 'reviews' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
				'item_id' => $to_user_id,
			) );
				
			//--------------------------------------------- 3 parte ----------------------------------------------------------------				
			// TODO: non viene mandata l'ENAIL per la Review NEGATIVA tipo Anonimo!
			// ------------------------------------------------------------------------------------------------------
			
			// DO_ACTION -- We'll use this do_action call to send the email notification. See bp-example-notifications.php 
			do_action( 'bp_review_send_review', $to_user_id, $from_user_id);															
		}
	}
	
	//RETURN	
	return $save_result;
}


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
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

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


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	verifica se l'utente può SCRIVERE una REVIEW per un altro utente
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
//	verifica se l'utente può MODERARE le review NEGATIVE	 
//--------------------------------------------------------------------------------------------------------------------------------------------------		

function bp_review_current_user_can_moderate()
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

	return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );
}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
// LOAD TEMPLATE Filter - (NON USATA)
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
add_filter( 'bp_located_template', 'bp_review_load_template_filter', 10, 2 );


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
//  TERMS func 1 - (NON USATA)
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 *
 */
 
 /*
function bp_review_accept_terms() 
{
	global $bp;

	check_admin_referer( 'bp_review_accept_terms' );
	$user_link = bp_core_get_userlink( $bp->loggedin_user->id );

	bp_review_record_activity( array
	(
		'type' => 'accepted_terms',
		'action' => apply_filters( 'bp_review_accepted_terms_activity_action', sprintf( __( '%s accepted the really exciting terms and conditions!', 'reviews' ), $user_link ), $user_link ),
	) );

	if ( function_exists( 'bp_activity_delete') )
		bp_activity_delete( array( 'type' => 'rejected_terms', 'user_id' => $bp->loggedin_user->id ) );

	do_action( 'bp_review_accept_terms', $bp->loggedin_user->id );

	return true;
}
*/

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//  TERMS func 2 - (NON USATA)
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
 /*
function bp_review_reject_terms() 
{
	global $bp;

	check_admin_referer( 'bp_review_reject_terms' );

	$user_link = bp_core_get_userlink( $bp->loggedin_user->id );

	bp_review_record_activity( array
	(
		'type' => 'rejected_terms',
		'action' => apply_filters( 'bp_review_rejected_terms_activity_action', sprintf( __( '%s rejected the really exciting terms and conditions.', 'reviews' ), $user_link ), $user_link ),
	) );

	if ( function_exists( 'bp_activity_delete') )
		bp_activity_delete( array( 'type' => 'accepted_terms', 'user_id' => $bp->loggedin_user->id ) );

	do_action( 'bp_review_reject_terms', $bp->loggedin_user->id );

	return true;
}
*/

?>