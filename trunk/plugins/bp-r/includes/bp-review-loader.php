<?php
/*

-----------------------------------------
[C] changes
-----------------------------------------

	[C] 1 
		
		'bp-review-admin.php' 

-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- definisce la CLASSE 'BP_Review_Component' che estende la CLASSE 'BP_Component' di Buddypress
			
	- istanzia un OGGETTO della CLASSE 'BP_Review_Component' e lo assegna alla var '$bp->review'
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	- 	[PHP file]	 tutti in 'includes/' 
	
			'bp-review-actions.php'
			'bp-review-screens.php'
			'bp-review-filters.php'
			'bp-review-classes.php'
			'bp-review-activity.php'		
			'bp-review-template.php'
			'bp-review-functions.php'
			'bp-review-notifications.php'
			'bp-review-ajax.php'
		e 
			'bp-review-admin.php'	
			
	-  [PHP function]		
	
			'get_review_slug()' in 'bp-review-template.php'
			
	-  [PHP constant]
	
			'BP_REVIEW_PLUGIN_DIR'	def. in 'loader.php'
			'BP_REVIEW_SLUG'		def. qui e/o in 'loader.php'
			
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	add_action()
	is_admin() 
	is_network_admin() 
	register_post_type()
	
	init	--->	register_post_type()
	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_loggedin_user_domain()
	bp_core_new_subnav_item()
	bp_get_settings_slug()
	bp_is_my_profile()
	//bp_is_user()
	
	bp_loaded	--->	bp_review_load_core_component
	
-----------------------------------------
global $bp
-----------------------------------------

	$bp->review
	$bp->pages
	$bp->active_components[]

*/
	
/**
 *
 *
 */
class BP_Review_Component extends BP_Component {

	/**
	 *	Costruttore 	
	 *
	 * L'unico metodo da richiamare obbligatoriamente è start() ---> parent::start()
	 *
	 *   (1) $id   - identificatore univoco
	 *   (2) $name 	              
	 *   (3) $path - Il percorso della directory del plugin è usato da BP_Component::includes() per includere i file del plugin.
	 *
	 */
	function __construct() 
	{
		global $bp;

		parent::start(
			'review',						//ID
			__( 'Review', 'bp-review' ),														//[T] traduci	
			BP_REVIEW_PLUGIN_DIR			//untrailingslashit(BP_REVIEW_PLUGIN_DIR) 
		);
	
		$this->includes();
		
		// così che la funzione 'bp_is_active( 'review')' restituisce TRUE;
		$bp->active_components[$this->id] = '1';

		//
		add_action( 'init', array( &$this, 'register_post_types' ) );
	}

	/**
	 * Include i files necessari al componente
	 *     
	 */
	function includes() {

		// File da includere
		$includes = array(
			'includes/bp-review-actions.php',
			'includes/bp-review-screens.php',
			'includes/bp-review-filters.php',
			'includes/bp-review-classes.php',
			'includes/bp-review-activity.php',
			'includes/bp-review-template.php',
			'includes/bp-review-functions.php',
			'includes/bp-review-notifications.php',
			'includes/bp-review-ajax.php'
		);

		//inclusione Automatica con la funzione 'includes' di BP_Component
		parent::includes( $includes );

		//inclusione Manuale
		if ( is_admin() || is_network_admin() ) {
			//include( BP_REVIEW_PLUGIN_DIR . '/includes/bp-review-admin.php' );			//[C] 1
		}
	}

	/**
	 * 	 
	 */
	function setup_globals() 
	{
		global $bp;
		
		if ( !defined( 'BP_REVIEW_SLUG' ) ) 
		{
			define( 'BP_REVIEW_SLUG', $this->id );
			//define('BP_REVIEW_SLUG','review'); 
		}
		
		// [?] la uso?
		$global_tables = array(
			'table_name'      => $bp->table_prefix . 'bp_review'
		);
		
		$globals = array
		(
			'slug'                  => BP_REVIEW_SLUG,
			
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_REVIEW_SLUG,
			
			//DIRECTORY 
			'has_directory'         => true, 
			//'has_directory'         => false, 
			
			//NOTIFICATIONS
			'notification_callback' => 'bp_review_format_notifications',
			'search_string'         => __( 'Search Reviews...', 'buddypress' ),
			'global_tables'         => $global_tables
		);

		parent::setup_globals( $globals );		
	}

