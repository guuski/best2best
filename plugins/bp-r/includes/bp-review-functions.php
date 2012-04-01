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
// usata dal FORM del file di Template 	----------'screen-one.php'
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

function bp_review_form_action() 
{
	echo site_url() . remove_query_arg( array('s','snptcat','n') );
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
// viene chiamata dal metodo 'salva()' (in 'bp-review-actions.php') dopo che è stato inviato il FORM
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 * Manda un messaggio di review all'utente.
 *
 * --------------- parte 1 ------------------
 *
 *
 * --------------- parte 2 ------------------
 *
 * Registra una notifica per l'utente con il "notifications menu" e manda una MAIL all utente.
 *
 * Registra un "activity stream item" col seguente testo: "Utente 1 ha inviato una review a Utente 2".
 *
 *
 * @see http://codex.wordpress.org/Function_Reference/check_admin_referer
 * @see http://codex.wordpress.org/Function_Reference/delete_user_meta
 * @see http://codex.wordpress.org/Function_Reference/maybe_unserialize
 *
 */
function bp_review_send_review( $to_user_id, $from_user_id, $content, $voto_prezzo, $voto_servizio) 					//[C] Rating
{
	global $bp;
			
	// [WPNONCE]
	check_admin_referer( 'bp_review_new_review' );

	//cancella il campo 'reviews' associato  all'utente di destinazione! 
	// ---> ma picchi? - cmq non si può staccare!
	delete_user_meta( $to_user_id, 'reviews' );																//delete USER_META!
	
	//
	$existing_reviews = maybe_unserialize( get_user_meta( $to_user_id, 'reviews', true ) );
	
	//
	if ( !in_array( $from_user_id, (array)$existing_reviews ) ) 
	{
		$existing_reviews[] = (int)$from_user_id;
		
		//
		update_user_meta( $to_user_id, 'reviews', serialize( $existing_reviews ) );							//update USER_META!

		// Let's also record it in our custom database tables
		$db_args = array
		(
			'recipient_id'  => (int)$to_user_id,
			'reviewer_id' 	=> (int)$from_user_id
		);

		//
		$review = new Review( $db_args );															//istanzia oggetto della CLASSE 'Review'
		
		//
		$review->save($content, $voto_prezzo, $voto_servizio);																	// [C] Rating
	}
	
	//-------------------- 2 parte ---------------------------------------------------------------------------

	//
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
	
	//DO ACTION
	do_action( 'bp_review_send_review', $to_user_id, $from_user_id , $content);						//aggiunto $content

	//
	return true;																					//ritorna sempre TRUE --?!?!? non va!
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// da CANCELLARE forse ----> al momento la uso solo per vedere se l'utente ha Reviews
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Restituisce l array costituito dagli ID  degli utenti  che hanno mandato una review all'utente passato come argomento alla funzione
 *
 */
function bp_review_get_reviewers_list_for_user( $user_id ) 
{
	global $bp;

	if ( !$user_id )
		return false;

	return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// funz da IMPLEMENTARE........
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 * Restituisce la lista delle review per un dato utente
 *
 */
function bp_review_get_reviews_for_user( $user_id ) 		
{

	global $bp;

	if ( !$user_id )
		return false;
/*
	//----CANBIARE---return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );				
*/	
}


 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// se l'utente viene cancellato fa un po' di pulizia
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * bp_review_remove_data()
 *
 */
function bp_review_remove_data( $user_id ) 
{
	delete_user_meta( $user_id, 'bp_review_some_setting' );															//delete USER_META!
	
	//DO ACTION
	do_action( 'bp_review_remove_data', $user_id );
}

add_action( 'wpmu_delete_user', 'bp_review_remove_data', 1 );
add_action( 'delete_user', 'bp_review_remove_data', 1 );


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ma le usa ste 2 funzioni ?!?!
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 *
 */
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

/**
 *
 *
 */
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ma la usa?!?!		-------> pare di no!
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

	return apply_filters( 'bp_review_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'bp_review_load_template_filter', 10, 2 );

?>