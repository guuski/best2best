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
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		
	
//contiene il path alla cartella del plugin 
define( 'BP_REVIEW_PLUGIN_DIR', dirname( __FILE__ ) );

//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
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
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		

add_action( 'init', 'load_my_textdomain');


/**
 *
 * @see http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function load_my_textdomain()
{
	load_plugin_textdomain( 'reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
//	---> SPOSTA nel file 'bp-review-functions.php'
//--------------------------------------------------------------------------------------------------------------------------------------------------		

	//can user write review
	function review_current_user_can_write()
	{
		$can_write=false;
		 
		if(is_user_logged_in()&&!bp_is_my_profile()
			//	&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id())
			)
			$can_write=true;

		return apply_filters('bp_reviews_can_user_write',$can_write);
	}


//--------------------------------------------------------------------------------------------------------------------------------------------------		
// 	IMPORTANTE 
//
//	il link! ---> review/screen-two			SCREEN-TWO  o CREATE 
//
//--------------------------------------------------------------------------------------------------------------------------------------------------


add_action( 'bp_member_header_actions'	, 'add_review_button',1);				

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
		href="'.bp_get_displayed_user_link().'review/screen-two#user-activity"								 
		>
		'.__('Add Review','reviews').'
		</a>
		</div>';
	}
}



//--------------------------------------------------------------------------------------------------------------------------------------------------		
//
//--------------------------------------------------------------------------------------------------------------------------------------------------		


add_action( 'wp_print_scripts'  		, 'add_css');					

/**
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function add_css()																						//---usa il metodo add_JS
{

	//CSS
	
//if(self::is_review_component())										
	wp_enqueue_style ('review',  plugin_dir_url (__FILE__).'/includes/review.css');
	
	
	
/*	
	wp_register_style('datepicker-css', plugin_dir_url (__FILE__). 'includes/ui-lightness/jquery-ui-1.8.19.custom.css');  
    wp_enqueue_style( 'datepicker-css');  
	
	wp_register_script('datepicker-js',  plugin_dir_url (__FILE__). 'includes/jquery-ui-1.8.19.custom.min.js');  
    wp_enqueue_script( 'datepicker-js');  
	
	//JS
	wp_enqueue_script('review',  plugin_dir_url (__FILE__).'/includes/review.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	//wp_enqueue_script('jquery-ui-core.min');
	//wp_enqueue_script('jquery-ui-datepicker.min');
	
	//wp_enqueue_script('jquery-ui-datepicker',  FILE_URL . 'jquery.ui.datepicker.js', array('jquery','jquery-ui-core') );
	//wp_enqueue_script('jquery-ui-datepicker', plugin_dir_url (__FILE__).'/includes/jquery.ui.datepicker.js', array('jquery','jquery-ui-core') );
	
*/		
}  


//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_head' , 'add_js_review');					

