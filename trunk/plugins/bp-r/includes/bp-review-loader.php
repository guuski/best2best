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
	 * L'unico metodo da richiamare obbligatoriamente � start() ---> parent::start()
	 *
	 *   (1) $id   - identificatore univoco
	 *   (2) $name 	              
	 *   (3) $path - Il percorso della directory del plugin � usato da BP_Component::includes() per includere i file del plugin.
	 *
	 */
	function __construct() 
	{
		global $bp;

		parent::start(
			'review',						//ID
			__( 'Review', 'reviews' ),														//[T] traduci	
			BP_REVIEW_PLUGIN_DIR			//untrailingslashit(BP_REVIEW_PLUGIN_DIR) 
		);
	
		$this->includes();
		
		// cos� che la funzione 'bp_is_active( 'review')' restituisce TRUE;
		$bp->active_components[$this->id] = '1';

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
			//'has_directory'         => true, 
			'has_directory'         => false, 
			
			//NOTIFICATIONS
			'notification_callback' => 'bp_review_format_notifications',
			'search_string'         => __( 'Search Reviews...', 'buddypress' ),
			'global_tables'         => $global_tables
		);
		
		parent::setup_globals( $globals );		
	}
	

	/**
	 *	Configura i men� di navigazione
	 */
	function setup_nav() 
	{
	
		global $bp;
		
		// 
		$main_nav = array
		(
			'name' 		      => __( 'Review', 'reviews' ),																//[I] manca GET TOTAL COUNT delle reviews!---crea la funzione!		
//			'name'          => sprintf( __( 'Reviews <span>%d</span>', 'reviews' ),---get_total_count()----),			
			'slug' 		      => bp_get_review_slug(), //&$this->slug			
			'position' 	      => 80,														//[?]
			'screen_function'     => 'bp_review_screen_one', // =>array(&$this,'screen_home'),							// IMPORTANTE: funzione 'bp_review_screen_one()' nel FILE
			//'default_subnav_slug' => 'screen-one'			 
			'default_subnav_slug' => 'my-reviews',																		// [C] S
			//'item_css_id'         => $this->id				
		);

		$review_link = trailingslashit( bp_loggedin_user_domain() . bp_get_review_slug() );
		//$review_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );		
		
		//---------------------------------------------------															//[C] S
		if(bp_is_my_profile())
		{
			$nav_text	 =	sprintf(__('Review ricevute','reviews'));						
			$review_link = 	trailingslashit( $bp->loggedin_user->domain . $this->slug );	
			//$review_link = trailingslashit( bp_loggedin_user_domain() . bp_get_review_slug() );
		}
		else
		{
			$nav_text	 =	sprintf (__('Le Review per %s', 'reviews'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$review_link = 	trailingslashit( $bp->displayed_user->domain . $this->slug);	
			//$review_link = trailingslashit( -----DISPLAYED USER! . bp_get_review_slug() );
		}		  
		//---------------------------------------------------
		
		$sub_nav[] = array
		(
			//'name'            =>  __( 'Lista Review', 'reviews' ),
			'name'            => $nav_text,																				//[C] S - vd IF sopra			
			
			//'slug'            => 'screen-one',																			
			'slug'            => 'my-reviews',																			//[C] S					
			
			'parent_url'      => $review_link,				//			
			'parent_slug'     => bp_get_review_slug(), 		// $this->slug,			
			'screen_function' => 'bp_review_screen_one',	//'screen_function' => array(&$this,'screen_home'),		 		// IMPORTANTE: funzione 'bp_review_screen_one()' nel FILE			
			'position'        => 10			
//			'item_css_id'     => 'review-my-review'
		);
		
		// aggiunge 		
		if(review_current_user_can_write()) 																			//nome ambiguo!
		{
			$sub_nav[] = array
			(
					'name'            => __('Scrivi una Review', 'reviews' )				

				,	'slug'            => 'screen-two'														// [S] create		---> devo cambiare il BOTTONE!!!
//				,	'slug'            => 'create'

				,	'parent_url'      => $review_link
				,	'parent_slug'     => $this->slug															
				,	'screen_function' => 'bp_review_screen_two'								 	// IMPORTANTE: funzione 'bp_review_screen_two()' nel FILE....			
				
				// ACCESS RESTRICTION - only allow on other's profile
				,	'user_has_access' => (		is_user_logged_in()										
											&&	!bp_is_my_profile()
											&&	bp_is_user()
										)
				,	'position'        => 20
			);
		}
		
		//---------------------------------------------------															
		if(bp_is_my_profile())
		{
			$nav_text_2	 =	sprintf(__('Review scritte da me','reviews'));						
			$review_link_2 = 	trailingslashit( $bp->loggedin_user->domain . $this->slug );	
			//$review_link = trailingslashit( bp_loggedin_user_domain() . bp_get_review_slug() );
		}
		else
		{
			$nav_text_2	 =	sprintf (__('Le Review scritte da %s', 'reviews'),  bp_core_get_user_displayname ($bp->displayed_user->id));				
			$review_link_2 = 	trailingslashit( $bp->displayed_user->domain . $this->slug);	
			//$review_link = trailingslashit( -----DISPLAYED USER! . bp_get_review_slug() );
		}		  
		//---------------------------------------------------
		
		$sub_nav[] = array
		(
				'name'            => $nav_text_2				
			,	'slug'            => 'screen-three'															
			,	'parent_url'      => $review_link_2
			,	'parent_slug'     => $this->slug														
			,	'screen_function' => 'bp_review_screen_three'											// IMPORTANTE: funzione 'bp_review_screen_three()' nel FILE....						
			,	'position'        => 30
		);
		
		
		//inserimento AUTOMATICO
		parent::setup_nav( $main_nav, $sub_nav );

		//inserimento MANUALE --> vedi 'bp_review_screen_settings_menu()'
		bp_core_new_subnav_item( 
			array
			(
				'name' 		  => __( 'Review Settings', 'reviews' ),				
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
			'name'	   => __( 'User Reviews', 'reviews' ),												//[T]
			//'singular_name' => __( 'User Review','reviews' )												//[?]
			'singular' => __( 'User Review', 'reviews' )			
		);
		
		$args = array(
			'label'	   => __( 'User Reviews', 'reviews' )
			,
			'labels'   => $labels
			

			// ARCHIVE page
			// associa TEMPLATE....ma anche no!
			// associa la pagine REVIEWS
			//
			
			// , 'has_archive'   => 'archivio'				
			// , 'has_archive'   => 'reviews_archive'
			//, 'has_archive'   => true
			//, 'has_archive'   => 'review'
			
			, 'has_archive'   => 'lista-reviews'
			
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
			, 'custom-fields'		 => true				//anche se False � possibile aggiungere custom fields 				
			//, 'register_meta_box_cb' => true				//You can create a custom callback function that is called when the meta boxes for the post form are set up.
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


//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//												[C] 3  - META BOXES o CUSTOME FIELDS
//
// 2 hoos + 3 funzioni di cui una di callback
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_action( 'add_meta_boxes', 'add_review_meta_box');	
	//add_action( 'admin_init', 'add_review_meta_box');					//[ALT]

/**
 * 
 * 
 */
function add_review_meta_box() 															
{	
	add_meta_box
	(		
			'review-details'		 // CSS_ID
		, 	'Dettagli Review'		 // title
		
		// (*)
		, 	'show_review_meta_box' 	 // callback Function name
		
		, 	'review' 				 // page_paramater = post_type
		,	'normal'				 // context	
		,	'high'					 // piority
		//, 	1					 // callback Args - [?]
	);		
			
}
		
/**
 *
 *	vd SOPRA (*)-->	'show_review_meta_box' 	 // callback Function name
 */
function show_review_meta_box( $post ) 							
{
	$bp_review_recipient_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true );		//NB:
	$bp_review_reviewer_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true );		//NB:
	$voto_prezzo 			= get_post_meta( $post->ID, 'voto_prezzo', true );		
	$voto_servizio 			= get_post_meta( $post->ID, 'voto_servizio', true );		
		
	?>
		<p> ID dell'utente di cui si vuole scrivere la Review: 
			<input 
				type  = "text" 
				name  = "bp_review_recipient_id" 
				value = "<?php echo esc_attr( $bp_review_recipient_id ); ?>" 
			/>
		</p>				
		
		<p> il tuo ID
			<input 
				type  = "text" 
				name  = "bp_review_reviewer_id" 
				value = "<?php echo esc_attr( $bp_review_reviewer_id ); ?>" 
			/>
		</p>			
					
		<p>	&nbsp; Prezzo &nbsp;				
			<select name = "voto_prezzo" id = "voto_prezzo" >
				<option selected> 0 </option>
				<option value = "1"	<?php selected( $voto_prezzo,1); ?>> 1 </option> 
				<option value = "2"	<?php selected( $voto_prezzo,2); ?>> 2 </option> 
				<option value = "3"	<?php selected( $voto_prezzo,3); ?>> 3 </option> 
				<option value = "4"	<?php selected( $voto_prezzo,4); ?>> 4 </option> 
				<option value = "5"	<?php selected( $voto_prezzo,5); ?>> 5 </option> 											
			</select>			
		</p>	
		<p> &nbsp; Servizio &nbsp;
			<select name = "voto_servizio" id = "voto_servizio" >
				<option selected> seleziona&nbsp;&nbsp;&nbsp;</option>
				<option value = "1"	<?php selected( $voto_servizio,1); ?>> 1 </option> 
				<option value = "2"	<?php selected( $voto_servizio,2); ?>> 2 </option> 		
				<option value = "3"	<?php selected( $voto_servizio,3); ?>> 3 </option> 
				<option value = "4"	<?php selected( $voto_servizio,4); ?>> 4 </option> 
				<option value = "5"	<?php selected( $voto_servizio,5); ?>> 5 </option> 					
			</select>
			
		</p>	
	<?php	
}


//HOOK
add_action( 'save_post', 'save_review_meta_box' );

/**
 *
 */
function save_review_meta_box( $post_id ) 
{
	if ( isset( $_POST['bp_review_recipient_id'] ) ) 
		update_post_meta( $post_id, 'bp_review_recipient_id', 	strip_tags( $_POST['bp_review_recipient_id'] ) );	
	
	if ( isset( $_POST['bp_review_reviewer_id'] ) ) 
		update_post_meta( $post_id, 'bp_review_reviewer_id', strip_tags( $_POST['bp_review_reviewer_id'] ) );
		
	if ( isset( $_POST['voto_prezzo'] ) ) 			
		update_post_meta( $post_id, 'voto_prezzo', strip_tags( $_POST['voto_prezzo'] ) );
					
	if ( isset( $_POST['voto_servizio'] ) ) 
		update_post_meta( $post_id, 'voto_servizio', strip_tags( $_POST['voto_servizio'] ) );		
}




//------------------------------------------------------------------------------------------------------------------------------------------------------------------
//			tanto per capire il meccanismo ma lasciamo perdere per orA!
//------------------------------------------------------------------------------------------------------------------------------------------------------------------



//add_action('bp_screens','save_review',4);


//
    function save_review()
	{
/*     
	 global $bp;
     
		if( 	
				bp_is_current_component($bp->review->slug)
			&&  bp_is_current_action('screen-two')													//SCREEN_TWO o CREATE
			&&	!empty($_POST['review-submit'])
			)										
		{
			
			// [WPNONCE]
			check_admin_referer( 'bp_review_new_review' );		
			
			$error   = false;
			$message = 'ciao benvenuto';


			//[...]
				
			bp_core_add_message($message,$error);      
			
			//redirect
			bp_core_redirect(wp_get_referer()); 
		}    
		*/
    }   


?>