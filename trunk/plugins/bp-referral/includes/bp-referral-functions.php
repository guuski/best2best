<?php

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by your plugin.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */

 
 //OPZIONE 2
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
	check_admin_referer( 'bp_ref_new_referral' );

	//$to_user_id
	//$from_user_id
	
	save_referral($to_user_id,$from_user_id );																	
								
	
	/*
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
	
	*/
	//-------------------- incredibile! ---------------------------------------------------------------------------
	//
	return true;																					//ATTENZIONE: ritorna sempre TRUE --?!?!? non va proprio!!!
}




function save_referral($to_user_id,$from_user_id) 

{




}







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