function add_js_review ()
{	
	wp_register_style('datepicker-css', plugin_dir_url (__FILE__). 'includes/ui-lightness/jquery-ui-1.8.19.custom.css');  
    wp_enqueue_style( 'datepicker-css');  
	
	wp_register_script('datepicker-js',  plugin_dir_url (__FILE__). 'includes/jquery-ui-1.8.19.custom.min.js');  
    wp_enqueue_script( 'datepicker-js');  
	
	//JS
	wp_enqueue_script('review',  plugin_dir_url (__FILE__).'/includes/review.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	//wp_enqueue_script('jquery-ui-core.min');
	//wp_enqueue_script('jquery-ui-datepicker.min');
	
	//wp_enqueue_script('jquery-ui-datepicker',  FILE_URL . 'jquery.ui.datepicker.js', array('jquery','jquery-ui-core') );
	//wp_enqueue_script('jquery-ui-datepicker', plugin_dir_url (__FILE__).'/includes/jquery.ui.datepicker.js', array('jquery','jquery-ui-core') );
		
}  


//-----------------------------------------------------------------------------------------------------------------------------------------------
// SOSTITUISCI con versione Giamba e ATTIVA! 
// ---> SPOSTA in bp-review-loader
//-----------------------------------------------------------------------------------------------------------------------------------------------

//add_action( 'bp_before_comments', 'show_comments',1);

function show_comments() 
{


/*
	$destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true );

	if(
			bp_loggedin_user_id() == $post->post_author 
		||  bp_loggedin_user_id() == $destinatario_review_id
	)  
	{
		echo 'COMMENTI version 1';
		comments_template();
	}		
	else 
	{
		echo 'COMMENTI version 2';
		comments_template();
		echo '<div id="respond" style = "display: none" > <div>';
	}
*/	
}


//-----------------------------------------------------------------------------------------------------------------------------------------------
// ---> SPOSTA in bp-review-loader
//-----------------------------------------------------------------------------------------------------------------------------------------------
/*
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { 

	//$num_review_ricevute = get_post_meta( $user->ID, 'num_review_ricevute', true );
	//$media_voto_review 	 = get_post_meta( $user->ID, 'media_voto_review', true );	
?>

	<h3>Extra profile information</h3>
	
	

	<table class="form-table">

		<tr>
			<th><label for="num_review_ricevute"> NUM Review Ricevute</label></th>

			<td>
				<input type="text" name="num_review_ricevute" id="num_review_ricevute" value="<?php echo esc_attr( get_the_author_meta( 'num_review_ricevute', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">inserisci num_review_ricevute</span>
			</td>
		</tr>
		
		<tr>
			<th><label for="media_voto_review">Media Voto Review</label></th>

			<td>
				<input type="text" name="media_voto_review" id="media_voto_review" value="<?php echo esc_attr( get_the_author_meta( 'media_voto_review', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">inserisci Media Voto Review</span>
			</td>
		</tr>
		


	</table>
<?php }
*/

//-----------------------------------------------------------------------------------------------------------------------------------------------
// ---> SPOSTA in bp-review-loader
//-----------------------------------------------------------------------------------------------------------------------------------------------
/*
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

		
	if ( isset( $_POST['media_voto_review'] ) ) 		
		update_usermeta( $user_id, 'media_voto_review', strip_tags($_POST['media_voto_review']));		//striptags
		
	if ( isset( $_POST['num_review_ricevute'] ) ) 		
		update_usermeta( $user_id, 'num_review_ricevute', strip_tags($_POST['num_review_ricevute']));		//striptags
}
*/
//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'bp_before_member_header_meta'	, 'show_points',1);


function show_points() 
{
	$points = get_the_author_meta('media_voto_review',bp_displayed_user_id());
	
	if($points != '') {
		?>
		<div id="new-review-rating" style="border: 1px solid #CCC;display: inline-block;">		
		<div class="rating-container"><span class="rating-title" style="width:auto;"><?php _e( 'Punteggio medio utente', 'reviews' ); ?></span> <ul id="prezzo" class='star-rating'>	
			<li class='current-rating' style="width: <?php echo 25*$points;?>px"></li></ul>
		</div>	</div>
		<?php 
	}
}

//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------

//add_action( 'bp_review_data_after_save', $this );

//-----------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------


function show_points_members_directory() 
{
	//echo 'ciao ciao ciao ciao ciao ciao ciao ciao ';

	//$points = get_the_author_meta('media_voto_review',$current_user->ID); //
	$points = get_the_author_meta('media_voto_review',bp_get_member_user_id()); //
	
	//bp_get_member_user_id()
	//$user_id = $current_user->ID; 
	
	if($points != '') {
		?>
		<div id="new-review-rating" style="border: 1px solid #CCC;display: inline-block;">		
			<div class="rating-container"><span class="rating-title" style="width:auto;"><?php _e( 'Punteggio medio utente', 'reviews' ); ?></span> 
				<ul id="prezzo" class='star-rating'>	
					<li class='current-rating' style="width: <?php echo 25*$points;?>px"></li>
				</ul>
			</div>	
		</div>
		<?php 
	}

}
add_action( 'bp_directory_members_actions'	, 'show_points_members_directory',1);




?>


