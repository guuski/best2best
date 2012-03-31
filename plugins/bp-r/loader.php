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
	
//contiene il path alla cartella del plugin 
define( 'BP_REVIEW_PLUGIN_DIR', dirname( __FILE__ ) );

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

//
add_action( 'bp_include', 'bp_review_init' );


//can user write review
function review_current_user_can_write(){
	$can_write=false;
	 
	if(is_user_logged_in()&&!bp_is_my_profile()&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id()))
		$can_write=true;

	return apply_filters('bp_reviews_can_user_write',$can_write);
}
//--------------------------------------------------------------------------------------------------------------------------------------------------		
// 	IMPORTANTE 
//	il link! ---> review/screen-one
//  STYLE style = " float:left;  position:relative;  top:-5px " 
//--------------------------------------------------------------------------------------------------------------------------------------------------

	/**	 
	 * 	
	 *
	 */
	function add_review_button()																	
	{
		if(review_current_user_can_write())
		{
			echo '
			<div class = "add-reviews" >
			<a
			class = "add-reviews button"
			title = "Scrivi una Review per l\'utente."
			href="'.bp_get_displayed_user_link().'review/screen-one#user-activity"
			>
			'.__('Add Review','reviews').'
			</a>
			</div>';
		}
	}

add_action( 'bp_member_header_actions'	, 'add_review_button',1);				//[C] 1 - aggiunge il bottone 'Add Review'

add_action( 'init', 'load_my_textdomain');
function load_my_textdomain()
{
	load_plugin_textdomain( 'reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 *
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function add_css()
{
// 	if(self::is_review_component())														//[C] 5
		wp_enqueue_style ('review',  plugin_dir_url (__FILE__).'/includes/review.css');
}  

add_action( 'wp_print_scripts'  		, 'add_css');						//[C] 5


?>
