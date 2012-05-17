<?php

					 
					function bp_referral_screen_one() 
					{
						global $bp;
						
						do_action( 'bp_referral_screen_one' );	
						
						bp_core_load_template( apply_filters( 'bp_referral_template_screen_one', 'example/screen-one' ) );
					}




/**
 * bp_example_screen_one()
 *
 */
function bp_example_screen_one() 
{
	global $bp;
	
	do_action( 'bp_example_screen_one' );

	bp_core_load_template( apply_filters( 'bp_example_template_screen_one', 'example/screen-one' ) );	
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

/**
 * bp_example_screen_five
 * 
 */
function bp_example_screen_five() 
{

	global $bp;
	
	do_action( 'bp_example_screen_five' );

	bp_core_load_template( apply_filters( 'bp_example_template_screen_five', 'example/screen-five' ) );
}

				/**
				 * bp_example_screen_six
				 * 
				 */
				function bp_example_screen_six() 
				{

					global $bp;
					
					do_action( 'bp_example_screen_six' );

					bp_core_load_template( apply_filters( 'bp_example_template_screen_six', 'example/screen-six' ) );
				}
?>