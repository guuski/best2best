<?php
/*

-----------------------------------------
[C] changes
-----------------------------------------

-----------------------------------------
Contenuto FILE:
-----------------------------------------

	- definisce la CLASSE 'BP_Referral_Component' che estende la CLASSE 'BP_Component' di Buddypress
			
	- istanzia un OGGETTO della CLASSE 'BP_Referral_Component' e lo assegna alla var '$bp->referral'
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
		
	- 	[PHP file]	 tutti in 'includes/' 
	
			'bp-referral-actions.php'
			'bp-referral-screens.php'
			'bp-referral-filters.php'
			'bp-referral-classes.php'
			'bp-referral-activity.php'		
			'bp-referral-template.php'
			'bp-referral-functions.php'
			'bp-referral-notifications.php'
			'bp-referral-ajax.php'
		e 
			//'bp-referral-admin.php'	
			
	-  [PHP function]		
	
			'get_referral_slug()' in 'bp-referral-template.php'
			
	-  [PHP constant]
	
			'BP_REFERRAL_PLUGIN_DIR'	def. in 'loader.php'
			'BP_REFERRAL_SLUG'			def. qui e/o in 'loader.php'
			
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

	
-----------------------------------------
global $bp
-----------------------------------------


*/
	
/**
 *
 *
 */
class BP_Referral_Component extends BP_Component {

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
			'referral',						//ID
			__( 'Referral', 'referral' ),	
			BP_REFERRAL_PLUGIN_DIR			
		);
	
		$this->includes();
			
		$bp->active_components[$this->id] = '2';	//2

		add_action( 'init', array( &$this, 'register_post_types' ) );		
	}

	/**
	 * Include i files necessari al componente
	 *     
	 */
	function includes() {

		// File da includere
		$includes = array(
			'includes/bp-referral-actions.php',
			'includes/bp-referral-activity.php',								
			'includes/bp-referral-classes.php',
			'includes/bp-referral-functions.php',			
			'includes/bp-referral-notifications.php',			
			'includes/bp-referral-screens.php',	
			'includes/bp-referral-template.php',

		);

		//inclusione Automatica con la funzione 'includes' di BP_Component
		parent::includes( $includes );

		//inclusione Manuale
	
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
				
		$globals = array
		(
			'slug'                  => BP_REFERRAL_SLUG,
			
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_REFERRAL_SLUG,
			
			//DIRECTORY 			
			'has_directory'         => false, 
			
			//NOTIFICATIONS
			//'notification_callback' => 'bp_referral_format_notifications',
			//'search_string'         => __( 'Search Referrals...', 'buddypress' ),					
		);
		
		parent::setup_globals( $globals );		
	}
	

	/**
	 *	Configura i men� di navigazione
	 */
	function setup_nav() 
	{
	
		global $bp;
		
		$main_nav = array
		(
			'name' 		      		=> __( 'Referral', 'referral' ),																
			'slug' 		      		=> &$this->slug,
			//'slug' 		      		=> bp_get_referral_slug(), 
			'position' 	      		=> 100,
			'screen_function'     	=> 'bp_referral_screen_one', 			
			'default_subnav_slug' 	=> 'screen-one'			 			
		);

		//$referral_link = trailingslashit( bp_loggedin_user_domain() . bp_get_referral_slug() );
				
		//inserimento AUTOMATICO
		parent::setup_nav( $main_nav);
	}
	
	/**
	 * Registra un nuovo tipo di post per i referral
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function register_post_types() 
	{		
		$labels = array(
			'name'	   => __( 'User Referrals', 'referral' ),													
			'singular' => __( 'User Referral', 'referral' )			
		);
		
		$args = array
		(
				'label'	   => __( 'User Referrals', 'referral' )
			,	'labels'   => $labels
			
			, 'public' 				=> true																	

			, 'show_ui'				=> true																											
			, 'show_in_menu' 		=> true
			, 'show_in_nav_menus' 	=> true

			// QUERY related 1
			, 'publicly_queryable'  => true	

			// QUERY related 2
			, 'query_var' 			=> true	
							
			, 'menu_position'		=> 6	
							
			// REWRITE - SLUG
			, 'rewrite' 			=> array('slug' => 'referrals')
			
			// SUPPORT 			
			, 'supports' 			=> array('title','author','custom_fields')   										
		);      
    		     
		register_post_type( $this->id, $args );
		
		parent::register_post_types();
	}

}// chiude la CLASSE

//------------------------------------------------------------------------------------------------------------------------------------------------------


/**
 * Carica il componente BP_Referral_Component in $bp global
 *
 * Usando l' hook 'bp_loaded' ci si assicura che BP_Referral_Component venga caricato dopo i core components di BuddyPress
 * 
 */
function bp_ref_load_core_component() 
{
	global $bp;

	$bp->referral = new BP_Referral_Component;
}
add_action( 'bp_loaded', 'bp_ref_load_core_component' );

?>