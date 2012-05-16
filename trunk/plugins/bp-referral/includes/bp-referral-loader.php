<?php

if ( file_exists( dirname( __FILE__ ) . '/languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'referrals', dirname( __FILE__ ) . '/bp-referral/languages/' . get_locale() . '.mo' );

	
	
	
//class BP_Referral_Component extends BP_Component 
class BP_Example_Component extends BP_Component 

{

	function __construct() 
	{
		global $bp;

		parent::start
		(

			'example',
			__( 'Referral', 'referrals' ),
			BP_EXAMPLE_PLUGIN_DIR

			
/*			

			'referral',
			__( 'Referral', 'referrals' ),
			BP_REFERRAL_PLUGIN_DIR
*/
			
		);

		$this->includes();

//		$bp->active_components[$this->id] = '1';

		add_action( 'init', array( &$this, 'register_post_types' ) );
	}

	function includes() 
	{
		// Files to include
		$includes = array
		(
			'includes/bp-referral-actions.php',
			'includes/bp-referral-screens.php',
			'includes/bp-referral-classes.php',
			'includes/bp-referral-activity.php',
			'includes/bp-referral-template.php',
			'includes/bp-referral-functions.php',
			'includes/bp-referral-notifications.php'
		);

		parent::includes( $includes );
	}

	function setup_globals() 
	{
		global $bp;

		if ( !defined( 'BP_REFERRAL_SLUG' ) ) {
			define( 'BP_REFERRAL_SLUG', $this->id );
			//define( 'BP_REFERRAL_SLUG', 'example' );
			//define( 'BP_REFERRAL_SLUG', 'referral' );
		}
		
		$global_tables = array(
			'table_name'      => $bp->table_prefix . 'bp_referral'
		);

		$globals = array(
			'slug'                  => BP_REFERRAL_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_REFERRAL_SLUG,
			
			'has_directory'         => false, // Set to false if not required
//			'notification_callback' => 'bp_example_format_notifications',
//			'search_string'         => __( 'Search Examples...', 'buddypress' ),
			'global_tables'         => $global_tables
		);

		parent::setup_globals( $globals );
	}

	function setup_nav() 
	{
/*
		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			'slug' 		    	  => bp_get_referral_slug(),					//get_ref
			'position' 	    	  => 80,
			'screen_function'     => 'bp_referral_screen_one',					//screen ONE	
			'default_subnav_slug' => 'screen-one'
		);

		$referral_link = trailingslashit( bp_loggedin_user_domain() . bp_get_referral_slug() );//get_ref

		$sub_nav[] = array
		(
			'name'            =>  __( 'Referral', 'referrals' ),
			'slug'            => 'screen-one',
			'parent_url'      => $referral_link,
			'parent_slug'     => bp_get_referral_slug(),				//get_ref
			'screen_function' => 'bp_referral_screen_one',				//screen ONE	
			'position'        => 10
		);
*/






//-------------------------------------------------------------------------------------
/*
		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			'slug' 		    	  => bp_get_example_slug(),
			'position' 	    	  => 80,
			'screen_function'     => 'bp_example_screen_one',
			'default_subnav_slug' => 'screen-one'
		);

		$example_link = trailingslashit( bp_loggedin_user_domain() . bp_get_example_slug() );
*/
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
/*
		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			'slug' 		    	  => bp_get_example_slug(),
			'position' 	    	  => 80,
			'screen_function'     => 'bp_example_screen_two',
			'default_subnav_slug' => 'screen-two'
		);

		$example_link = trailingslashit( bp_loggedin_user_domain() . bp_get_example_slug() );
*/
//-------------------------------------------------------------------------------------

		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			
			'slug' 		    	  => bp_get_example_slug(),
//			'slug' 		    	  => &$this->slug,
			
			'position' 	    	  => 80,
			'screen_function'     => 'bp_example_screen_one',

			'default_subnav_slug' => 'screen-one'
			,

			//			'default_subnav_slug' => 'my-referrals'

		);
		
		$referral_link = trailingslashit( bp_loggedin_user_domain() . bp_get_example_slug() );	
				
		if(bp_is_my_profile())
		{
			$nav_text	 =	sprintf(__('Referral ricevute','referrals'));						
			//$referral_link = 	trailingslashit( $bp->loggedin_user->domain . $this->slug );	
			$referral_link = 	trailingslashit( $bp->loggedin_user->domain . bp_get_example_slug() );
		}
		else
		{
			$nav_text	 =	sprintf (__('Referral per %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			//$referral_link = 	trailingslashit( $bp->displayed_user->domain . $this->slug);	
            $referral_link = 	trailingslashit( $bp->displayed_user->domain . bp_get_example_slug() );
		}		  		
		
		$sub_nav[] = array
		(			
			'name'            => $nav_text,																						

//			'slug'            => 'my-referrals',																			
			'slug'            => 'screen-one',																			
			
			'parent_url'      => $referral_link,				
			'parent_slug'     => bp_get_example_slug(), 														// EXAMPLE
			'screen_function' => 'bp_example_screen_one',													//EXAMPLE
			'position'        => 10			
		);
		
		// aggiunge...
		if(referral_current_user_can_write()) 																			//nome ambiguo!
		{
			$sub_nav[] = array
			(
					'name'            => __('Chiedi un Referral', 'referrals' )				
				,	'slug'            => 'screen-two'					
				,	'parent_url'      => $referral_link

				,	'parent_slug'     => $this->slug															
//				,	'parent_slug'     => bp_get_example_slug() 														// EXAMPLE				
				
				, 	'screen_function' => 'bp_example_screen_two'													//EXAMPLE
				
				// ACCESS RESTRICTION - only allow on other's profile
				,	'user_has_access' => (		is_user_logged_in()										
											&&	!bp_is_my_profile()
											&&	bp_is_user()
										)
				,	'position'        => 20
			);
		}
				
		if(bp_is_my_profile())
		{
			$nav_text_2	 =	sprintf(__('Referral richieste da me','referrals'));						
			$referral_link_2 = 	trailingslashit( $bp->loggedin_user->domain . $this->slug );	
		}
		else
		{
			$nav_text_2	 =	sprintf (__('I Referral richiesti da %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$referral_link_2 = 	trailingslashit( $bp->displayed_user->domain . $this->slug);	
		}		  
		
		$sub_nav[] = array
		(
				'name'            => $nav_text_2				
			,	'slug'            => 'screen-three'															
			,	'parent_url'      => $referral_link_2
			,	'parent_slug'     => $this->slug														
			, 	'screen_function' => 'bp_example_screen_three'													//EXAMPLE
			,	'position'        => 30
		);
	
	
		
//-------------------------------------------------------------------------------------
/*
		$sub_nav[] = array
		(
			'name'            =>  __( 'Referral', 'referrals' ),
			'slug'            => 'screen-one',
			'parent_url'      => $example_link,
			'parent_slug'     => bp_get_example_slug(),
			'screen_function' => 'bp_example_screen_one',
			'position'        => 10
		);
*/
//-------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------
/*
		$sub_nav[] = array
		(
			'name'            =>  __( 'Referral', 'referrals' ),
			'slug'            => 'screen-two',
			'parent_url'      => $example_link,
			'parent_slug'     => bp_get_example_slug(),
			'screen_function' => 'bp_example_screen_two',
			'position'        => 10
		);
*/		
//-------------------------------------------------------------------------------------


		//-----------------------------------------
		parent::setup_nav( $main_nav, $sub_nav );

	}
	
	
	
	
	
	
	function register_post_types() 
	{
	
		$labels = array(
			'name'	   => __( 'Referrals', 'referrals' ),
			'singular' => __( 'Referral', 'referrals' )
		);

		$args = array
		(
			  'label'	   => __( 'Referrals', 'referrals' )
			, 'labels'   => $labels
			
		
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
//			, 'exclude_from_search  => false													//

			// QUERY related 2
			, 'query_var' 			=> true														// 
							
			// ICONA
				//, 'menu_icon'			=> plugins_url('icons/favicon.ico',__FILE__)				 // [ALT] get_stylesheet_directory_uri() . '/icons/favicon.ico'
							
			// POSIZIONE																				
			, 'menu_position'		=> 6														// 
							
			// REWRITE - SLUG
			, 'rewrite' 			=> array('slug' => 'referrals')								// [ST] [?] Ur*?! cambiare?!
			
			// SUPPORT 			
			//, 'supports' 			=> array('title','editor','author','comments','custom_fields','excerpt')   		
			, 'supports' => array( 'title' )							//solo?
			
			// Export
//				, 'can_export'		  	=> true
			
			// CUSTOM FIELDS - META BOXES -	in prova
			, 'custom-fields'		 => true				//anche se False ? possibile aggiungere custom fields 				
			//, 'register_meta_box_cb' => true				//You can create a custom callback function that is called when the meta boxes for the post form are set up.

		);

		register_post_type( $this->id, $args );

		parent::register_post_types();
	}
}// chiude la CLASSE

function bp_referral_load_core_component() 
{
	global $bp;

	//$bp->referral = new BP_Referral_Component;
					
	$bp->example = new BP_Example_Component;														//EXAMPLE
}
add_action( 'bp_loaded', 'bp_referral_load_core_component' );

?>