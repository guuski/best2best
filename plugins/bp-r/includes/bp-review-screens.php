<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

Questo file contiene le "screen functions":

le "screen functions" sono i controllers (nel modello Model View Controller) di BuddyPress. 
Vanno in esecuzione quando viene intercettato l'indirizzo URL a cui sono assegnate.
Interagiscono con le "business functions" (che costituiscono il Model) per manipolare o salvare informazioni
e poi mandano l'output a file di template (le View).
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------


-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	add_action()
	do_action()
	apply_filters()

-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_core_load_template()
		
-----------------------------------------
global $bp
-----------------------------------------


*/

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	SCREEN-ONE (LISTA REVIEWS) 
//   
//  assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 1
 */
function bp_review_screen_one() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_one' );													

	// [W] - PHP Notice
	// bp_core_delete_notifications_for_user_by_type � <strong>deprecata</strong> dalla versione 1.5! 
	// ---> Utilizzare al suo posto bp_core_delete_notifications_by_type(). in C:\Programmi\Apache Software Foundation\Apache2.2\htdocs\best2best\wp-includes\functions.php on line 3467
		
	//cancella le notifiche di review dell'utente
		//bp_core_delete_notifications_by_type(bp_loggedin_user_id(), $bp->review->id, 			'----NOME NOTFICA: new_review----		');	
			
	//carica 'screen_one.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_one', 'review/screen-one' ) );	//FILTER - non usato da nessuno
	
	//NB:
		//nome FILTRO diverso  
		//FILTER - non usato da nessuno	
}
	
	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	SCREEN-TWO (SCRIVI REVIEW)  
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 2
 */
function bp_review_screen_two() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_two' );												
		
	//carica 'screen_two.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_two', 'review/screen-two' ) );	//FILTER - non usato da nessuno	
}


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	SCREEN-THREE (REVIEW SCRITTE)  
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 3
 */
function bp_review_screen_three() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_three' );												
		
	//carica 'screen_three.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_three', 'review/screen-three' ) );					//FILTER - non usato da nessuno
}


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	SCREEN-FOUR (MODERA LE REVIEW NEGATIVE)  
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 4
 */
function bp_review_screen_four() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_four' );												
		
	//carica 'screen_four.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_four', 'review/screen-four' ) );						
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	SCREEN-FIVE ( review Negative in attesa di essere moderate ) 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 5
 */
function bp_review_screen_five() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_five' );												
		
	//carica 'screen_five.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_five', 'review/screen-five' ) );						
}
	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	SCREEN-SIX ( review ANONIME ) 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * SCREEN 6
 */
function bp_review_screen_six() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_six' );												
		
	//carica 'screen_six.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_six', 'review/screen-six' ) );						
}	
?>