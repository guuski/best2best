<?php
/** 
Plugin Name: BuddyPress User Reviews
Version: 0.0.2
Description: 
Plugin URI:
Revision Date: March 25, 2012
Requires at least: 
Tested up to: 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Andrea Sangiorgio
Author URI:

-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- dichiara le COSTANTI per il plugin.			

	- definisce la CLASSE 'BPUserReviewHelper'
			
		- istanzia un OGGETTO della CLASSE 'BP_Review_Component' e lo assegna alla var '$bp->reviews'
	
	- lancia l OGGETTO SIGLETON della  CLASSE 'BPUserReviewHelper' 	 [ BPUserReviewHelper::get_instance();]
	
-----------------------------------------
[C] Changes
-----------------------------------------

	[C] 1 - localizzazione lato ADMIN, nuovi parametri (pagine automatiche ARCHIVE,   )
	
	[C] 2 - bottone 'Add Review' -> add_action e metodo
	
	[C] 3 - METADATA per le review -> meta box (custom fields)
	
	[C] 4 - esplorazione HOOKS 

	[C] 5 - Giamba made
	
-----------------------------------------
[I] Planned Implementations 
	per le versione successive
-----------------------------------------	

	[I] 1 - spostare 'register_review_post_type' e funzioni meta box in una CLASSE + ABSTRACT o INTERFACE per la creazione del nuovo post_type
	
	[I] 2 - togliere 'add_action' dal Costruttore
	
	[I] 3 - togliere 'add_action' dal Costruttore
	
	[I] 4 - rinomina costante cambia BP_REVIEWS_PLUGIN_DIR --> BP_REVIEW_PLUGIN_DIR (REVIEWS -> REVIEW)
	
	[I] 5 - usa 'load_plugin_textdomain()' in luogo di 'load_textdomain()'
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	[PHP file]	 
			
		'review-component.php' in '/core'
	
	[PHP class]	
	
		'BP_Review_Component' 
		
	[PHP function]			
			
	[PHP constant]
	
		'BP_REVIEWS_PLUGIN_DIR'	
			
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	plugin_dir_path()
	plugin_dir_url()	
	add_action()
	is_multisite()
	is_main_site()
	register_post_type()
	...
	
	init	--->	register_post_type()
	.....
	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_loggedin_user_domain()
	bp_is_my_profile()
	bp_is_user()
	bp_is_current_component()
	....
	
	bp_loaded					--->	init_component
	bp_loaded					--->	load_textdomain
	bp_member_header_actions	--->    add_review_button
	
	.....
	
-----------------------------------------
GLOBALS: $bp, $wpdb, $creds
-----------------------------------------

	$bp->reviews	

*/
 
 

// Contiene il PATH alla cartella del plugin 
define('BP_REVIEWS_PLUGIN_DIR',  plugin_dir_path (__FILE__));


//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * 
 * 
 * istanzia un OGGETTO della CLASSE 'BP_Review_Component' e lo assegna alla var '$bp->reviews'
 *
 */
class BPUserReviewHelper
{      
	//
    private static $instance;
    
	/**
	 * get_instance()
	 *
	 */
    public static function get_instance()
	{
        if (!isset(self::$instance)) 		
            self::$instance = new self();
        
        return self::$instance;
    }
	
	/**
	 * __construct() 
	 *
	 */    
    private function __construct() 
	{        
		// Usando l' hook 'bp_loaded' ci si assicura che BP_Review_Component venga caricato dopo i core components di BuddyPress	
        add_action( 'bp_loaded'		  			, array(&$this , 'init_component'), 0);
        add_action( 'init'			  			, array(&$this , 'register_review_posttype'));     
        add_action( 'bp_loaded'		  			, array(&$this , 'load_textdomain'), 2);
        add_action( 'wp_print_scripts'  		, array(&$this , 'add_css'));						//[C] 5
        add_action( 'wp_print_scripts' 			, array(&$this , 'add_js'));		
		add_action( 'bp_member_header_actions'	, array(&$this , 'add_review_button'));				//[C] 1 - aggiunge il bottone 'Add Review'
		
		//add_action( 'init'  					, array(&$this , 'button_modifications', 9);		//[C] 3 		
		//add_action( 'add_meta_boxes'			, array(&$this , 'add_review_meta_box'));			//[C] 2 - posizione [ALT]
    }
  
