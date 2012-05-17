<?php

//-----------------------------------------------------------------------------------------------------
function bp_referral_slug() {
	echo bp_get_referral_slug();
}
	function bp_get_referral_slug() {
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$referral_slug = isset( $bp->referral->slug ) ? $bp->referral->slug : '';

		return apply_filters( 'bp_get_referral_slug', $referral_slug );
	}

function bp_referral_root_slug() {
	echo bp_get_referral_root_slug();
}

	function bp_get_referral_root_slug() 
	{
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$referral_root_slug = isset( $bp->referral->root_slug ) ? $bp->referral->root_slug : '';

		return apply_filters( 'bp_get_referral_root_slug', $referral_root_slug );
	}

//-----------------------------------------------------------------------------------------------------	
	
	
	
	
function bp_example_slug() {
	echo bp_get_example_slug();
}
	function bp_get_example_slug() {
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$example_slug = isset( $bp->example->slug ) ? $bp->example->slug : '';

		return apply_filters( 'bp_get_example_slug', $example_slug );
	}

function bp_example_slug_root_slug() {
	echo bp_get_example_root_slug();
}

	function bp_get_example_root_slug() {
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$example_root_slug = isset( $bp->example->root_slug ) ? $bp->example->root_slug : '';

		return apply_filters( 'bp_get_example_root_slug', $example_root_slug );
	}

	
	
	
	

?>