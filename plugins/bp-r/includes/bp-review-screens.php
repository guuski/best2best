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
		
	- [PHP file]	tutti in 'includes/' 
	
	- [PHP Class]	
	
		'Review'
	
	- [PHP Function]
		
		'bp_is_review_component()' in 'bp-review-classes.php' (classe Review)

-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	add_action()
	do_action()
	apply_filters()

-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_update_is_directory()
	bp_current_action()
	
	bp_core_load_template()
	
	
	
	
	bp_screens	-->	 bp_review_directory_setup
	
-----------------------------------------
global $bp
-----------------------------------------


*/


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	screen SCREEN-ONE (LISTA REVIEWS)- 1/3
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
/**
 * bp_review_screen_one()
 * 
 */
function bp_review_screen_one() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_one' );													
		
	//cancella le notifiche di review dell'utente
	//bp_core_delete_notifications_by_type(bp_loggedin_user_id(), $bp->review->id, 			'----NOME NOTFICA: new_review----		');	
	
	//PHP Notice: 
		// bp_core_delete_notifications_for_user_by_type è <strong>deprecata</strong> dalla versione 1.5! 
		// Utilizzare al suo posto bp_core_delete_notifications_by_type(). in C:\Programmi\Apache Software Foundation\Apache2.2\htdocs\best2best\wp-includes\functions.php on line 3467
		
	//carica 'screen_one.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_one', 'review/screen-one' ) );				  		//FILTER - non usato da nessuno
}
	
	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	screen SCREEN-TWO (SCRIVI REVIEW) - 2/3
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
/**
 * bp_review_screen_two()
 * 
 */
function bp_review_screen_two() 
{
	global $bp;
	
	// DO ACTION
	do_action( 'bp_review_screen_two' );												
		
	//carica 'screen_two.php'
	bp_core_load_template( apply_filters( 'bp_review_template_screen_two', 'review/screen-two' ) );					//FILTER - non usato da nessuno
	
}


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	
//	screen SCREEN-THREE (REVIEW SCRITTE) - 3/3
//
//	assegnata dentro il METODO setup_nav() del COMPONENTE Review nel FILE 'bp-review-loader.php'
//
//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	

/**
 * bp_review_screen_three()
 * 
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
//		NOTE sulle  funzioni SCreen
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	
/*

	NOTA BENE:
	
		è collegata la seguente riga nel FILE delle notifiche
			//add_action( 'bp_review_screen_one', 'bp_review_remove_screen_notifications' );		 //ACTION del plugin -- 'screen_one'
		
	
	come era nel vecchio PLUGIN la funzione Screen ONE:

		{
			bp_reviews_delete_between_user_by_type(bp_loggedin_user_id(), bp_displayed_user_id(),$bp->reviews->id);		
			
			add_action('bp_template_content',array(&$this,'home_content'));													
			
					---> 
					function home_content()
					{
						bp_reviews_load_template('reviews/review-loop.php');												
					}
	
			bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));					
		}
		
		
	
	//function screen_write()
	
		//add_action('bp_template_content','write_content');	
		/*
					---> 
				
					function write_content()
					{
						bp_reviews_post_form();																				//[T]	vd (S1)
					}
					
        */					
		//bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));				 		


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
//  non le usa pennnnniente!		---BOH!
//
// - forse servono nel caso non ci caricano i FILE DI TEMPLATE della cartella review espicitamente!
// - la 2 ha lo stesso contenuto del FILE di template REVIEW LOOP!					 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
function bp_review_screen_one_title() 
{
	//_e( 'Screen One', 'reviews' );
	
	_e( 'SLA LA LA LA LA e', 'reviews' );
}

function bp_review_screen_one_content() 
{
				
/*
	global $bp;

	$reviews = bp_review_get_reviews_for_user( $bp->displayed_user->id );	
	$send_link = wp_nonce_url( $bp->displayed_user->domain . $bp->current_component . '/screen-one/send-rw', 'bp_review_send_review' );
?>
	<h4><?php _e( 'Welcome to Screen One', 'reviews' ) ?></h4>
	<p><?php printf( __( 'Send %s a <a href="%s" title="Send Review!">review!</a>', 'reviews' ), $bp->displayed_user->fullname, $send_link ) ?></p>

	<?php if ( $reviews ) : ?>
		<h4><?php _e( 'Received Reviews', 'reviews' ) ?></h4>

		<table id="reviews">
			<?php foreach ( $reviews as $user_id ) : ?>
			<tr>
				<td width="1%"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 25, 'height' => 25 ) ) ?></td>
				<td>&nbsp; <?php echo bp_core_get_userlink( $user_id ) ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
	
	<?php
*/

}



//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//  una volta che la DIRECTORY è disabilitata non serve più a niente  ---> lo stesso vale per il TEMPLATE index.php (*)
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * 
 *
 * @package BuddyPress_Template_Pack
 *
 */
 
	function bp_review_directory_setup() 
	{
		/*
		if ( bp_is_review_component() && !bp_current_action() && !bp_current_item() ) 
		{
			bp_update_is_directory( true, 'review' );			
			do_action( 'bp_review_directory_setup' );
			bp_core_load_template( apply_filters( 'review_directory_template', 'review/index' ) );		// (*)
		}
		*/	
	}
	//add_action( 'bp_screens', 'bp_review_directory_setup' );
	
?>

