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
__( 'Review inviata!', 'reviews' )
__( 'Review non inviata', 'reviews' )


*/

/**
 *	NOTA BENE: 
 *  
 *  non � richiamata direttamente da NEssuno
 *  ma viene richiamata perch� � stata aggiunta 
 *  con 'add_action' all HOOK: 'bp-actions'
 *
 */
function bp_review_review_save() 
{
/*
											//CURRENT ACTION : screen-one
											//ACTION VARIABLE: send-h5 -->  send-review


	// entra se L'utente loggato ha fatto click sul link 'invia review' 		--->		ACTION = 'send-review'
	if ( bp_is_review_component() && bp_is_current_action( 'screen-one' ) && bp_is_action_variable( 'send-review', 0 ) ) 
	{
		//NON CERA E NON SERVE 
		//---ch--eck_a-dmin_r-eferer( 'new_review', '_wpnonce_new_review' );
		
	    //$error	 =	false;
        //$message =	'';
		
		if ( !is_user_logged_in() ) {
           $message=__('Non sei autorizzato','reviews');
           $error=true;
		}
		else if ( empty( $_POST['new-review'] ) ) 		//controlla se la variabile � vuota			---// non va!!!
		{
			$message = __( 'inserisci del testo', 'reviews' ) ;
			$error=true;
		}
		
		if ( bp_is_my_profile() ) 
		{			
			bp_core_add_message( __( 'non puoi mandare review a te stesso', 'reviews' ), 'error' );
			//$message= __( 'non puoi mandare review a te stesso', 'reviews' ) ;
			//$error=true;
		} 
		else 
		{
			//				chiama la funzione 'bp_review_send_review(utent_1, utente_2)' 		nel FILE 'bp-review-functions.php'	....	se restituisce true � OK!			
			
			$result = bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id() );
			
			//if ( bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id() ) ) 
			if($result)
			{
				//$message = __( 'Review inviata correttamente', 'reviews' ) ;
				
				//PROVA var POST!
					//$message = $_POST['new-review'] . 'ciao';								
				
				bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );
			}
			else 
			{
				bp_core_add_message( __( 'Review non inviata', 'reviews' ), 'error' );
				//$message= __( 'Review non inviata...errore!', 'reviews' ) ;
				//$error=true;
			}
		}
		  
		//bp_core_add_message($message,$error);
		
		//REDIRECT su screenone
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );	//screen one
		//bp_core_redirect(wp_get_referer()); 												 (---> bp-user-reviews)
	}
*/
}

//IMPORTANTE!
//add_action( 'bp_actions', 'bp_review_review_save' );


function salva() 
{
	global $bp;
	
	// entra se L'utente loggato ha fatto click sul link 'invia review' 		--->		ACTION = 'send-review'
	//if ( bp_is_review_component() && bp_is_current_action( 'screen-one' ) && bp_is_action_variable( 'send-review', 0 ) ) 
		
	if ( isset( $_POST['review-submit'] ) && bp_is_active( 'review' ) ) 
	{
		
		// [WPNONCE]
		check_admin_referer( 'bp_review_new_review' );			
		
		$content = $_POST['review-content'];		
			//$content = apply_filters( 'bp_code_snippets_post_snippet_content', $_POST['snippet_content'] );	
		
		//DEBUG prova			
		bp_core_add_message($content);			

		// 

		if ( bp_is_my_profile() ) 
		{			
			bp_core_add_message( __( 'non puoi mandare review a te stesso', 'reviews' ));			
		} 
		else 
		{
			//AGGIUNTO!
			if ( empty( $content ) ) 
			{
				bp_core_add_message( __( 'Inserisci del testo', 'reviews' ),'error' );
				bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );	//screen one		
			}	
///*	
			//chiama la funzione 'bp_review_send_review(utent_1, utente_2)' 		nel FILE 'bp-review-functions.php'		....			se restituisce true � OK!									
			$result = bp_review_send_review( bp_displayed_user_id(), bp_loggedin_user_id(), $content );			
			
			if($result)
			{				
				bp_core_add_message( __( 'Review inviata correttamente', 'reviews' ) );
			}
			else 
			{
				bp_core_add_message( __( 'Review non inviata', 'reviews' ) );			
			}
//*/			
		}

		//REDIRECT su screenone
		bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );	//screen one		
	}
	
}
//IMPORTANTE!
add_action( 'bp_actions', 'salva' );



//---------------------------------------------------------------

function save_review()
{
  /*
		global $bp;
     
		if( 	
					bp_is_current_component($bp->review->slug)
				&&  bp_is_current_action('create')
				&&	!empty($_POST['review-submit'])
			)														//POST - op 1
		{
			// Check the nonce
            ch--eck_admin_referer( 'new_review', '_wpnonce_new_review' )---;
			
			$content = $_POST['new-review'];
			
			bp_core_add_message($content);			
			
			//redirect
				//bp_core_redirect(wp_get_referer()); 
				
			//REDIRECT su screenone
			bp_core_redirect( bp_displayed_user_domain() . bp_get_review_slug() . '/screen-one' );	//screen one						
		}    
*/		
}   

//IMPORTANTE!

//add_action('bp_screens','save_review');	
	//add_action( 'bp_actions', 'save_review' );

?>