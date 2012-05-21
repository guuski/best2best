<?php

//------------------------------------------------------------------------------------------------------------------------------------------------------------------
// REMOVE SCREEN Notifications - 1 - new_referral_pending (PENDING)



												//ATTIVA l'action!
												
//------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 */
function bp_referral_remove_screen_notifications_pending()																					//EXAMPLE --> REFERRAL
{
	global $bp;	
	
	//FUNZIONE DEPRECATA
		//bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->slug, 'new_referral_pending' );	  //SLUG
		//bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->id, 'new_referral_pending' );	  //ID			
	
	//FUNZIONE OK
		//bp_core_delete_notifications_by_type(bp_loggedin_user_id(), $bp->example->id,'new_referral_pending');	

					
}
//EXAMPLE --> REFERRAL																																			
	//add_action( 'bp_example_screen_four', 'bp_referral_remove_screen_notifications' );		// SCREEN - 4																							

	//altri SCRREN?
		//add_action( 'bp_example_screen_		', 'bp_referral_remove_screen_notifications' );		// SCREEN - 		
	
//------------io la toglierei!  - ma manco funziona forse?!																							
add_action( 'xprofile_screen_display_profile', 'bp_referral_remove_screen_notifications_pending' );	


//------------------------------------------------------------------------------------------------------------------------------------------------------------------
// REMOVE SCREEN Notifications - 2 - new_referral_accepted  (ACCEPTED)




												//ATTIVA l'action!
												
//------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 */
function bp_referral_remove_screen_notifications_accepted()																					//EXAMPLE --> REFERRAL
{
	global $bp;	
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->slug, 'new_referral_accepted' );			
	
						
					
					
					//$bp->example->id	
					
					

}

//EXAMPLE --> REFERRAL																								
	//add_action( 'bp_example_screen_		', 'bp_referral_remove_screen_notifications' );		// SCREEN - 		
																																																								
//------------io la toglierei!  - ma manco funziona forse?!																							
	add_action( 'xprofile_screen_display_profile', 'bp_referral_remove_screen_notifications_accepted' );	
	
	
		
//------------------------------------------------------------------------------------------------------------------------------------------------------------------
// REMOVE SCREEN Notifications - 3 - new_referral_denied  (DENIED)





																//ATTIVA l'action!
//------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 */
function bp_referral_remove_screen_notifications_denied()																					//EXAMPLE --> REFERRAL
{
	global $bp;	
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->slug, 'new_referral_denied' );			
	
	
						
					
					
					//$bp->example->id	
					
					

}

//EXAMPLE --> REFERRAL																								
	//add_action( 'bp_example_screen_		', 'bp_referral_remove_screen_notifications' );		// SCREEN - 		
																																																								
//------------io la toglierei!  - ma manco funziona forse?!																							
add_action( 'xprofile_screen_display_profile', 'bp_referral_remove_screen_notifications_denied' );	
	
	

	
	
	
	
	
	
	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------
// FORMAT Notifications
//------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 */
function bp_referral_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) 
{
	global $bp;
	
	$text_title ="";
	
	switch ( $action ) 
	{
		//INFO: $item_id e' l'user ID dell'utente che ha inviato la richiesta REFERRAL
	
		// 
		// 1 - Referral PENDING - Mi hanno chiesto un REFERRAL
		// 
		case 'new_referral_pending':							

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//EXAMPLE --> REFERRAL			
			
			$link = $bp->loggedin_user->domain . $bp->example->slug .'/screen-four';			//Screen 4 
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
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
/*		

		//--------------------------------------------------
		
			ATTIVA l'action    
		   
							oppure
		   
			aggiungi la chiamata nella corrispotiva  funzione SCREEN in 'bp-referral-screens'

		//--------------------------------------------------

*/
		// 
		// 2 - La mia richiesta Referral ACCETTATA
		// 
		case 'new_referral_accepted':		

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//EXAMPLE --> REFERRAL							
			
			//opt 1 - SCREEN 3 - Le Referral richieste da me
			$link = $bp->loggedin_user->domain . $bp->example->slug .'/screen-three';			
			
			//opt 2 - SCREN   - Le ref accettate a me
				//$link = $bp->loggedin_user->domain . $bp->example->slug .'/screen-	',			//Screen  
			////////////////////////////////////////////////////////////////////////////////////////////////////////////		
					
			if ( (int)$total_items > 1 ) 
			{
				$text_title = sprintf( __( '%d richieste referral accettate', 'referrals' ), (int)$total_items );
			}
			else 
			{
				$user_fullname 	= bp_core_get_user_displayname( $item_id, false );
				$text_title		= apply_filters( 'bp_ref_single_new_referral_accepted_notification', sprintf( __( '%s ha accettato la tua richiesta di Referral', 'referrals' ), $user_fullname ) , $user_fullname );
			}
			
		break;
/**/		

/*		

		//--------------------------------------------------
		
		
			ATTIVA l'action    
		   
							oppure
		   
			aggiungi la chiamata nella corrispotiva  funzione SCREEN in 'bp-referral-screens'

*/
		//--------------------------------------------------
		
		// 
		// 3 - La mia richiesta Referral RIFIUTATA
		// 
		case 'new_referral_denied':				

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//EXAMPLE --> REFERRAL	
			
			//opt 1 - SCREEN 3 - Le Referral richieste da me
			$link = $bp->loggedin_user->domain . $bp->example->slug .'/screen-three';		
			
			//opt 2 - le REFERRAL rifiutate a me (SCREEN 7) 
				//$link = $bp->loggedin_user->domain . $bp->example->slug .'/screen-seven',			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////				
			
					
			if ( (int)$total_items > 1 ) 
			{
				$text_title = sprintf( __( '%d richieste referral rifiutate', 'referrals' ), (int)$total_items );
			}
			else 
			{
				$user_fullname 	= bp_core_get_user_displayname( $item_id, false );
				$text_title		= apply_filters( 'bp_ref_single_new_referral_denied_notification', sprintf( __( '%s ha rifiutato la tua richiesta di Referral', 'referrals' ), $user_fullname ) , $user_fullname );
			}
			
		break;
/*	*/
		
	}// fine SWITCH

	//
	$return =  array
	(
		//
			'text' 	=> $text_title
			
		//REDIRECT!	
		,   'link'	=> $link								
	//	,	'link' 	=> $bp->loggedin_user->domain . $bp->example->slug .'/screen-four',		//Screen 4    //EXAMPLE --> REFERRAL	
		
		//
		,	'title'	=> __( 'Referrals', 'referrals' )						//?!
	//  ,	'title'	=> $title												// se voglio personalizzarlo per ogni 
	
	);

	//DO_ACTION
	do_action( 'bp_referral_format_notifications', $action, $item_id, $secondary_item_id, $total_items );
	
 	return $return;
}
?>
