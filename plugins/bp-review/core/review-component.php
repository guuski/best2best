<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- definisce la CLASSE 'BP_Review_Component' che estende la CLASSE 'BP_Component' di Buddypress

----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	[PHP file]	 
	
		template-tags.php
		classes.php
		hooks.php
				
	[PHP class]	
	
		BPReviewMapper
		BPReviewUserPermissions
					
	[PHP function]			
	
		review-loop.php 						//
		
	
		dal file 'classes.php'
		
			associate_review_page() - di BPReviewMapper 
		
		dal file 'template-tags.php'
			
			bp_reviews_load_template			
			bp_reviews_post_form()				
			
			review_current_user_can_write() 	
		
		dal file 'hooks.php'
			
			bp_reviews_delete_between_user_by_type	
			
	[PHP constant]
	
		BP_REVIEWS_SLUG

-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			
	.....
	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------
	
	bp_core_delete_notifications_by_type()
	
	bp_screens  --> save_review
	bp_screens  --> update_review
	bp_screens  --> delete_review
	
	bp_template_content -->	home_content
	bp_template_content --> write_content
	bp_template_content --> pending_content
	
	.....
	
-----------------------------------------
GLOBALS: $bp, $wpdb, $creds
-----------------------------------------


------------------

	// Add the Create review link
	if(review_current_user_can_write()) 	//nome ambiguo!
	{
		....
	}
	
	---> nel file 'template-tags.php'
	
		function review_current_user_can_write()						//can user write review
		{
			$can_write=false;
	   
			if(is_user_logged_in()&&!bp_is_my_profile()&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id()))
				$can_write=true;
		
			return apply_filters('bp_reviews_can_user_write',$can_write);
		}	
	
---------------------
		//
		if(bp_is_current_component($bp->reviews->root_slug))
		{			
			//make sure to call it when a review page is opened for a user  
			
			$this->mapper->associate_review_page();
					
			$bp->reviews->post		= $this->mapper->get_post(); //do we really need it
			$bp->reviews->post->id	= $bp->reviews->post->ID;    //do we really need it		   
		}  
		
----------------------

	else if(!BPReviewUserPermissions::can_write())		
*/


/**
 * BPUserReview
 * 
 * extends BP_Component and Is implemented as singleton instance to allow other devs changing hooks
 *
 */
class BPUserReview extends BP_Component
{    

	// singleton instance - (STATIC) 
    private static $instance;
	
	//
    private $mapper;
	
	/**
	 * get_instance() 
	 *
	 * (STATIC)
	 *
	 */
    public static function get_instance() 
	{
        if (!isset(self::$instance)) 
               self::$instance = new self();        

        return self::$instance;
    }
	
	/**
	 * set_mapper_object()
	 *
	 * assegna l'oggetto mapper
	 *
	 */
    function set_mapper_object(BPReviewMapper $mapper)
	{
        $instance = self::get_instance();
        $instance->mapper = $mapper;
    }

    /**
     * __construct() 
	 *
	 * Private constructor 
	 *
	 *
	 *
     */
    private function __construct() {
	
		global $bp;
        
		//PARENT  
        parent::start
		(
            'reviews',															// unique id
            __( 'Review', 'reviews' ),				 							//
			untrailingslashit(BP_REVIEWS_PLUGIN_DIR) 							// base path., remove slash
        );
     		
		//(dallo SKELETON)																									
		//$bp->active_components[$this->id] = '1';
		
		//(dallo BP-ALBUM)																						//ripetuto in 'setup globals'
		//$bp->reviews = new stdClass();
		//$bp->active_components[$bp->reviews->slug] = $bp->reviews->id;
        
		//--------------------------------
		//		ADD_ACTIONS - bp_screens
		//--------------------------------				
		add_action('bp_screens',array(&$this,'save_review'),4);
		add_action('bp_screens',array(&$this,'update_review'),4);
		add_action('bp_screens',array(&$this,'delete_review'),4);
    
		//add_action( 'bp_screens', array(&$this,'bp_review_directory_setup'),4);								//[C] - vd ultima funzione!		     
    }
    

