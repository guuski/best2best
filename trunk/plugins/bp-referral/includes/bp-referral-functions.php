<?php

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	
//--------------------------------------------------------------------------------------------------------------------------------------------------		

//can user ask for a referral
function referral_current_user_can_write()
{
	$can_write=false;
	 
	if(		is_user_logged_in()
		&&	!bp_is_my_profile()
	//	&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id())
	)
	{
		$can_write = true;
	}
		
	//////////////////////////////////////////////////////////////////////
	// 1 - trash
	//////////////////////////////////////////////////////////////////////
	$query_args = array
	(
			'post_status'		=> 'trash'					// Status: TRASH
		,	'post_type'			=> 'referral'				// Tipo:   'referral'
		,   'author'			=> bp_loggedin_user_id()														
		,	'meta_query'		=> array()				
	);

	$query_args['meta_query'][] = array					
	(
			'key'	  => 'bp_referral_recipient_id',
			'value'	  => (array)bp_displayed_user_id(),
			'compare' => 'IN' 							
	);		

	$loop = new WP_Query($query_args);	
		
	if ( $loop->have_posts() ) 
	{
		$can_write = false;
	}	
	//////////////////////////////////////////////////////////////////////
			
	//////////////////////////////////////////////////////////////////////
	// 2 - publish
	//////////////////////////////////////////////////////////////////////
	$query_args = array
	(
			'post_status'		=> 'publish'					// Status: PUBLISH
		,	'post_type'			=> 'referral'					// Tipo:   'referral'
		,   'author'			=> bp_loggedin_user_id()														
		,	'meta_query'		=> array()				
	);

	$query_args['meta_query'][] = array					
	(
		'key'	  => 'bp_referral_recipient_id',
		'value'	  => (array)bp_displayed_user_id(),
		'compare' => 'IN' 							
	);		

	$loop = new WP_Query($query_args);	
		
	if ( $loop->have_posts() ) 
	{
		$can_write = false;
	}
	//////////////////////////////////////////////////////////////////////
	
	//////////////////////////////////////////////////////////////////////
	// 3 - pending
	//////////////////////////////////////////////////////////////////////
	$query_args = array
	(
			'post_status'		=> 'pending'					// Status: PENDING
		,	'post_type'			=> 'referral'					// Tipo:   'referral'
		,   'author'			=> bp_loggedin_user_id()														
		,	'meta_query'		=> array()				
	);

	$query_args['meta_query'][] = array					
	(
		'key'	  => 'bp_referral_recipient_id',
		'value'	  => (array)bp_displayed_user_id(),
		'compare' => 'IN' 							
	);		

	$loop = new WP_Query($query_args);	
		
	if ( $loop->have_posts() ) 
	{
		$can_write = false;
	}
	//////////////////////////////////////////////////////////////////////	

	//--------------------------------------------------------------------
	return apply_filters('bp_referral_can_user_write',$can_write);
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// FORM
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

function bp_ref_post_form_action()			//EXAMPLE --> REFERRAL																										
{
    global $bp;
		
	// SCREEN 5		
	echo apply_filters('bp_ref_post_form_action',  $bp->displayed_user->domain.$bp->example->slug."/screen-five/"); 		
		
}

			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
			// FORM	2
			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

			/*
			function bp_ref_post_form_action		()					//EXAMPLE --> REFERRAL																										
			{
				global $bp;
				
				// SCREEN 5		
				echo apply_filters('bp_ref_post_form_action',  $bp->displayed_user->domain.$bp->example->slug."/screen-five/"); 		
				
			}
			*/

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
// viene chiamata dal metodo 'invia_referral()' (in 'bp-referral-actions.php') dopo che e' stato inviato il FORM
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function bp_ref_send_referral( $to_user_id, $from_user_id )
{
	global $bp;
			
	// [WPNONCE]
	check_admin_referer( 'bp_ref_new_referral' );											//ma non è ripetuto?! - vedi FILE Actions!

	// FUNCTION CALL
	$result = create_referral_post($to_user_id,$from_user_id );																	
	
	if($result) 
	{									
		// --------------------------- NOTIFICATION  --------------------------------------
		
		// EXAMPLE --> REFERRAL		
		bp_core_add_notification( $from_user_id, $to_user_id, $bp->example->slug, 'new_referral_pending' );		//NOTIFICATIOIN: 'new_referral_pending'
		
		// --------------------------- ACTIVITY -------------------------------------------
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$to_user_link   = bp_core_get_userlink( $to_user_id );					//sono UGUALI?!?!
		$from_user_link = bp_core_get_userlink( $from_user_id );
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		
		bp_referral_record_activity( 
			array
			(
					'type'		=>  'rejected_terms'							//?!
				,	'action' 	=>  apply_filters
												( 	'bp_ref_new_referral_pending_activity_action', 
													sprintf( __( '%s ha richiesto un Referral a %s!', 'referrals' ), 
													$from_user_link, $to_user_link ), $from_user_link, $to_user_link 
												)
				,	'item_id'	=>  $to_user_id									//ITEM_ID
			) 
		);
	}			
	return $result;											
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
// chiamata 'accetta_referral()' (in 'bp-referral-actions.php') 
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function bp_ref_accept_referral_request( $id_post, $from_user_id, $to_user_id, $tipologia_rapporto, $anzianita_rapporto , $utente_consigliato, $voto_complessivo )
{
	global $bp;
			
	// FUNCTION call 1
	$status_changed = change_referral_post_status($id_post, 'publish');
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//---> BUG: risultano uguali (REFERRAL di Andrea su Andrea) 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
		//invertiti
		$new_referral_title = sprintf( __( 'REFERRAL di %1$s su %2$s', 'referrals' ),  bp_core_get_user_displayname( $to_user_id ), bp_core_get_user_displayname( $from_user_id ) );
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	
	// FUNCTION call 2
	$title_changed = change_referral_title($id_post, $new_referral_title);		
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	// ---> IMPLEM: devo invertire AUTORE e Recipient forse!!! 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	// FUNCTION call 3
	$author_inverted = true;
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	// FUNCTION call 4
	$metatags = add_referral_metatags($id_post, $tipologia_rapporto, $anzianita_rapporto , $utente_consigliato, $voto_complessivo );		
	
	//
	//----> CHANGE: un IF SELETTIVO SAREBBE MEGLIO!!!! per capire quale errore si è verificato
	//
	if($status_changed && $title_changed && $author_inverted && $metatags) 				
	{				
		// --------------------------- NOTIFICATION  --------------------------------------		
		
		// EXAMPLE --> REFERRAL		
		
		//scambio UTENTI!		
			//$from_user_id_new = $to_user_id;
			//$to_user_id_new   = $from_user_id;				
			//bp_core_add_notification( $from_user_id_new, $to_user_id_new, $bp->example->slug, 'new_referral_accepted' ); //NOTIFICATIOIN: 'new_referral_accepted'
			
		bp_core_add_notification( $from_user_id, $to_user_id, $bp->example->slug, 'new_referral_accepted' ); //NOTIFICATIOIN: 'new_referral_accepted'
		
		// --------------------------- ACTIVITY -------------------------------------------
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$to_user_link   = bp_core_get_userlink( $to_user_id );								//sono UGUALI?!?!
		$from_user_link = bp_core_get_userlink( $from_user_id );
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		
		bp_referral_record_activity( array
		(
			'type' => 'rejected_terms',			//?!
			'action' => apply_filters( 'bp_ref_new_referral_accepted_activity_action', sprintf( __( '%s ha accettato la richiesta di Referral di %s!', 'referrals' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
			'item_id' => $to_user_id,			//ITEM_ID
		) );
				
		//IMPORTANTE!
		$result = true;	
	}	
	return $result;											
}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
// chiamata da 'rifiuta_referral()' (in 'bp-referral-actions.php') 
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function bp_ref_deny_referral_request( $id_post, $from_user_id, $to_user_id )
{
	global $bp;
			
	$result = change_referral_post_status($id_post, 'trash');
		
	if($result) 
	{	
		// --------------------------- NOTIFICATION  --------------------------------------
		
		// EXAMPLE --> REFERRAL		
		
		//scambio UTENTI!		
			//$from_user_id_new = $to_user_id;
			//$to_user_id_new   = $from_user_id;				
			//bp_core_add_notification( $from_user_id_new, $to_user_id_new, $bp->example->slug, 'new_referral_denied' );		//NOTIFICATIOIN: 'new_referral_denied'
			
		bp_core_add_notification( $from_user_id, $to_user_id, $bp->example->slug, 'new_referral_denied' );		//NOTIFICATIOIN: 'new_referral_denied'
			
		// --------------------------- ACTIVITY -------------------------------------------
						
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$to_user_link   = bp_core_get_userlink( $to_user_id );						//sono UGUALI?!?!
		$from_user_link = bp_core_get_userlink( $from_user_id );
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		
		bp_referral_record_activity( array
		(
			'type' => 'rejected_terms',			//?!
			'action' => apply_filters( 'bp_ref_new_referral_denied_activity_action', sprintf( __( '%s ha rifiutato la richiesta di Referral di %s!', 'referrals' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
			'item_id' => $to_user_id,			//ITEM_ID
		) );						
	}
	
	//
	return $result;											
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function create_referral_post($to_user_id , $from_user_id) 
{	
	$referral_title = sprintf( __( 'Richiesta referral da parte di %1$s a %2$s', 'referrals' ), bp_core_get_user_displayname( $from_user_id ), bp_core_get_user_displayname( $to_user_id ) );

	$wp_insert_post_args = array
	(
			'post_status'	=> 'pending'		//PENDING!!!
		,	'post_type'		=> 'referral'										//post_type
				,   'post_author'	=> $from_user_id 														//? è SBAGLIATO!!!
		,	'post_title'	=> $referral_title			
	);

	//
	$result = wp_insert_post( $wp_insert_post_args );
	
	if ( $result ) 			
	{
		update_post_meta( $result, 'bp_referral_recipient_id', $to_user_id );			
	}

	return $result;
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function change_referral_post_status($id_post, $new_post_status) 
{
	$wp_update_post_args = array
	(
			'ID'			=> $id_post
		,   'post_status'   => $new_post_status
	);
		
	$result = wp_update_post( $wp_update_post_args );

	return $result;	
}

/*
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
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function change_referral_title($id_post, $post_title) 
{
	$wp_update_post_args = array
	(
			'ID'			=> $id_post
		,	'post_title'	=> $post_title		
	);
		
	$result = wp_update_post( $wp_update_post_args );

	return $result;	
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function add_referral_metatags($id_post , $tipologia_rapporto , $anzianita_rapporto , $utente_consigliato , $voto_complessivo ) 
{
	$wp_update_post_args = array
	(
		'ID' => $id_post					
	);
		
	$result = wp_update_post( $wp_update_post_args );
	
	if($result) 
	{			
		update_post_meta( $result, 'tipologia_rapporto',$tipologia_rapporto);			
		update_post_meta( $result, 'anzianita_rapporto',$anzianita_rapporto);			
		update_post_meta( $result, 'utente_consigliato',$utente_consigliato);			
		update_post_meta( $result, 'voto_complessivo',$voto_complessivo);			
	}

	return $result;	
}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//  è usata?!													//EXAMPLE --> REFERRAL
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

																										
/**
 * bp_example_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_example_screen_one() when you want to load
 * a template file.
 */
function bp_example_load_template_filter( $found_template, $templates ) {
	global $bp;

	/**
	 * Only filter the template location when we're on the example component pages.
	 */
	if ( $bp->current_component != $bp->example->slug )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	return apply_filters( 'bp_example_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'bp_example_load_template_filter', 10, 2 );

?>