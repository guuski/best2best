<?php

/********************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */

 
 
					 
					function bp_referral_screen_one() 
					{
						global $bp;
						
						do_action( 'bp_referral_screen_one' );	
						
						bp_core_load_template( apply_filters( 'bp_referral_template_screen_one', 'example/screen-one' ) );
					}




/**
 * bp_example_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_example_screen_one() 
{
	global $bp;
	
	do_action( 'bp_example_screen_one' );

	bp_core_load_template( apply_filters( 'bp_example_template_screen_one', 'example/screen-one' ) );
	//bp_core_load_template( apply_filters( 'bp_example_template_screen_one', 'screen-one' ) );

}


/**
 * bp_example_screen_two)
 * 
 */
function bp_example_screen_two() 
{

	global $bp;

	do_action( 'bp_example_screen_two' );
		
	bp_core_load_template( apply_filters( 'bp_example_template_screen_two', 'example/screen-two' ) );

}

/**
 * bp_example_screen_three
 * 
 */
function bp_example_screen_three() 
{

	global $bp;
	
	do_action( 'bp_example_screen_three' );

	bp_core_load_template( apply_filters( 'bp_example_template_screen_three', 'example/screen-three' ) );

}

/**
 * bp_example_screen_four
 * 
 */
function bp_example_screen_four() 
{

	global $bp;
	
	do_action( 'bp_example_screen_four' );

	bp_core_load_template( apply_filters( 'bp_example_template_screen_four', 'example/screen-four' ) );

}
?>