<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	'bp_is_review_component()' 	in 'bp-review-classes()'
	'bp_get_review_slug()'		in 
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_is_current_action()
	bp_is_action_variable()
	bp_core_add_message()
	bp_core_redirect()	
	bp_is_my_profile()
	bp_displayed_user_domain()
	bp_displayed_user_id()
	bp_loggedin_user_id()
	
	
	'bp_actions' --> 'bp_review_review_save()'
	
-----------------------------------------
global $bp
-----------------------------------------

-----------------------------------------
[T]
-----------------------------------------
__( 'Review sent!', 'bp-review' )
__( 'Review non inviata', 'bp-review' )


*/

/**
 *
 *
 */
function bp_review_review_save() {

	if ( bp_is_review_component() && bp_is_current_action( 'screen-one' ) && bp_is_action_variable( 'send-review', 0 ) ) 
	{
		// L'utente ha fatto click sul link 'invia review' 

		if ( bp_is_my_profile() ) 
		{			
			bp_core_add_message( __( 'No self reviews', 'bp-review' ), 'error' );
		} 
		else 
		{
			if ( bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id() ) )
				bp_core_add_message( __( 'Review sent!', 'bp-review' ) );
			else
				bp_core_add_message( __( 'Review non inviata', 'bp-review' ), 'error' );
		}

		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );			//screen one
	}
}
add_action( 'bp_actions', 'bp_review_review_save' );

?>