<?php

	
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------	
// 													CLASSE: BP_Example_Component 																
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------	
class BP_Example_Component extends BP_Component 														//EXAMPLE --> REFERRAL
//class BP_Referral_Component extends BP_Component 											
{
	/**
	 *
	 */
	function __construct() 
	{
		global $bp;

		parent::start
		(

			'example',																					//EXAMPLE --> REFERRAL
			__( 'Referral', 'referrals' ),
			BP_EXAMPLE_PLUGIN_DIR
			
		/*			
			'referral',
			__( 'Referral', 'referrals' ),
			BP_REFERRAL_PLUGIN_DIR
		*/
			
		);

		$this->includes();

		//$bp->active_components[$this->id] = '1';

		add_action( 'init', array( &$this, 'register_post_types' ) );							//ACTION
	}

	/**
	 *
	 */
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

	/**
	 *
	 */
	function setup_globals() 
	{
		global $bp;

		if ( !defined( 'BP_REFERRAL_SLUG' ) ) 
		{
			define( 'BP_REFERRAL_SLUG', $this->id );										
		}
		
		/*	
		$global_tables = array(
			'table_name'      => $bp->table_prefix . 'bp_referral'
		);
		*/

		$globals = array(
			'slug'                  => BP_REFERRAL_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_REFERRAL_SLUG,
			
			'has_directory'         => false, // Set to false if not required
			'notification_callback' => 'bp_referral_format_notifications',															//NOTIFICATIONS callback!
			//'search_string'         => __( 'Search Examples...', 'buddypress' ),
			//'global_tables'         => $global_tables
		);

		parent::setup_globals( $globals );
	}

	/**
	 *
	 */
	function setup_nav() 
	{
	
		global $bp;

		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			'slug' 		    	  => bp_get_example_slug(),															//EXAMPLE --> REFERRAL
			'position' 	    	  => 80,
			
			//'screen_function'     => 'bp_example_screen_one',			// 1											//EXAMPLE --> REFERRAL
			'screen_function'     => 'bp_example_screen_five',			// 5											//EXAMPLE --> REFERRAL
			//'screen_function'     => 'bp_example_screen_two',			// 2											//EXAMPLE --> REFERRAL

			//'default_subnav_slug' => 'screen-one'			
			'default_subnav_slug' => 'screen-five'				// 5
			//'default_subnav_slug' => 'screen-two'				// 2
		);
		
		//togliere?!
		$referral_link = trailingslashit( bp_loggedin_user_domain() . bp_get_example_slug() );						//EXAMPLE --> REFERRAL
				
		// 
		// ------- screen 1 ---------- Referral confermati/lasciati/accettati dall'utente ad altri utenti-------------------------------------
		//		
				
		if(bp_is_my_profile())
		{
			$nav_text		= sprintf(__('Referral confermati da me','referrals'));						
			$referral_link 	= trailingslashit( $bp->loggedin_user->domain . $this->slug );	
		}
		else
		{
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$nav_text		= sprintf (__('Referral confermati da %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$referral_link 	= trailingslashit( $bp->displayed_user->domain . $this->slug);	
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}		  		
		
		$sub_nav[] = array
		(			
				'name'            => $nav_text
			,	'slug'            => 'screen-one'
			,	'parent_url'      => $referral_link
			,	'parent_slug'     => bp_get_example_slug() 														//EXAMPLE --> REFERRAL
			,	'screen_function' => 'bp_example_screen_one'													//EXAMPLE --> REFERRAL
			,	'position'        => 10			
			
			// ACCESS RESTRICTION - only allow on YOUR OWN profile
			,	'user_has_access' =>
									(		is_user_logged_in()										
										&&	bp_is_my_profile()
									//	&&	bp_is_user()
									)						
		);
		
		// 
		// ------- screen 2 ------------------------	Chiedi un REFERRAL - Lascia una richiesta di REFERRAL-----------------------------------
		//
		
		// aggiunge...
		if(referral_current_user_can_write()) 																			//nome ambiguo!
		{
			$sub_nav[] = array
			(
					'name'            => __('Chiedi un Referral', 'referrals' )				
				,	'slug'            => 'screen-two'					
				,	'parent_url'      => $referral_link
				,	'parent_slug'     => $this->slug																			
				, 	'screen_function' => 'bp_example_screen_two'													//EXAMPLE --> REFERRAL
				,	'position'        => 20
				
				// ACCESS RESTRICTION - only allow on OTHER'S profile
				,	'user_has_access' => 
										(		is_user_logged_in()										
											&&	!bp_is_my_profile()
											&&	bp_is_user()
										)				
			);
		}
		
		// 
		// ------- screen 3 ------- RICHIESTE Referral fatta dall utente agli altri utenti ----------------------------------------------------
		//		
				
		if(bp_is_my_profile())
		{
			$nav_text_2		 = sprintf(__('Referral chiesti da me','referrals'));						
			$referral_link_2 = trailingslashit( $bp->loggedin_user->domain . $this->slug );	
		}
		else
		{
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$nav_text_2	 	 = sprintf (__('I Referral richiesti da %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$referral_link_2 = trailingslashit( $bp->displayed_user->domain . $this->slug);	
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}		  
		
		$sub_nav[] = array
		(
				'name'            => $nav_text_2				
			,	'slug'            => 'screen-three'															
			,	'parent_url'      => $referral_link_2
			,	'parent_slug'     => $this->slug														
			, 	'screen_function' => 'bp_example_screen_three'													//EXAMPLE --> REFERRAL
			,	'position'        => 30
			
			// ACCESS RESTRICTION - only allow on YOUR OWN profile 
			,	'user_has_access' => 
									(		is_user_logged_in()										
										&&	bp_is_my_profile()
									//	&&	bp_is_user()
									)
		);
		
		// 
		// ------- screen 4 ------- --------------------------------------------------------------------------------------------------------
		//
	
		if(bp_is_my_profile())
		{
			$nav_text_3		 = sprintf(__('Referral da Moderare','referrals'));						
			$referral_link_3 = trailingslashit( $bp->loggedin_user->domain . $this->slug );	
		}
		else
		{
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$nav_text_3		 = sprintf (__('Richieste Referral da Moderare di %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$referral_link_3 = trailingslashit( $bp->displayed_user->domain . $this->slug);	
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}		  
		
		$sub_nav[] = array
		(
				'name'            => $nav_text_3				
			,	'slug'            => 'screen-four'															
			,	'parent_url'      => $referral_link_3
			,	'parent_slug'     => $this->slug														
			, 	'screen_function' => 'bp_example_screen_four'													//EXAMPLE --> REFERRAL
			,	'position'        => 40
							
			// ACCESS RESTRICTION 
			,	'user_has_access' => (		is_user_logged_in()										
										&&	bp_is_my_profile()
										//&&	bp_is_user()
									)
		);
		
		// 
		// ------- screen 5 ------- ---------------------------------------------------- ----------------------------------------------------
		//
		
		if(bp_is_my_profile())
		{
			$nav_text_4		 = sprintf(__('Le mie Referral','referrals'));						
			$referral_link_4 = trailingslashit( $bp->loggedin_user->domain . $this->slug );	
		}
		else
		{
			$nav_text_4	 	 = sprintf (__('I Referral di %s', 'referrals'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$referral_link_4 = trailingslashit( $bp->displayed_user->domain . $this->slug);	
		}		  
		
		$sub_nav[] = array
		(
				'name'            => $nav_text_4				
			,	'slug'            => 'screen-five'															
			,	'parent_url'      => $referral_link_4
			,	'parent_slug'     => $this->slug														
			, 	'screen_function' => 'bp_example_screen_five'													//EXAMPLE --> REFERRAL
			,	'position'        => 40
		);
	
		//---------------------------------------
		parent::setup_nav( $main_nav, $sub_nav );
		//---------------------------------------

	}
		
	/**
	 *
	 */
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
			, 'public' 				=> true																	

			, 'show_ui'				=> true																											
			, 'show_in_menu' 		=> true
			, 'show_in_nav_menus' 	=> true

			// QUERY related 1
			, 'publicly_queryable'  => true														// 

			// QUERY related 2
			, 'query_var' 			=> true														// 
							
			// ICONA
				//, 'menu_icon'			=> plugins_url('icons/favicon.ico',__FILE__)				 // [ALT] get_stylesheet_directory_uri() . '/icons/favicon.ico'
							
			// POSIZIONE																				
			, 'menu_position'		=> 6														// 
							
			// REWRITE - SLUG
			, 'rewrite' 			=> array('slug' => 'referrals')								
			
			// SUPPORT 			
			, 'supports'			=> array( 'title','editor','author')																					
			
		);

		register_post_type( $this->id, $args );

		parent::register_post_types();
	}
	
}// ------------------------------------------------------------- chiude la CLASSE ------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'bp_loaded', 'bp_referral_load_core_component' );

/**
 *
 */
function bp_referral_load_core_component() 
{
	global $bp;

	$bp->example = new BP_Example_Component;														//EXAMPLE --> REFERRAL
	//$bp->referral = new BP_Referral_Component;
}
?>