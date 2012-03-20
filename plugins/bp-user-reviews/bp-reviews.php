<?php
/**
Plugin Name: BuddyPress User Reviews 
Plugin URI: 
Description: *********
Version: 0.0.2
Revision Date: *******
Requires at least: WP 2.9, BuddyPress 1.6
Tested up to: WP 2.9, BuddyPress 1.6
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Andrea Sangiorgio
Author URI: 
*/

/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- definisce la CLASSE 'BPUserReviewHelper' (in futuro 'UR_Helper')
	
	- dichiara le COSTANTI per il plugin
	
	- istanzia l'OGGETTO della CLASSE 'BPUserReviewHelper'	(in futuro 'UR_Helper')

-----------------------------------------
FILE, CLASSI e OGGETTI collegati 
-----------------------------------------
		
	- 	[PHP file]	 'review-component.php' in 'core/' 
			[PHP class]	 'BPUserReview'		  			(in futuro 'UR_Component')
		
	- 	[CSS file]	 'review.css' in 'inc/'	 			(in futuro 'ur.css')
	
	-	[JS file]	 'review.js' in 'inc/'				(in futuro 'ur.js')
	
	
*/ 

define('BP_REVIEWS_PLUGIN_DIR',  plugin_dir_path (__FILE__));


/**
 *
 * 	------------descrizione-------------
 * 	------------descrizione-------------
 * 	------------descrizione------------- 
 *
 *  ACTIONS usate bp_loaded (Bp) init, wp_print_styles, wp_print_scripts (Wp)
 * 	
 *	
 *
 *	get_istance()					---> OK	
 *	__construct()	 				---> 0.0.3 	alleggerire e creare nuovo metodo 'addActions' or 'addHOOKS'
 *  init_component()				---> 0.0.3	rinominare in 'init_ur_component'  
												cambia per usare le costanti UR_PLUGIN_DIR 
 *  is_ur_component()				---> OK
 *	register_review_posttype()		---> 0.0.3 	rinominare in 'register_custom_post' e dargli un argomento
 *	register_taxnomies()			---> 0.0.5 	
 *  add_css()						---> 0.0.3	cambia per usare le costanti UR_PLUGIN_DIR e UR_INC_DIR
 *  add_js()						---> 0.0.3	cambia per usare le costanti UR_PLUGIN_DIR e UR_INC_DIR
 *  load_textdomain() 				---> 0.0.3	confronto con SKELETON  ,  cambia per usare le costanti UR_PLUGIN_DIR 
 *  register_reviews_shortcode()	---> 0.0.4 	[IMPLEMENTARE] usa Tag per trovare REF
 *  reviews_shortcode() 			---> 0.0.4 	[IMPLEMENTARE] usa Tag per trovare REF
 * 
 */ 
class BPUserReviewHelper // (in futuro 'UR_Helper')
{
       
    private static $instance;
    
    public static function get_instance()
	{
        if (!isset(self::$instance)) 
		{          
            self::$instance = new self();
        }

        return self::$instance;
    }
    
	/**
     * 
	 * Private constructor  	 
	 *
	 * chiama 'add_action()' su 5 volte 4 HOOKS: 3 di Wp e 1 di Bp.
	 *	
	 *
	 */
    private function __construct() 
	{        
        add_action('bp_loaded',array(&$this,'init_component'),0);					//[HOOKS Bp] bp_loaded
        add_action('init',array(&$this,'register_review_posttype'));     					
																					//[HOOKS Wp] init, wp_print_styles, wp_print_scripts
        //[IMPLEMENTARE] 0.0.4
        //add_action('init',array(&$this,'register_reviews_shortcode'));
		
		//[IMPLEMENTARE] 0.0.5
		//ACTION per ------register_taxonomies() 
    
        add_action('bp_loaded', array(&$this,'load_textdomain'), 2 );
        add_action('wp_print_styles',array(&$this,'add_css'));
        add_action('wp_print_scripts',array(&$this,'add_js'));
    }
    
	/**
	 * 
	 * salva in "reviews" di "$bp" l'oggetto singleton "UR_Component"
	 *
	 * [INCLUDE]
	 * 		[PHP file]: 'review-component.php' in 'core/'
	 *		[PHP class]: 'UR_Component'
	 *
	 */       
    function init_component()
	{
        global $bp;
        include_once BP_REVIEWS_PLUGIN_DIR.'core/review-component.php';
        $bp->reviews=  BPUserReview::get_instance();
    }   

    
    function is_review_component()
	{
        global $bp;
        return bp_is_current_component($bp->reviews->root_slug);
    }
    
	/**
	 *
	 * registra un nuovo 'custom_type': e lo chiama 'ureviews' 
	 *
     * If your component needs to store data, it is highly recommended that you use WordPress
	 * custom post types for that data, instead of creating custom database tables.
	 *
	 * In the future, BuddyPress will have its own bp_register_post_types hook. For the moment,
	 * hook to init. 
	 * See BP_Example_Component::__construct().	 	 
	 *
	 * 
	 *
	 * 
	 * [FUNZIONE Wp] is_multisite()
	 * [FUNZIONE Wp] is_main_site()
	 * [FUNZIONE Wp] register_post_type()
	 *
	 *
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */    
	function register_review_posttype()
	{
       
       if(is_multisite()&&!is_main_site())
           return;
       
     register_post_type( 'ureviews',
    
    array(
      
      'labels' => array(
        'name' => __( 'User Review','reviews' ),
        'singular_name' => __( 'User Review','reviews' )
      ),
    
      'public' => false,
                        'has_archive' => false,
                        'menu_position'=>80,
                        'show_ui'=>true,
      
      'rewrite' => array('slug' => 'ureviews'),
                        'supports' => array('title','editor','author','comments')
    )
  );
   }
	/**
	 *
	 * [IMPLEMENTARE] 0.0.5
	 */
	function register_taxonomies() 
	{

	}  
  
function add_css()
{
  if(self::is_review_component())
    wp_enqueue_style ('review',  plugin_dir_url (__FILE__).'/inc/review.css');
}  
 
function add_js()
{
  if(self::is_review_component())
    wp_enqueue_script ('review-js',plugin_dir_url(__FILE__).'inc/review.js',array('jquery'));
}

 
 function load_textdomain() 
 {
  $locale = apply_filters( 'bp_reviews_textdomain_get_locale', get_locale() );
               
  if ( !empty( $locale ) ) 
  {
    $mofile_default = sprintf( '%slanguages/%s.mo', plugin_dir_path(__FILE__), $locale );              
    $mofile = apply_filters( 'bp_reviews_textdomain_mofile', $mofile_default );
    
        if ( file_exists( $mofile ) ) 
    {        
      load_textdomain( 'reviews', $mofile );
    }
  }
}

	/**
     *
	 *	[IMPLEMENTARE] 0.0.4
	 *
	 * 	Register the [reviews] shortcode.
	 *
	 */
	function register_reviews_shortcode()
	{		
		add_shortcode('reviews','reviews_shortcode');  
	}   
  	
	/**
     *	[IMPLEMENTARE] 0.0.4
	 *
	 */ 
	function reviews_shortcode() 
	{
		return 'ciao ciao ciao';				
	}       
	
}// chiude la CLASSE

//
BPUserReviewHelper::get_instance(); // (in futuro 'UR_Helper')

?>