<?php

/**
 *
 */
function bp_referral_remove_screen_notifications()
 {
 
	global $bp;
	
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->slug, 'new_referral_pending' );			//EXAMPLE --> REFERRAL
																															//new_referral_pending
}



add_action( 'bp_example_screen_four', 'bp_referral_remove_screen_notifications' );									//ACTION del plugin -- 'screen_four'  --- SCREEN 4
																							//EXAMPLE --> REFERRAL
																							
	//add_action( 'bp_example_screen_		', 'bp_referral_remove_screen_notifications' );									//ACTION del plugin -- 'screen_     '  --- SCREEN 
	//EXAMPLE --> REFERRAL
																							
//------------io la toglierei!  - ma manco funziona forse?!																							
add_action( 'xprofile_screen_display_profile', 'bp_referral_remove_screen_notifications' );	


/**
 *
 */
function bp_referral_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) 
{
	global $bp;
	
	$text_title ="";
	
	switch ( $action ) 
	{
	
		// 
		// 1
		// 
		case 'new_referral_pending':																			//new_referral_pending - Referral PENDING - Mi hanno chiesto un REFERRAL
			
			// $item_id e' l'user ID dell'utente che ha inviato la richiesta REFERRAL

			if ( (int)$total_items > 1 ) 
			{
				$text_title = sprintf( __( '%d new rihieste referral ', 'referrals' ), (int)$total_items );
			}
			else 
			{
				$user_fullname 	= bp_core_get_user_displayname( $item_id, false );
				$text_title		= apply_filters( 'bp_ref_single_new_referral_request_notification', sprintf( __( '%s ti ha mandato una richiesta Referral', 'referrals' ), $user_fullname ) , $user_fullname );
			}
		break;
		
		// 
		// 2
		// 
		case 'new_referral_accepted':																			//new_referral_accepted  - La mia richiesta Referral ACCETTATA
		
			// $item_id e' l'user ID dell'utente che ha inviato la richiesta REFERRAL

			if ( (int)$total_items > 1 ) 
			{
				$text_title = sprintf( __( '%d referral accettati', 'referrals' ), (int)$total_items );
			}
			else 
			{
				$user_fullname 	= bp_core_get_user_displayname( $item_id, false );
				$text_title		= apply_filters( 'bp_ref_single_new_referral_accepted_notification', sprintf( __( '%s ha accettato la tua richiesta di Referral', 'referrals' ), $user_fullname ) , $user_fullname );
			}
		break;
		
		// 
		// 3
		// 
		case 'new_referral_denied':																			//new_referral_denied -  La mia richiesta Referral RIFIUTATA
			// $item_id e' l'user ID dell'utente che ha inviato la richiesta REFERRAL

			if ( (int)$total_items > 1 ) 
			{
				$text_title = sprintf( __( '%d referral non accettati', 'referrals' ), (int)$total_items );
			}
			else 
			{
				$user_fullname 	= bp_core_get_user_displayname( $item_id, false );
				$text_title		= apply_filters( 'bp_ref_single_new_referral_denied_notification', sprintf( __( '%s ha rifiutato la tua richiesta di Referral', 'referrals' ), $user_fullname ) , $user_fullname );
			}
		break;
	}

	//
	$return =  array
	(
			'text' => $text_title,
			'link' => $bp->loggedin_user->domain . $bp->example->slug,				//EXAMPLE --> REFERRAL
			'title' => __( 'Referrals', 'referrals' )
	);

	//DO_ACTION
	do_action( 'bp_referral_format_notifications', $action, $item_id, $secondary_item_id, $total_items );
	
 	return $return;
}


?>
