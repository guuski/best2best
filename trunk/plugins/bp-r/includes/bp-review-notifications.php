<?php

//---------------------------------------------------------------------------------------------------------------------------------------------------
//	RIMUOVI Notifica - (1/2) -  per le Review POSITIVE, NEUTRE e NEGATIVE Registrato
//---------------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'bp_review_screen_one', 'bp_review_remove_screen_one_notifications' );			//ACTION del plugin -- 'screen_one'																										
add_action( 'xprofile_screen_display_profile', 'bp_review_remove_screen_one_notifications' );

/**
 *
 */
function bp_review_remove_screen_one_notifications() //_screen_one_
 {
	global $bp;

	/* When clicking on a screen notification, we need to remove it from the menu. The following command will do so.*/

	//legata a SCREEN 1
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->review->slug, 'new_review' ); 
	
			//spostare....
			bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->review->slug, 'negative_review_refused' ); 
			bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->review->slug, 'negative_review_accepted' ); 
			
		
}

//---------------------------------------------------------------------------------------------------------------------------------------------------
//	RIMUOVI Notifica -  
//---------------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'bp_review_screen_four', 'bp_review_remove_screen_four_notifications' );			//ACTION del plugin -- 'screen_four'																										
add_action( 'xprofile_screen_display_profile', 'bp_review_remove_screen_four_notifications' );

function bp_review_remove_screen_four_notifications() //_screen_four_
 {
	global $bp;
	
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->review->slug, 'new_review_moderation_request' );
}
//---------------------------------------------------------------------------------------------------------------------------------------------------
//	RIMUOVI Notifica -  
//---------------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'bp_review_screen_five', 'bp_review_remove_screen_five_notifications' );			//ACTION del plugin -- 'screen_five'																										
add_action( 'xprofile_screen_display_profile', 'bp_review_remove_screen_five_notifications' );

function bp_review_remove_screen_five_notifications() //_screen_five_
 {
	global $bp;
	
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->review->slug, 'new_negative_review_sent' );
}

//---------------------------------------------------------------------------------------------------------------------------------------------------
//	RIMUOVI Notifica -  
//---------------------------------------------------------------------------------------------------------------------------------------------------






//---------------------------------------------------------------------------------------------------------------------------------------------------
//	FORMATTA Notifiche
//---------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * bp_review_format_notifications()
 *
 * The format notification function will take DB entries for notifications and format them
 * so that they can be displayed and read on the screen.
 *
 * Notifications are "screen" notifications, that is, they appear on the notifications menu
 * in the site wide navigation bar. They are not for email notifications.
 *
 *
 * The recording is done by using bp_core_add_notification() which you can search for in this file for
 * examples of usage.
 *
 *
 *  NOTA BENE: "$item_id" e' l'user ID dell'utente che ha inviato la review
 *
 * 
 */
function bp_review_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) 
{
	global $bp;
	
	$text_title ="";
	
	switch ( $action ) 
	{
		//1	
		case 'new_review':
						
			if ( (int)$total_items > 1 ) 
			{
				//return apply_filters( 'bp_review_multiple_new_review_notification', '<a class="ab-item" href="' . $bp->loggedin_user->domain . $bp->review->slug . '/" title="' . __( 'Multiple reviews', 'reviews' ) . '">' . sprintf( __( '%d new reviews', 'reviews' ), (int)$total_items ) . '</a>', $total_items );
				$text_title = sprintf( __( '%d new reviews', 'reviews' ), (int)$total_items );
			}
			else 
			{
				$user_fullname = bp_core_get_user_displayname( $item_id, false );// $item_id e' l'user ID dell'utente che ha inviato la review
				
				//FILTER!
				$text_title = apply_filters( 'bp_review_single_new_review_notification', sprintf( __( '%s ti ha mandato una review', 'reviews' ), $user_fullname ) , $user_fullname );
			}
			
			break;
		
		//2			
		case 'new_negative_review_sent':	
			/*		
			if ( (int)$total_items > 1 ) 
			{				
				$text_title = sprintf( __( '%d new reviews', 'reviews' ), (int)$total_items );
			}
			else 
			{
			*/
				//NO FILTER!
				$text_title = __( 'Hai inviato una review Negativa. Prima di diventare pubblica deve essere moderata', 'reviews' );
			//}

			break;			
			
		//3
		case 'new_review_moderation_request':	
				
			if ( (int)$total_items > 1 ) 
			{				
				$text_title = sprintf( __( '%d nuove review da moderare', 'reviews' ), (int)$total_items );
			}
			else 
			{			
				//NO FILTER!
				$text_title = __( 'nuova review negativa da moderare', 'reviews' );
			}

			break;				
			
		//4
		case 'negative_review_refused':	
				
			if ( (int)$total_items > 1 ) 
			{				
				$text_title = sprintf( __( '%d delle tue review negative inviate sono state rifiutate', 'reviews' ), (int)$total_items );
			}
			else 
			{
			
				//NO FILTER!
				$text_title = __( 'una tua review negativa è stata rifiutata', 'reviews' );
			}

			break;				
			
		//5
		case 'negative_review_accepted':	
				
			if ( (int)$total_items > 1 ) 
			{				
				$text_title = sprintf( __( '%d delle tue review negative inviate sono state accettate/pubblicate', 'reviews' ), (int)$total_items );
			}
			else 
			{
			
				//NO FILTER!
				$text_title = __( 'una tua review negativa è stata accetata', 'reviews' );
			}

			break;			
			
	}

	$return =  array
	(
		'text'  => $text_title,
		'link'  => $bp->loggedin_user->domain . $bp->review->slug,
		'title' => __( 'Reviews', 'reviews' )
	);
	
	//DO_ACTION - chiama funzione
	do_action( 'bp_review_format_notifications', $action, $item_id, $secondary_item_id, $total_items );
	
 	return $return;
}