    /**
	 * includes()
	 *
     * Include i files necessari
	 *
     */
    function includes() 
	{  
		$includes = array
		(
			'core/template-tags.php',
			'core/classes.php',
            'inc/ajax.php',
            'core/hooks.php'
		);
                
        //PARENT     
        parent::includes( $includes );
        
		//
		self::set_mapper_object(new BPUserReviewMapper()); 										// [I] spostiamolo!
	}
	
	/**
	 * setup_globals()
	 * 
	 * Set up your plugin's globals
	 *
	 *
	 * 1) Use the parent::setup_globals() method to set up the key global data for your plugin:
	 *
	 * 2) You can also use this function to put data directly into the $bp global.
	 *
	 *  'slug'			
	 *				This is the string used to create URLs when your component
	 *				adds navigation underneath profile URLs. For example,
	 *				in the URL 
	 * 
	 *					http://testbp.com/members/boone/example
	 *					
	 *				the 'example' portion of the URL is formed by the 'slug'.
	 *				Site admins can customize this value by defining
	 *				BP_EXAMPLE_SLUG in their wp-config.php or bp-custom.php
	 *				files.
	 *
	 *
	 *  'root_slug'		
	 *				This is the string used to create URLs when your component
	 *				adds navigation to the root of the site. In other words,
	 *				you only need to define root_slug if your component is a
	 *				"root component". 
	 *				
	 *				Eg, in:		http://testbp.com/example/test
	 *				
	 *				'example' is a root slug. This should always be defined
	 *				in terms of $bp->pages; see the example below. Site admins
	 *				can customize this value by changing the permalink of the
	 *				corresponding WP page in the Dashboard. 
	 *					
	 *				NOTE: 'root_slug' requires that 'has_directory' is true.	 
	 *				  
	 *				  
	 *	'has_directory'		
	 *	
	 *				Set this to true if your component requires a top-level
	 *				directory, such as http://testbp.com/example. When
	 *				'has_directory' is true, BP will require that site admins
	 *  			associate a WordPress page with your component. 
	 *
	 *				NOTE: When 'has_directory' is true, you must also define your
	 *				component's 'root_slug'; see previous item.
	 *				Defaults to false.
	 *				  
	 *				  
	 *  'search_string'		
	 *				  
	 *				If your component is a root component (has_directory),
	 *				you can provide a custom string that will be used as the
	 *				default text in the directory search box.					  
	 *
	 * 
	 *	 
	 *
	 * @global obj $bp BuddyPress's global object
     */
	function setup_globals() 
	{
        global $bp;	
	   		 
		if(!defined('BP_REVIEWS_SLUG')) 
		{
			define('BP_REVIEWS_SLUG','reviews');			
			//define( 'BP_REVIEWS_SLUG', $this->id );		
        } 
		
		//all other globals
        // Note that global_tables is included in this array.										--?!
		$globals = array
		(
			//SLUG
			'slug'                  		=> BP_REVIEWS_SLUG
				
			//Fatal Error! when 'root_slug' is enabled!
				//Fatal error: Cannot access empty property in .....buddypress\bp-core\bp-core-template.php on line 760							
			// 2 Notice when 'root_slug' is disabled!			
				// Notice: Trying to get property of non-object in...buddypress\bp-activity\bp-activity-template.php on line 2310
				// Notice: Trying to get property of non-object in...buddypress\bp-messages\bp-messages-template.php on line 564
								
			,	'root_slug'             	=> isset( $bp->pages->reviews->slug ) ? $bp->pages->reviews->slug : BP_REVIEWS_SLUG			
			
			//(SKELETON version) è uguale!
//			,   'root_slug'             	=> isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_REVIEWS_SLUG
			
			//DIRECTORY 
//			,	'has_directory'   			=> true 																		
	
			//NOTIFICATIONS
			, 	'notification_callback'		=> 'bp_reviews_format_notifications'	
		);
				
		// (1) - [PARENT]
		parent::setup_globals( $globals );//it will call do_action("bp_reviews_setup_global") after setting up the constants properly
                	  
//-------------------------------------------------------------------------------------------------------------------------------------------------------------			  
		//disabilito 
		if(bp_is_current_component($bp->reviews->root_slug) && false)
		{			
			//(dallo BP-ALBUM)				
				//$bp->reviews = new stdClass();															//[ST]
				//$bp->active_components[$bp->reviews->slug] = $bp->reviews->id;			
		
			//make sure to call it when a review page is opened for a user  
			$this->mapper->associate_review_page();
		
			$bp->reviews->post		= $this->mapper->get_post(); //do we really need it
			if($bp->reviews->post != null) 
				$bp->reviews->post->id=$bp->reviews->post->ID;//do we really need it
		}  
//-------------------------------------------------------------------------------------------------------------------------------------------------------------			  		

		// (2) - ES (SKELETON)	   
		// If your component requires any other data in the $bp global, put it there now.
		$bp->{$this->id}->misc_data = '123';              		
}
        
			
	/**
	 * setup nav()
	 *
	 */
	function setup_nav() 
	{
		global $bp;
					 
		// Add 'reviews' to the user's main navigation
		$main_nav = array
		(
				'name'                => sprintf( __( 'Reviews <span>%d</span>', 'reviews' ),$this->mapper->get_total_count() )
			,	'slug'                => $this->slug															// [S] pending
			,	'position'            => 86
			,	'screen_function'     => array(&$this,'screen_home')											// [T] screen_home
			,	'default_subnav_slug' => 'my-reviews'
			,	'item_css_id'         => $this->id
		);

		$review_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );//without a trailing slash
	   
