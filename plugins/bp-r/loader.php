<?php
/*
Plugin Name: bp-review
Plugin URI:
Description: 
Version: 0.0.1
Revision Date: MMMM DD, YYYY
Requires at least: 
Tested up to: 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: 
Author URI:
Network: true
*/

/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- dichiara le COSTANTI per il plugin
	- include (con la funz 'require') il file 'bp-review-loader.php'

-----------------------------------------
FILE, CLASSI, OGGETTI collegati 
-----------------------------------------
		
	- [PHP file] 
		'bp-review-loader.php' in 'includes/' 
			
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------		
	'bp_include'	--->	bp_review_init()
	

*/

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// COSTANTI 
//--------------------------------------------------------------------------------------------------------------------------------------------------		
	
// 1 - Contiene il path alla cartella del plugin 
define( 'BP_REVIEW_PLUGIN_DIR', dirname( __FILE__ ) );

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// INIT
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'bp_include', 'bp_review_init' );

/**
 * Carica il plguin solo se BuddyPress e' presente.
 * 
 */
function bp_review_init() 
{
	// poiche' il plugin usa BP_Component, richiede la versione di BP 1.5 o successiva.
	if ( version_compare( BP_VERSION, '1.5', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-review-loader.php' );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// LOCALIZZAZIONE
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'init', 'bp_review_load_my_textdomain');

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function bp_review_load_my_textdomain()
{
	load_plugin_textdomain( 'reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//  "ADD REVIEW" button
//
// 	IMPORTANTE:	il link! ---> review/screen-two			SCREEN-TWO  o CREATE 
//--------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_member_header_actions'	, 'bp_review_add_review_button',1);	
		
/**	 
 * 	
 *
 */
function bp_review_add_review_button()																	
{
	if(			bp_review_current_user_can_write() 
			&& !bp_review_current_user_can_moderate() //l'utente "Staff-Recensioni-Best2best" non ha necessità di scrivere Review
			&& !bp_review_displayed_user_is_staff_member() //l'utente "Staff-Recensioni-Best2best" non ha necessità di scrivere Review
			&& !bp_review_loggedin_user_is_staff_member() //l'utente "Staff-Recensioni-Best2best" non ha necessità di scrivere Review
		) 
	{
		echo '
		<div class = "add-reviews" >
		<a
		class = "add-reviews button"
		title = "'.__('Add Review','reviews').'"
		href="'.bp_get_displayed_user_link().'review/screen-two#user-activity"								 
		>
		'.__('Add Review','reviews').'
		</a>
		</div>';
	}
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//  "ADD LIST_REVIEW" button  (?x)
//--------------------------------------------------------------------------------------------------------------------------------------------------		


add_action( 'bp_directory_members_actions'	, 'bp_review_add_list_review_button',100);

/*
 *
 */
function bp_review_add_list_review_button()
{ 
	global $members_template;
	
	if(is_user_logged_in()  && $members_template->member->id != bp_loggedin_user_id())
	{		
		$write_rev_href= bp_core_get_user_domain($members_template->member->id);
		echo '
		<div class = "add-reviews" style="float:right; background: url(/wp-content/plugins/bp-review/includes/img/alt_star.gif) right center no-repeat;">
		<a class = "add-reviews button"	title = "'.__('Add Review','reviews').'"
		href="'.$write_rev_href.'review/screen-two#user-activity"
		>'.__('Add Review','reviews').'&nbsp;&nbsp;&nbsp;</a></div>';
	}
}

//--------------------------------------------------------------------------------------------------------------------------------------------------		
// Aggiunge i CSS
//
// ----il datepicker di jQuery è stato staccato?
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'wp_print_scripts' , 'bp_review_add_css');					

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 * @see http://codex.wordpress.org/Function_Reference/wp_register_style
 */
function bp_review_add_css()																						//---usa il metodo add_JS
{
	//if(self::is_review_component() 
	//{							
		//Review FILE
		wp_enqueue_style ('review',  plugin_dir_url (__FILE__).'/includes/review.css');
			
		//DATEPICKER - Css (vd la funz sotto...)	
			//wp_register_style('datepicker-css', plugin_dir_url (__FILE__). 'includes/ui-lightness/jquery-ui-1.8.19.custom.css');  
			//wp_enqueue_style( 'datepicker-css');  
	//}		
}  

//-----------------------------------------------------------------------------------------------------------------------------------------------
// Aggiunge il Javascript
//
// ----il datepicker di jQuery è stato staccato?
//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_head' , 'bp_review_add_js');					

/*
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style   (rimuovi...)
 * @see http://codex.wordpress.org/Function_Reference/wp_register_style  (rimuovi...)
 * @see http://codex.wordpress.org/Function_Reference/wp_register_script
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */
function bp_review_add_js()
{	
	//DATEPICKER - Css  (sposta nella fuzione "Aggiunge il CSS) ------ (rimuovi...)
	wp_register_style('datepicker-css', plugin_dir_url (__FILE__). 'includes/ui-lightness/jquery-ui-1.8.19.custom.css');  
    wp_enqueue_style( 'datepicker-css');  
	
	//DATEPICKER - Js
	wp_register_script('datepicker-js',  plugin_dir_url (__FILE__). 'includes/jquery-ui-1.8.19.custom.min.js');  
    wp_enqueue_script( 'datepicker-js');  
	
	//Review FILE
	wp_enqueue_script('review',  plugin_dir_url (__FILE__).'/includes/review.js');
	
	//JQUERY
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');		
}  

//-----------------------------------------------------------------------------------------------------------------------------------------------
// show_points 1
//-----------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_before_member_header_meta'	, 'bp_review_show_points',1);

/*
 *
 */
function bp_review_show_points() 
{
	$points = get_the_author_meta('media_voto_review',bp_displayed_user_id());
	
	if($points != '') {
		?>
		<div id="new-review-rating" style="border: 1px solid #CCC;display: inline-block;">		
		<div class="rating-container"><span class="rating-title" style="width:auto;"><?php _e( 'Punteggio medio utente', 'reviews' ); ?></span> <ul class='star-rating'>	
			<li class='current-rating' style="width: <?php echo 25*$points;?>px"></li></ul>
		</div>	</div>
		<?php 
	}
}

//-----------------------------------------------------------------------------------------------------------------------------------------------
// show_points 2
//-----------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_directory_members_item'	, 'bp_review_show_points_members_directory',1);

/*
 *
 */
function bp_review_show_points_members_directory() 
{
	
	$points = get_the_author_meta('media_voto_review',bp_get_member_user_id()); //
	
	if($points != '') 
	{	
		?><ul class='star-rating'><li class='current-rating' style='height: 0px; padding-top: 10px; border: 0; width: <?php echo 25*$points;?>px'></li>	</ul>
		<?php 
	}

}

//-----------------------------------------------------------------------------------------------------------------------------------------------
// show_points 3
//-----------------------------------------------------------------------------------------------------------------------------------------------

add_action("bp_after_members_loop","bp_review_move_member_points");

/*
 *
 */
function bp_review_move_member_points() 
{
	?><script>
	jQuery(document).ready(function(){
		jQuery(".item-title").children("a").after(function(){
			return jQuery(this).parent().nextAll(".star-rating");});
		});
	</script>
	<?php 
}

?>