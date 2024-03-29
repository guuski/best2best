<?php

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
// sposta nel file FUNCTIONS?
//----------------------------------------------------------------------------------------------------------------------------------------------------------------
function bp_ref_check_voto($voto) 
{
	return (empty($voto) || $voto==0);
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
// INVIA RICHIESTA Referral
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

//[OSS] - se stacco l'action funziona lo stesso secondo me!
add_action( 'bp_actions', 'invia_richiesta_referral' );

/**
 *
 */
function invia_richiesta_referral() 
{
	global $bp;
	
	if ( isset( $_POST['referral-submit'] ) 
		)//&& bp_is_active( 'example' ) ) 	//&& bp_is_active( 'referral' ) ) 				//EXAMPLE --> REFERRAL
	{		
		// [WPNONCE]
		check_admin_referer( 'bp_ref_new_referral' );			
					
		// FUNCTION call
		$result = bp_ref_send_referral
		(	
				bp_displayed_user_id()
			, 	bp_loggedin_user_id()		
		);																																						
		
		if($result)
		{				
			bp_core_add_message( __( 'Richiesta REFERRAL inviata correttamente', 'referrals' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'Richiesta REFERRAL non inviata --> ERRORE!', 'referrals' ) );			
		}	
			
		// opt 1 - per� deve essere abilitata la restrizione che impedisce di chiedere pi� di un REFERRAL! - deve comparire un messaggio invece del FORM
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-two' );			//EXAMPLE --> REFERRAL		- SCREEN 2
				
		// opt 2 - lo SCREEN 5 
		bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-five' ); 		//SCREEN 5       EXAMPLE --> REFERRAL	
	
	}	
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	ACCETTA
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_actions', 'accetta_referral' );

/**
 *
 */
function accetta_referral() 
{
	global $bp;
	
	if ( isset( $_POST['accetta-referral'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( 'accetta-referral' );			

		// [POST_vars]
		$id_post 			= $_POST['id-post'];					
		$tipologia_rapporto	= $_POST['tipologia'];	 
		$anzianita_rapporto	= $_POST['anzianita'];	 
		$utente_consigliato	= $_POST['consigliato'];	 		
		$voto_complessivo 	= $_POST['voto-complessivo'];			
			
		//	
		// NB: tutti gli IF fanno REDIRECT su (SCREEN 4)
		//
		
		if ( empty($tipologia_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica la tipologia del rapporto commerciale', 'referrals' ),'error' );					
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-four' );			//EXAMPLE --> REFERRAL		- SCREEN 4  
			//return;
		}		
	
		if ( empty($anzianita_rapporto)) 	//empty
		{
			bp_core_add_message( __( 'Indica anzianita del rapporto commerciale', 'referrals' ),'error' );					
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-four' );			//EXAMPLE --> REFERRAL	- SCREEN 4  
			//return;
		}		
	
		if ( empty($utente_consigliato)) 	//empty
		{
			bp_core_add_message( __( 'Raccomanderesti questo utente?', 'referrals' ),'error' );					
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-four' );			//EXAMPLE --> REFERRAL	- SCREEN 4  
			//return;
		}		
		
		if (bp_ref_check_voto( $voto_complessivo ) ) 	//bp_ref_check_voto
		{
			bp_core_add_message( __( 'Assegna un voto complessivo per l utente', 'referrals' ),'error' );					
			bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-four' );			//EXAMPLE --> REFERRAL   - SCREEN 4  
			//return;
		}		
		

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//ricava il DESTINATARIO
		$obj_post 		 = get_post($id_post);			
		$post_author_id  = $obj_post->post_author;
				
		//		
		$from_user_id = bp_displayed_user_id(); // oppure --> bp_loggedin_user_id()			(forse concettualmente � meglio la seconda!)
		$to_user_id   = $post_author_id;				
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		// FUNCTION call 
		$result = bp_ref_accept_referral_request 
		(
				$id_post
				, 	$from_user_id
				,	$to_user_id
			, 	$tipologia_rapporto
			, 	$anzianita_rapporto 
			, 	$utente_consigliato
			,	$voto_complessivo
		);	
		
		if($result)	
		{				
			//bp_core_add_message( __( 'referral accettato e pubblicato' . 'ID POST: ' . $id_post, 'referrals' ) );
			bp_core_add_message( __( 'Referral pubblicato','referrals' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'errore accettazione referral (notifica e activity non inviate)', 'referrals' ) );			
		}	

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//opt 1 - va (SCREEN 1) - Le REFERRAL CONFERMATE/ACCETTATE  da  ME
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-one' );			//EXAMPLE --> REFERRAL	- SCREEN

		//opt 2 - va sullo (SCREEN 4) -  Le REFERRAL da MODERARE - ha senso!
		bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-four' );			//EXAMPLE --> REFERRAL	- SCREEN 4
				
		//------Vd funzione successiva			
			//REDIRECT su Screen da COSTRUTIRE
				//bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen- ' );			//EXAMPLE --> REFERRAL		-Screen 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
	}	
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
function accetta_referral() 
{

	global $bp;
	
	if ( isset( $_POST['accetta-referral'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( 'accetta-referral' );			

		$id_post = $_POST['id-post'];					
		
														
													//REDIRECT su Screen 6
													bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-six' );			//EXAMPLE slug		-Screen 6
														
	}	
}

add_action( 'bp_actions', 'accetta_referral' );
*/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	RIFIUTA
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_actions', 'rifiuta_referral' );

/**
 *
 */
function rifiuta_referral() 
{
	global $bp;
	
	if ( isset( $_POST['rifiuta-referral'] ) )		
	{		
		// [WPNONCE]
		check_admin_referer( 'rifiuta-referral');			

		// [POST_vars]
		$id_post = $_POST['id-post'];		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//ricava il DESTINATARIO
		$obj_post 		 = get_post($id_post);			
		$post_author_id  = $obj_post->post_author;
				
		//		
		$from_user_id = bp_displayed_user_id(); // oppure --> bp_loggedin_user_id()	
		$to_user_id   = $post_author_id;		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// FUNCTION call 
		$result = bp_ref_deny_referral_request ($id_post, $from_user_id ,$to_user_id ) ;		
						
		if($result)
		{				
			// ---> BUG: non compare! msg troppo lungo?!
			bp_core_add_message( __( 'referral rifiutato e cestinato'. 'ID POST: ' . $id_post, 'referrals' ) );
		}
		else 
		{			
			bp_core_add_message( __( 'errore rifiuto referral (notifica e activity non inviate)', 'referrals' ) );			
		}	
			
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//opt 1 - reindirizza sulla Nuova TAB 'screen-six' (SCREEN 6) - Le REFERRAL RIFIUTATE da ME
			//bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-six' );	// SCREEN 6     EXAMPLE --> REFERRAL 
		
		//opt 2 - per ora ritorna sullo (SCREEN 4) 
		bp_core_redirect( bp_displayed_user_domain() . bp_get_example_slug() . '/screen-one' );			//SCREEN 1     EXAMPLE --> REFERRAL		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}	
}
?>