		if(bp_is_my_profile())
		{
			$nav_text	 =	sprintf(__('Le mie Review <span>%d</span>','reviews'),$this->mapper->get_comment_count_by_status(1));
			$review_link = 	trailingslashit( $bp->loggedin_user->domain . $this->slug );//without a trailing slash
		}
		else
		{
			$nav_text	 =	sprintf (__('Review di %s <span>%d</span>', 'reviews'),  bp_core_get_user_displayname ($bp->displayed_user->id),$this->mapper->get_comment_count_by_status(1));	
			$review_link = 	trailingslashit( $bp->displayed_user->domain . $this->slug );//without a trailing slash
		}
		
		$sub_nav[] = array
		(
				'name'            => $nav_text
			,	'slug'            => 'my-reviews'															// [S] my-reviews
			,	'parent_url'      => $review_link
			,	'parent_slug'     => $this->slug															// [S]
			,	'screen_function' => array(&$this,'screen_home')											// [T] screen_home
			, 	'position'        => 10
			,	'item_css_id'     => 'review-my-review'
		);

		// Add the Create review link
		if(review_current_user_can_write()) 	//nome ambiguo!
		{
			$sub_nav[] = array
			(
					'name'            => __('Scrivi una Review', 'reviews' )
				,	'slug'            => 'create'														// [S] create
				,	'parent_url'      => $review_link
				,	'parent_slug'     => $this->slug													// [S]	
				,	'screen_function' => array(&$this,'screen_write')									// [T] screen_write
				
				// ACCESS RESTRICTION - only allow on other's profile
				,	'user_has_access' => (		is_user_logged_in()										
											&&	!bp_is_my_profile()
											&&	bp_is_user()
										)
				,	'position'        => 20
			);
		}
					
		$sub_nav[] = array
		(
				'name'            => sprintf(__('Da moderare <span>%d</span>', 'reviews' ),$this->mapper->get_comment_count_by_status(0))
			,	'slug'            => 'pending'															// [S] pending
			,	'parent_url'      => $review_link
			,	'parent_slug'     => $this->slug														
			,	'screen_function' => array(&$this,'screen_pending')										// [T] screen_pending
			,	'user_has_access' => bp_is_my_profile()													// ACCESS RESTRICTION 
			,	'position'        => 30
		);

		//PARENT
		parent::setup_nav( $main_nav, $sub_nav );//will call bp_ureview_setup_nav
	}