	/**
	 *	Configura i menù di navigazione
	 */
	function setup_nav() 
	{
		// 
		$main_nav = array
		(
			'name' 		      => __( 'Review', 'bp-review' ),
			
			//manca GET TOTAL COUNT delle reviews!		
				//'name'          => sprintf( __( 'Reviews <span>%d</span>', 'reviews' ),$this->mapper->get_total_count() ),
			
			'slug' 		      => bp_get_review_slug(), //&$this->slug
			
			'position' 	      => 80,

			//'item_css_id'         => $this->id														//[?]
									
			'screen_function'     => 'bp_review_screen_one',//'screen_function'     =>array(&$this,'screen_home'),			//screen one			
			
			'default_subnav_slug' => 'screen-one'		//'default_subnav_slug' => 'my-reviews',						//screen one					
		);

		$review_link = trailingslashit( bp_loggedin_user_domain() . bp_get_review_slug() );
		//$review_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );		//without a trailing slash
		
		/*
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
		*/
		
		$sub_nav[] = array
		(
			'name'            =>  __( 'Screen One', 'bp-review' ),
			//'name'            => $nav_text,
			
			'slug'            => 'screen-one',															//screen-one
			//'slug'            => 'my-reviews',
			
			'parent_url'      => $review_link,				//
			
			'parent_slug'     => bp_get_review_slug(), //'parent_slug'     => $this->slug,
			
			'screen_function' => 'bp_review_screen_one',//'screen_function' => array(&$this,'screen_home'),		//screen-one
			
			'position'        => 10
			
			//'item_css_id'     => 'review-my-review'
		);
		
		// aggiunge "Create review link
		/*
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
		*/
		/*
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
		*/
		
		//inserimento AUTOMATICO
		parent::setup_nav( $main_nav, $sub_nav );

		//inserimento MANUALE --> vedi 'bp_review_screen_settings_menu()'
		bp_core_new_subnav_item( 
			array
			(
				'name' 		  => __( 'Review', 'bp-review' ),
				'slug' 		  => 'review-admin',
				'parent_slug'     => bp_get_settings_slug(),
				'parent_url' 	  => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ),
				'screen_function' => 'bp_review_screen_settings_menu',
				'position' 	  => 40,
				'user_has_access' => bp_is_my_profile() // accesso consentito solo all'utente sul proprio profilo
			) 
		);	
	}
	
	/**
	 * Registra un nuovo tipo di post per le review
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function register_post_types() 
	{		
		$labels = array(
			'name'	   => __( 'User Reviews', 'bp-review' ),												//[T]
			//'singular_name' => __( 'User Review','reviews' )												//[?]
			'singular' => __( 'User Review', 'bp-review' )			
		);
		
		$args = array(
			'label'	   => __( 'User Reviews', 'bp-review' )
			,
			'labels'   => $labels
			

			// ARCHIVE page
			// associa TEMPLATE....ma anche no!
			// associa la pagine REVIEWS
			//
			
			// , 'has_archive'   => 'archivio'				
			// , 'has_archive'   => 'reviews_archive'
			// , 'has_archive'   => true
			// , 'has_archive'   => 'review'
			
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
				//, 'menu_icon'			=> plugins_url('icons/favicon.ico',__FILE__)				 // [ALT] get_stylesheet_directory_uri() . '/icons/favicon.ico'
							
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
		);      
    		     
		register_post_type( $this->id, $args );
		
		parent::register_post_types();
	}

}// chiude la CLASSE


/**
 * Carica il componente BP_Review_Component in $bp global
 *
 * Usando l' hook 'bp_loaded' ci si assicura che BP_Review_Component venga caricato dopo i core components di BuddyPress
 * 
 */
function bp_review_load_core_component() 
{
	global $bp;

	$bp->review = new BP_Review_Component;
}
add_action( 'bp_loaded', 'bp_review_load_core_component' );

?>