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
//	
//	screen SCREEN-ONE 
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Referral nel FILE 'bp-referral-loader.php'
//
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	


/**
 * bp_referral_screen_one()
 * 
 */
function bp_referral_screen_one() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_referral_screen_one' );													
			
	//carica 'screen_one.php'
	bp_core_load_template( apply_filters( 'bp_referral_template_screen_one', 'referral/screen-one' ) );				  		//FILTER - non usato da nessuno
}
	
	
?>