<?php

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

function bp_ref_post_form_action()
{
    global $bp;
	//echo apply_filters('bp_ref_post_form_action',  $bp->displayed_user->domain.$bp->example->slug."/screen-two/"); 		//la S  //CREATE		
	echo apply_filters('bp_ref_post_form_action',  $bp->displayed_user->domain.$bp->example->slug."/screen-one/"); 		//SCREEN 1
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
// 
// viene chiamata dal metodo 'invia_referral()' (in 'bp-review-actions.php') dopo che e' stato inviato il FORM
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function bp_ref_send_referral( $to_user_id, $from_user_id ) //, $title)
{
	global $bp;
			
	// [WPNONCE]
	check_admin_referer( 'bp_ref_new_referral' );															//ma non è ripetuto?! - vedi FILE Actions!

	//
	$result = create_referral_post($to_user_id,$from_user_id );																	
								
	// --------------------------- NOTIFICATION  --------------------------------------
	
	
	bp_core_add_notification( $from_user_id, $to_user_id, $bp->example->slug, 'new_referral_pending' );		//NOTIFICATIOIN: 'new_referral_pending'
	
																											// EXAMPLE --> REFERRAL
	
	
	// --------------------------- ACTIVITY --------------------------------------
	
	$to_user_link   = bp_core_get_userlink( $to_user_id );
	$from_user_link = bp_core_get_userlink( $from_user_id );
	
	bp_review_record_activity( array
	(
		'type' => 'rejected_terms',			//?!
		'action' => apply_filters( 'bp_review_new_referral_pending_activity_action', sprintf( __( '%s ha richiesto un Referral a %s!', 'reviews' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
		'item_id' => $to_user_id,			//ITEM_ID
	) );
	
	
	// ------------------------------------------------------------------------------------------
	
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
		,   'post_author'	=> $from_user_id 
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
'new' - When there's no previous status
'publish' - A published post or page
'pending' - post in pending review
'draft' - a post in draft status
'auto-draft' - a newly created post, with no content
'future' - a post to publish in the future
'private' - not visible to users who are not logged in
'inherit' - a revision. see get_children.
'trash' - post is in trashbin. added with Version 2.9.
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