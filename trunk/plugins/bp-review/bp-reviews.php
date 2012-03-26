<?php
/**
 * Plugin Name: BuddyPress User Reviews
 * Version: 0.1
 * Plugin URI: 
 * Author: Andrea Sangiorgio
 * Author URi: 
 * Description: 
 * 
 */

define('BP_REVIEWS_PLUGIN_DIR',  plugin_dir_path (__FILE__));

class BPUserReviewHelper
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
    
    private function __construct() 
	{        
        add_action( 'bp_loaded'		  			, array(&$this , 'init_component'), 0);
        add_action( 'init'			  			, array(&$this , 'register_review_posttype'));     
        add_action( 'bp_loaded'		  			, array(&$this , 'load_textdomain'), 2);
        add_action( 'wp_print_styles'  			, array(&$this , 'add_css'));
        add_action( 'wp_print_scripts' 			, array(&$this , 'add_js'));		
		add_action( 'bp_member_header_actions'	, array(&$this , 'add_review_button'));				//[C] 1 - aggiunge il bottone 'Add Review'
		//add_action( 'init'  					, array(&$this , 'button_modifications', 9);		//[C] 2 - esplorazione HOOKS 
    }
    
    
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
    
  
	function register_review_posttype()
	{
		//
		if(is_multisite()&&!is_main_site())
			return;
       	  
		//
		register_post_type( 'ureviews', array 
			(
				'labels' => array(
					'name' 			=> __( 'User Review','reviews' ),
					'singular_name' => __( 'User Review','reviews' )
				)
				, 'public' 			=> false

				, 'has_archive'   	=> 'reviews' 										//[C] 3 - era 'non presente' 
				
				, 'menu_position'	=> 80
				, 'show_ui'			=> true																							
				, 'rewrite' 		=> array('slug' => 'ureviews')						// [ST]
				, 'supports' 		=> array('title','editor','author','comments')   	//OK
			)
		);
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
		load_plugin_textdomain( 'reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		/*
		$locale = apply_filters( 'bp_reviews_textdomain_get_locale', get_locale() );
					
		if ( !empty( $locale ) ) 
		{
			$mofile_default = sprintf( '%slanguages/%s.mo', plugin_dir_path(__FILE__), $locale );              
			$mofile = apply_filters( 'bp_reviews_textdomain_mofile', $mofile_default );
			
			if ( file_exists( $mofile ) ) 
			{        
				
				load_textdomain( 'reviews',$mofile  );
			}
		}*/
	}

	/**
	 * ------------------------------------
	 * 				[C] 1
	 * ------------------------------------
	 * 		
	 * [I] - Implementa -> CSS per <div e <a
	 * [T] - Traduci 	-> title, cioè tooltip!
	 * [T] - Traduci 	-> testo bottone!
	 *
	 */
	function add_review_button()																	
	{
		if(!bp_is_my_profile()) {
			echo '
			<div style = " float:left;  position:relative;  top:-5px ">
				<a 	
					class = "add-reviews button" 
					title = "Scrivi una Review per l\'utente." 												
					href="'.bp_get_displayed_user_link().'reviews/create"
				>				
					'.__('Add Review','reviews').'
				</a>
			</div>';
		}
	
	}
	
	/**
	 * ------------------------------------
	 * 				[C] 2
	 * ------------------------------------
	 */
	function button_modifications() 															
	{	
		remove_action('bp_member_header_actions','bp_send_public_message_button');
	}

}

//
BPUserReviewHelper::get_instance();
?>