//---------------------------------------------------------------------------------------------------------------------------------------------------
//	INVIA MAIL di Notifica - (1/2) -  per le Review POSITIVE, NEUTRE e NEGATIVE Registrato 
//---------------------------------------------------------------------------------------------------------------------------------------------------

//ACTION legata a "bp_review_send_review"
add_action( 'bp_review_send_review', 'bp_review_send_review_notification', 10, 2 );

/**
 * Notification functions are used to send email notifications to users on specific events
 * They will check to see the users notification settings first, if the user has the notifications
 * turned on, they will be sent a formatted email notification.
 *
 * You should use your own custom actions to determine when an email notification should be sent.
 */
function bp_review_send_review_notification( $to_user_id, $from_user_id ) 
{
	global $bp;

	//invia solo se account destinatario � ghost 	
	if(!esc_attr(get_the_author_meta( 'user_is_ghost', $to_user_id )))		
		return false;
				
	$sender_name	= bp_core_get_user_displayname( $from_user_id, false );
	$reciever_name 	= bp_core_get_user_displayname( $to_user_id, false );
		
	// invia solo se la notifica non � stata gi� vista dall'utente 
	if ( 'no' == get_user_meta( (int)$to_user_id, 'notification_review_new_review', true ) )
		return false;
	
	// USER DATA - Get the userdata for the reciever and sender, this will include usernames and emails that we need. 
	$reciever_ud = get_userdata( $to_user_id );
	$sender_ud 	 = get_userdata( $from_user_id );

	// URL - now we need to construct the URL's that we are going to use in the email 	
	$sender_profile_link	= site_url( adesioni . '/' . $sender_ud->user_login . '/' . $bp->profile->slug );
	$reciever_review_link 	= site_url( adesioni . '/' . $reciever_ud->user_login . '/' . $bp->review->slug . '/my-reviews' );
	
	// MESSAGE - Set up and send the message 
	$to		 = $reciever_ud->user_email;	
	$subject = '[' . get_blog_option( 1, 'blogname' ) . '] ' . sprintf( __( '%s ti ha mandato una review', 'reviews' ), stripslashes($sender_name) );	
	$message = sprintf( __(
	
'Gentil %s,

hai ricevuto una recensione sulla tua attivita\' tramite il network Best2Best 

clicca qui %s per scoprire cosa ha scritto sulla tua azienda

---------------------

http://www.best2best.it/registrati/ Registrati adesso ed entra nel network utile per i tuoi contatti commerciali.

', 'reviews' ), $reciever_name, $reciever_review_link);
	
	// WP_MAIL - invia la mail
	wp_mail( $to, $subject, $message );
	
	//LOG
	error_log("____bp_review_send_review_notification");
	error_log("MAIL =>  ______". "bp_review_send_review_notification");
}


//---------------------------------------------------------------------------------------------------------------------------------------------------
//	INVIA MAIL di Notifica - (2/2) -  per le review NEGATIVE Anonime 
//---------------------------------------------------------------------------------------------------------------------------------------------------

//ACTION legata a "			"
	//add_action( '							', 'bp_review_send_review_anonima_notification', 10, 2 );

/**
 *
 */ 
function bp_review_send_review_anonima_notification( $to_user_id, $from_user_id ) 
{
	global $bp;

	//invia solo se account destinatario � ghost 	
	if(!esc_attr(get_the_author_meta( 'user_is_ghost', $to_user_id )))		
		return false;
				
	$sender_name	= bp_core_get_user_displayname( $from_user_id, false );
	$reciever_name 	= bp_core_get_user_displayname( $to_user_id, false );
		
	// invia solo se la notifica non � stata gi� vista dall'utente 
	if ( 'no' == get_user_meta( (int)$to_user_id, 'notification_review_new_review_anonima', true ) ) //ANONIMA --> notification_review_new_review_anonima
		return false;
	
	// USER DATA - Get the userdata for the reciever and sender, this will include usernames and emails that we need. 
	$reciever_ud = get_userdata( $to_user_id );
	$sender_ud 	 = get_userdata( $from_user_id );

	// URL - now we need to construct the URL's that we are going to use in the email 	
	$sender_profile_link	= site_url( adesioni . '/' . $sender_ud->user_login . '/' . $bp->profile->slug );
	$reciever_review_link 	= site_url( adesioni . '/' . $reciever_ud->user_login . '/' . $bp->review->slug . '/my-reviews' );
	
	// MESSAGE - Set up and send the message 
	$to		 = $reciever_ud->user_email;	
	$subject = '[' . get_blog_option( 1, 'blogname' ) . '] ' .  __( 'hai ricevuto una review NEGATIVA anonima', 'reviews' );	
	$message = sprintf( __(
	
	
'Gentil %s,

hai ricevuto una recensione sulla tua attivita\' tramite il network Best2Best 

clicca qui %s per scoprire cosa hanno scritto sulla tua azienda

---------------------

http://www.best2best.it/registrati/ Registrati adesso ed entra nel network utile per i tuoi contatti commerciali.

', 'reviews' ), $reciever_name, $reciever_review_link);
	
	// WP_MAIL - invia la mail
	wp_mail( $to, $subject, $message );
	
	//LOG
	error_log("____bp_review_send_review_ANONIMA_notification");
	error_log("MAIL =>  ______". "bp_review_send_review_ANONIMA_notification");
}

?>