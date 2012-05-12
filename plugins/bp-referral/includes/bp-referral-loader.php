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

		if ( !defined( 'BP_REFERRAL_SLUG' ) )
			define( 'BP_REFERRAL_SLUG', $this->id );

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
			'slug' 		    	  => bp_get_referral_slug(),
			'position' 	    	  => 80,
			'screen_function'     => 'bp_referral_screen_one',
			'default_subnav_slug' => 'screen-one'
		);

		$referral_link = trailingslashit( bp_loggedin_user_domain() . bp_get_referral_slug() );

		$sub_nav[] = array
		(
			'name'            =>  __( 'Screen One', 'referrals' ),
			'slug'            => 'screen-one',
			'parent_url'      => $referral_link,
			'parent_slug'     => bp_get_referral_slug(),
			'screen_function' => 'bp_example_screen_one',
			'position'        => 10
		);
*/
		$main_nav = array
		(
			'name' 		  		  => __( 'Referral', 'referrals' ),
			'slug' 		    	  => bp_get_example_slug(),
			'position' 	    	  => 80,
			'screen_function'     => 'bp_example_screen_one',
			'default_subnav_slug' => 'screen-one'
		);

		$example_link = trailingslashit( bp_loggedin_user_domain() . bp_get_example_slug() );

		$sub_nav[] = array
		(
			'name'            =>  __( 'Referral', 'referrals' ),
			'slug'            => 'screen-one',
			'parent_url'      => $example_link,
			'parent_slug'     => bp_get_example_slug(),
			'screen_function' => 'bp_example_screen_one',
			'position'        => 10
		);

		parent::setup_nav( $main_nav, $sub_nav );

	}
	
	function register_post_types() 
	{
	
		$labels = array(
			'name'	   => __( 'High Fives', 'referrals' ),
			'singular' => __( 'High Five', 'referrals' )
		);

		$args = array(
			'label'	   => __( 'High Fives', 'referrals' ),
			'labels'   => $labels,
			'public'   => false,
			'show_ui'  => true,
			'supports' => array( 'title' )
		);

		register_post_type( $this->id, $args );

		parent::register_post_types();
	}

}
/*
function bp_referral_load_core_component() 
{
	global $bp;

	$bp->referral = new BP_Referral_Component;
}
add_action( 'bp_loaded', 'bp_referral_load_core_component' );

*/
function bp_example_load_core_component() 
{
	global $bp;

	$bp->example = new BP_Example_Component;
}
add_action( 'bp_loaded', 'bp_example_load_core_component' );

?>