//-------------------------------------------------------------------------------------------------------------------------------------------------
//																SCREENS functions
//-------------------------------------------------------------------------------------------------------------------------------------------------		

   /**
    *
    */	
	function screen_home()
	{
		global $bp;
		
		bp_reviews_delete_between_user_by_type(bp_loggedin_user_id(), bp_displayed_user_id(),$bp->reviews->id);
		
		add_action('bp_template_content',array(&$this,'home_content'));
		
		bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));					//[T]		
	}
	
	/**
     *
     */	
	function screen_write()
	{
		add_action('bp_template_content',array(&$this,'write_content'));
		
		bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));					//[T]	
	}
   
   /**
    *
    */		
	function screen_pending()
	{
		global $bp;
		
		//delete all notifications for this user
		bp_core_delete_notifications_by_type(bp_loggedin_user_id(), $bp->reviews->id, 'new_review');
		
		add_action('bp_template_content',array(&$this,'pending_content'));
		
		bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));				//[T]
	}

//-------------------------------------------------------------------------------------------------------------------------------------------------
//																what to show on user review page
//-------------------------------------------------------------------------------------------------------------------------------------------------		        
		
	function home_content()
	{
		bp_reviews_load_template('reviews/review-loop.php');												//[T]
	}
				
	function pending_content()
	{
		bp_reviews_load_template('reviews/review-loop.php');												//[T]
	}
	
	function write_content()
	{
		bp_reviews_post_form();																				//[T]	vd (S1)
	}
		
	/*
		(S1)
		
		--------------------------------
		nel file 'template-tags.php'
		--------------------------------
	
		function bp_reviews_post_form()
		{
			bp_reviews_load_template('reviews/post-form.php') ;   
		}	
	*/	

		
//-------------------------------------------------------------------------------------------------------------------------------------------------
//															ACTION functions
//-------------------------------------------------------------------------------------------------------------------------------------------------											


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//		SAVE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function save_review()
	{
        global $bp;
     
		if( 	
				bp_is_current_component($bp->reviews->slug)
			&&  bp_is_current_action('create')
			&&	!empty($_POST['review-submit']))														//POST - op 1
		{
			// Check the nonce
            check_admin_referer( 'new_review', '_wpnonce_new_review' );
			
			$error   = false;
			$message = '';
		
			if ( !is_user_logged_in() ) 
			{
				$message = __('Non sei autorizzato a compiere questa operazione','reviews');
				$error   = true;
			}
			else if ( empty( $_POST['new-review'] ) ) 													//POST - op 2
			{
				$message = 	__( 'Inserisci del testo per favore', 'reviews' ) ;
				$error   =	true;
			}
			else if(!BPReviewUserPermissions::can_write())
			{
				$message = __('Scrittura Review non consentita.','reviews');
				$error   = true;
			}
			else
			{       
				//
				$content   = $_POST['new-review'];		
								
				//$review_id = $this->mapper->save_review($content);												//[L]
				$review_id = $this->mapper->associate_review_page(null, $content);
				//////////////////////////////////////////////////////////
				//PROVA_ID
				//$prova_id = $this->mapper->save_review_prova($content);										//[L]		//[C]
				//////////////////////////////////////////////////////////
								
				$message   = __('Review inviata. In attesa di moderazione.','reviews');
			
				if ( empty( $review_id ) ) 
				{
					$message =  __( 'Operazione non riuscita. Prova di nuovo più tardi.', 'reviews' ) ;
					$error   = true;
				}
				else {								
					do_action( 'bp_reviews_action_saved_review', $review_id );             
				}
			}      
			
			bp_core_add_message($message,$error);      
			
			//redirect
			bp_core_redirect(wp_get_referer()); 
		}    
    }   
	