	/**
	 * init_component()
	 *
	 * Carica il componente BP_Review_Component in $bp global
	 *
	 * 
	 *
	 */    
    function init_component()
	{
        global $bp;

        include_once BP_REVIEWS_PLUGIN_DIR.'core/review-component.php';

		//NB: usa get_istance perchè anche BPUserReview è una CLASSE SINGLETON
        $bp->reviews = BPUserReview::get_instance(); 		
    }   

	/**
	 * is_review_component()
	 *
	 */    
    function is_review_component()
	{
        global $bp;
    
		return bp_is_current_component($bp->reviews->root_slug);
    }
    
	/**
	 * register_review_posttype()															[C] 1 - changed parametri 'regiter_post_method'
	 *
	 * Registra un nuovo tipo di post per le review
	 *
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */  
	function register_review_posttype()
	{
		//
		if(is_multisite()&&!is_main_site())
			return;
       	  
		//
		register_post_type( 'ureviews', array 
			(
				'labels' => array
					(											 
						 //[?] *wS
						'name' 					=> __( 'Reviews','reviews' )	
					,	'singular_name' 		=> __( 'Review','reviews' )		
//					,   'singular' 				=> __( 'Review', 'reviews' )			
					
					// the menu item for adding a new post
					,	'add_new'				=> __( 'Nuova Review','reviews' )							
					
					// the header shown when creating a new post
					,	'add_new_item'			=> __( 'Scrivi una nuova Review','reviews' )				
					,	'edit'					=> __( 'Modifica la Review','reviews' )						
					,	'edit_item'				=> __( 'Modifica Review','reviews' )						
					
					// shown in the favorites menu in the admin header
					,	'new_item'				=> __( 'Scrivi Review','reviews' )							
					
					,	'view'					=> __( 'Vedi la Review','reviews' )							
					,	'view_item'				=> __( 'Vedi Review','reviews' )							
					,	'search_items'			=> __( 'Cerca Review','reviews' )							
					,	'not_found'				=> __( 'Review non trovata','reviews' )						
					,	'not_found_in_trash'	=> __( 'Review non trovata nel cestino','reviews' )			
					
//					,	'parent'				=> __( '','reviews' )							   //non serve

				)//chiude LABELS
				
				// ARCHIVE page
				// associa TEMPLATE....ma anche no!
				// associa la pagine REVIEWS
				//
				, 'has_archive'   		=> 'reviews' 												// era 'non presente' ---> [ST] cap4 - pag 98	- WPAnthology			
																							
				
				// 
				, 'description'			=> __('Le Review vengono scritte da utenti.....','reviews') //non usata				
					
//				, 'hierarchical'																	//non serve				
				
				// PUBLIC
				// if False --> show_ui(F), publicly_queryable (F), exclude_from_search(T), show_in_nav_menus(F)
				// if True  --> show_ui(T), publicly_queryable (T), exclude_from_search(F), show_in_menu(T)
				, 'public' 				=> true																	

				, 'show_ui'				=> true																											
				, 'show_in_menu' 		=> true
				, 'show_in_nav_menus' 	=> true

				// QUERY related 1
				, 'publicly_queryable'  => true														// 
				
				// SEARCH
//				, 'exclude_from_search  => false													//
	
				// QUERY related 2
				, 'query_var' 			=> true														// 
								
				// ICONA
				, 'menu_icon'			=> plugins_url('icons/favicon.ico',__FILE__)				 // [ALT] get_stylesheet_directory_uri() . '/icons/favicon.ico'
								
				// POSIZIONE																				
				, 'menu_position'		=> 3														// era 80 --> 3 (prima di Articoli/Post)
								
				// REWRITE - SLUG
				, 'rewrite' 			=> array('slug' => 'ureviews')								// [ST] [?] Ur*?! cambiare?!
				
				// SUPPORT 
				// aggiunti 'excerpt' e 'custom_fields'	- in prova		
				, 'supports' 			=> array('title','editor','author','comments','custom_fields','excerpt')   		
				
				// Export
//				, 'can_export'		  	=> true
				
				// CUSTOM FIELDS - META BOXES -	in prova
				, 'custom-fields'		 => true				//anche se False è possibile aggiungere custom fields 				
				, 'register_meta_box_cb' => true				//You can create a custom callback function that is called when the meta boxes for the post form are set up.
			)
		);
	}
   
   	/**
	 *
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	 */
	function add_css()
	{
		//if(self::is_review_component())														//[C] 5
			wp_enqueue_style ('review',  plugin_dir_url (__FILE__).'/inc/review.css');
	}  
	 
