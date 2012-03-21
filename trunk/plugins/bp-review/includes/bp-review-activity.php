<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------
	
-----------------------------------------
global $bp
-----------------------------------------
$bp->review->id
$bp->loggedin_user->id

/**
 *
 */
function bp_review_record_activity( $args = '' ) 
{
	global $bp;

	if ( !function_exists( 'bp_activity_add' ) )
		return false;

	$defaults = array
	(
		'id' 					=> 	false,
		'user_id' 				=> 	$bp->loggedin_user->id,
		'action' 				=> 	'',
		'content'				=> 	'',
		'primary_link' 			=> 	'',
		'component' 			=> 	$bp->review->id,
		'type' 					=> 	false,
		'item_id'	 			=> 	false,												//item=review?
		'secondary_item_id' 	=> 	false,
		'recorded_time' 		=> 	gmdate( "Y-m-d H:i:s" ),
		'hide_sitewide' 		=> 	false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return bp_activity_add( array( 	'id' => $id, 
									'user_id' => $user_id, 
									'action' => $action, 
									'content' => $content, 
									'primary_link' => $primary_link, 
									'component' => $component, 
									'type' => $type, 
									'item_id' => $item_id, 												//item=review?
									'secondary_item_id' => $secondary_item_id, 
									'recorded_time' => $recorded_time, 
									'hide_sitewide' => $hide_sitewide 
							) 
	);
}

?>