//////////////////////////////////////////////////////////////////////////////////////////////////////	//////////////////////////////////////////////////////////////////////////////////////////////////////		
	
    function delete_review()
	{
         global $bp;
		 
         $error   = false;
         $message = '';
		 
         if(bp_is_current_component($bp->reviews->slug)&&  bp_is_current_action('delete')&&!empty($_REQUEST['tid']))
		 {
             check_admin_referer( 'bp_review_delete_link' );
         
			//check if the user can write and xyz           	
			$review_id=$_REQUEST['tid'];				
			$review=BPReviewMapper::get_comment($review_id);
      
			if(!BPReviewUserPermissions::can_delete($review,$this->mapper)){
				$error = true;
                $message=__('Non sei autorizzato a compiere quest operazione','reviews');
			}
			
			//if current user can delete it
			if(!$error&& $this->mapper->delete_review($review_id,true))
				$message=__('Cancellazione effettuata correttamente','reviews');
      
			bp_core_add_message($message,$error);
			do_action( 'bp_reviews_action_delete_review', $review_id );
			  
			//redirect
			bp_core_redirect(wp_get_referer());	
        }
    }

	/*
	 *
	 *
	 */
    function update_review()
	{
        global $bp;
		 
        if(
				bp_is_current_component($bp->reviews->slug)
			&&  bp_is_current_action('approve-unapprove')
			&&	!empty($_REQUEST['tid']))
		{
        
			// Check the nonce
			check_admin_referer( 'bp_review_approve_link' );
			
			$review_id=$_REQUEST['tid'];
			$action=$_REQUEST['action'];
			
			$error   = false;
			$message = '';
			
			if ( !is_user_logged_in() ) 
			{
			   $message = __('Non sei autorizzato a compiere quest operazione','reviews');
			   $error   = true;
			   
			} 
			else if ( empty( $review_id ) || !is_numeric($review_id ) ) 
			{
				$message = __('Operazione non valida.','reviews');
				$error   = true;
				
			} 
			else if(!($action=='hide'||$action=='approve'))
			{
				$message = __( 'Operazione non valida.', 'reviews' );
				$error   = true;
			}
				   
			//if current user can delete it
			$review='';//get current comment
			
			if(!$error)
			{
				if($action=='approve')
				{
					$status 		= 1;
					$status_message = __('Review approvata e visibile sul tuo profilo','reviews');
				}
				else
				{
				   $status		   = 0;
				   $status_message = __('Review in attesa di moderazione! Vai alla scheda Review sul tuo profilo...','reviews');
				}   
			
				$review = BPReviewMapper::get_comment($review_id);
				
				//if current_user_can
				if(!BPReviewUserPermissions::can_approve($review,$this->mapper)){
					$message=__('Non sei autorizzato a compiere quest\'operazione','reviews');
					$error=true;
				}
						  
				$review->comment_approved=$status;
			  
				if(!$error&&$this->mapper->update_review($review))
				{
					do_action( 'bp_reviews_action_update_review', $review_id,$action );
					$message=$status_message;
				}
				else 
				{
					$message= __( 'Operazione non riuscita!. Riprova di nuovo!', 'reviews' );
					$error=true;
				}
			}  
		
			bp_core_add_message($message,$error);
			
			//redirect
			bp_core_redirect(wp_get_referer());  
		}
	}   
     
	
//////////////////////////////////////////////////////////////////////////////////////////////////////					//[C] 
/**
 * 
 *
 * @package BuddyPress_Template_Pack
 *
 */
function bp_review_directory_setup() 																
{
	if ( 		bp_is_review_component() 
			&& !bp_current_action() 
			&& !bp_current_item() 
		) 
	{		
		//bp_update_is_directory( true, $this->id );							//this->id = reviews (vd CONSTRUCT)
		bp_update_is_directory( true, reviews);	
		
		/*
		        parent::start(
					'reviews',																		//unique id
					__( 'Review', 'reviews' ),
					untrailingslashit(BP_REVIEWS_PLUGIN_DIR)//base path., remove slash
				);
		*/
		
		do_action( 'bp_review_directory_setup' );

		bp_core_load_template( apply_filters( 'review_directory_template', 'review/index' ) );
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
		
		  

}//chiude la CLASSE BPUserReview
?>