	/**
	 *
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 */ 
	function add_js()
	{
		if(self::is_review_component())																	
			wp_enqueue_script ('review-js',plugin_dir_url(__FILE__).'inc/review.js',array('jquery'));
	}

 	/**
	 *					
	 *	[I] 5 - load_plugin_textdomain()
	 *
	 */
	function load_textdomain() 																				
	{
		load_plugin_textdomain( 'reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	
//--------------------------------------------------------------------------------------------------------------------------------------------------		
// 	[C] 2
//--------------------------------------------------------------------------------------------------------------------------------------------------

	/**	 
	 * 	
	 * [I] - Implementa -> CSS per <div e <a
	 *
	 * [T] - Traduci  title, cioè tooltip!  --> OK
	 * [T] - Traduci  testo bottone!		--> OK
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
					href="'.bp_get_displayed_user_link().'reviews/create#user-activity"
				>				
					'.__('Add Review','reviews').'
				</a>
			</div>';
		}
	}

//-----------------------
// 	[C] 4
//-----------------------
	function button_modifications() 															
	{	
		remove_action('bp_member_header_actions','bp_send_public_message_button');
	}


//-------------------------------------------------------------------------------------------------------------------------------------------------------------------	
}//chiude la CLASSE 'BPUserReviewHelper'



//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//												[C] 3  - META BOXES o CUSTOME FIELDS
//
// http://codex.wordpress.org/Custom_Fields
//
// wptheming.com/2010/08/custom-metabox-for-post-type/
// wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/
//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//				
// Warning:    call_user_func_array() expects parameter 1 to be a valid callback, 
//             no array or string given in ...wordpress_2_buddy\wp-includes\plugin.php 
//
// on line 405
//


add_action( 'add_meta_boxes', 'add_review_meta_box');	
//add_action( 'admin_init', 'add_review_meta_box');					//[ALT]


	/**
	 * 
	 * 
	 */
	function add_review_meta_box() 															
	{	
		add_meta_box
		(		'review-details'		 // CSS_ID
			, 	'Dettagli Review'		 // title
			, 	'show_review_meta_box' 	 // callback Function name
			, 	'ureviews' 				 // page_paramater = post_type
			,	'normal'				 // context	
			,	'high'					 // piority
			//, 	1					 // callback Args - [?]
		);		
	}
		
	function show_review_meta_box_prova() 															
	{	
		echo 'benvenuti';
	}
	
	function show_review_meta_box_prova_2( $post ) 							
	{
		global $post;
		$custom = get_post_custom($post->ID);
		//....
	
	}
	
	/**
	 *
	 */
	function show_review_meta_box( $post ) 							
	{
		/*
			//UNDERSCORE nei nomi delle variabili --- perchè?
			
			1)
			if your metadata name starts with an UNDERSCORE it does not display the default custom fields meta box
			in WP. This can help eliminate confusion by the user when entering metadata
		
			2)
		*/
	
		//retrieve the meta data values if they exist
		$boj_mbe_name = get_post_meta( $post->ID, '_boj_mbe_name', true );
		$boj_mbe_costume = get_post_meta( $post->ID, '_boj_mbe_costume', true );

		echo 'Please fill out the information below';
		?>
			<p>Name: 
				<input 
					type  = "text" 
					name  = "boj_mbe_name" 
					value = "<?php echo esc_attr( $boj_mbe_name ); ?>" 
				/>
			</p>
			
			<p>Costume: 
				<select name = "boj_mbe_costume">
					<option value = "vampire" 	<?php selected( $boj_mbe_costume, 'vampire' ); ?>	>Vampire </option>
					<option value = "zombie" 	<?php selected( $boj_mbe_costume, 'zombie' );  ?>	>Zombie	 </option>
					<option value = "smurf" 	<?php selected( $boj_mbe_costume, 'smurf' );   ?>	>Smurf	 </option>
				</select>
			</p>
		<?php	
	}

	
//hook to save the meta box data
add_action( 'save_post', 'save_review_meta_box' );

/**
 *
 */
function save_review_meta_box( $post_id ) 
{

	//verify the meta data is set
	if ( isset( $_POST['boj_mbe_name'] ) ) 
	{	
		//save the meta data
		update_post_meta( $post_id, '_boj_mbe_name', 	strip_tags( $_POST['boj_mbe_name'] ) );
		update_post_meta( $post_id, '_boj_mbe_costume', strip_tags( $_POST['boj_mbe_costume'] ) );	
	}

}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------






//
BPUserReviewHelper::get_instance();

?>