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
		'action' => apply_filters( 'bp_review_accepted_terms_activity_action', sprintf( __( '%s accepted the really exciting terms and conditions!', 'bp-review' ), $user_link ), $user_link ),
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
		'action' => apply_filters( 'bp_review_rejected_terms_activity_action', sprintf( __( '%s rejected the really exciting terms and conditions.', 'bp-review' ), $user_link ), $user_link ),
	) );

	if ( function_exists( 'bp_activity_delete') )
		bp_activity_delete( array( 'type' => 'accepted_terms', 'user_id' => $bp->loggedin_user->id ) );

	do_action( 'bp_review_reject_terms', $bp->loggedin_user->id );

	return true;
}









/**
 *
 * Manda un messaggio di review all'utente.
 * Registra una notifica per l'utente con il "notifications menu" e manda una MAIL all utente.
 *
 * Registra un "activity stream item" col seguente testo: "Utente 1 ha inviato una review a Utente 2".
 */
function bp_review_send_review( $to_user_id, $from_user_id ) 
{
	global $bp;

	check_admin_referer( 'bp_review_send_review' );

	/**
	 * Le reviews sono conservate come "usermeta"...
	 *
	 */
	delete_user_meta( $to_user_id, 'reviews' );	
	$existing_reviews = maybe_unserialize( get_user_meta( $to_user_id, 'reviews', true ) );
	
	if ( !in_array( $from_user_id, (array)$existing_reviews ) ) 
	{
		$existing_reviews[] = (int)$from_user_id;
		
		update_user_meta( $to_user_id, 'reviews', serialize( $existing_reviews ) );

		// Let's also record it in our custom database tables
		$db_args = array(
			'recipient_id'  => (int)$to_user_id,
			'reviewer_id' 	=> (int)$from_user_id
		);

		$review = new Review( $db_args );											//istanzia oggetto della classe 'Review'
		$review->save();
	}

	bp_core_add_notification( $from_user_id, $to_user_id, $bp->review->slug, 'new_review' );
	
	$to_user_link = bp_core_get_userlink( $to_user_id );
	$from_user_link = bp_core_get_userlink( $from_user_id );

	bp_review_record_activity( array
	(
		'type' => 'rejected_terms',
		'action' => apply_filters( 'bp_review_new_review_activity_action', sprintf( __( '%s reviewED %s!', 'bp-review' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
		'item_id' => $to_user_id,
	) );

	// per la MAIL notification ---> See bp-review-notifications.php 
	do_action( 'bp_review_send_review', $to_user_id, $from_user_id );

	return true;
}

/**
 *
 *
 * Restituisce l array costituito dagli ID  degli utenti  che hanno mandato una review all'utente passato come argomento alla funzione
 */
function bp_review_get_reviews_for_user( $user_id ) 
{
	global $bp;

	if ( !$user_id )
		return false;

	return maybe_unserialize( get_user_meta( $user_id, 'reviews', true ) );
}


/**
 * bp_review_remove_data()
 *
 */
function bp_review_remove_data( $user_id ) 
{
	delete_user_meta( $user_id, 'bp_review_some_setting' );
	do_action( 'bp_review_remove_data', $user_id );
}

add_action( 'wpmu_delete_user', 'bp_review_remove_data', 1 );
add_action( 'delete_user', 'bp_review_remove_data', 1 );

?>