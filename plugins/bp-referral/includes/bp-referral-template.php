<?php
/*

-----------------------------------------
Contenuto FILE:
-----------------------------------------
Questo file contiene le funzioni "template tag" che posso essere aggiunte files di template.

Per convenzione in Wordpress le funzioni "template tag" hanno 2 versioni:
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------			
	

-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

-----------------------------------------
global $bp
-----------------------------------------


------------------------------------------
[T]
------------------------------------------


/**
 *
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 *
 * @return bool True if it's the referral component, false otherwise
 */
function bp_is_referral_component() 
{
	$is_referral_component = bp_is_current_component( 'referral' );

	return apply_filters( 'bp_is_referral_component', $is_referral_component );
}



/**
 *
 *
 */
function bp_referral_slug() 
{
	echo bp_get_referral_slug();
}

	/**
	 *
	 * @uses apply_filters() Filter 'bp_get_referral_slug' to change the output
	 * @return str $referral_slug The slug from $bp->referral->slug, if it exists
	 */
	function bp_get_referral_slug() 
	{
		global $bp;
		
		$referral_slug = isset( $bp->referral->slug ) ? $bp->referral->slug : '';

		return apply_filters( 'bp_get_referral_slug', $referral_slug );
	}

/**
 * 
 *
 */
function bp_referral_root_slug() 
{
	echo bp_get_referral_root_slug();
}

	/**
	 * @uses apply_filters() Filter 'bp_get_referral_root_slug' to change the output
	 * @return str $referral_root_slug The slug from $bp->referral->root_slug, if it exists
	 */
	function bp_get_referral_root_slug() 
	{
		global $bp;

		$referral_root_slug = isset( $bp->referral->root_slug ) ? $bp->referral->root_slug : '';

		return apply_filters( 'bp_get_referral_root_slug', $referral_root_slug );
	}
	
?>