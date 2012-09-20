<?php
/*

-----------------------------------------
Contenuto FILE:
-----------------------------------------
Questo file contiene le funzioni "template tag" che posso essere aggiunte files di template.

Per convenzione in Wordpress le funzioni "template tag" hanno 2 versioni:
	- la 1) ritorna il valore richiesto 				---> ES. 'bp_review_get_nome()'
	- la 2) fa l'echo del valore della prima funzione 	---> ES. 'bp_review_nome()'	
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------			
	
	- [PHP Class]	
	
		'Review'	in 'bp-review-classes.php'										//REVIEW  -----62,303,338

-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	wp_parse_args()
	have_posts()
	apply_filters ()
	get_post_meta()
	get_the_author_meta()
	get_the_ID()

-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_loggedin_user_id()
	bp_core_fetch_avatar()
	bp_core_get_userlink()
	
	bp_is_current_component()
	
	bp_loggedin_user_id()
	
-----------------------------------------
global $bp
-----------------------------------------


------------------------------------------
[T]
------------------------------------------

(204)	$title = sprintf( __( '%1$s ha scritto una review per %2$s!', 'reviews' ), $review_link, $recipient_link );


/**
 *
 *
 */
function bp_review_has_reviews( $args = '' ) 
{
	global $bp, $reviews_template;

	if ( empty( $reviews_template ) ) 
	{
		$defaults = array(
			'reviewer_id' => 0,																//reviewER_id
			'recipient_id'  => 0,
			'per_page'      => 10,
			'paged'			=> 1
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		$reviews_template = new Review();													//istanzia oggetto Review
		$reviews_template->get( $r );
	}

	return $reviews_template->have_posts();
}

/**
 *
 */
function bp_review_the_review() 
{
	global $reviews_template;
	
	return $reviews_template->query->the_post();
}

/**
 *
 */
function bp_review_review_name() 
{
	echo bp_review_get_review_name();
}
	
/**
 *
 */
function bp_review_get_review_name() 
{
	global $reviews_template;
		 
	echo apply_filters( 'bp_review_get_review_name', $reviews_template->review->name );
}


/**
 * 
 *
 */
function bp_review_pagination_count() 
{
	echo bp_review_get_pagination_count();
}

	/**
	 * 
	 *
	 */
	function bp_review_get_pagination_count() 
	{
		global $reviews_template;

		//$pagination_count = sprintf( __( 'Viewing page %1$s of %2$s', 'reviews' ), $reviews_template->query->query_vars['paged'], $reviews_template->query->max_num_pages );

		return apply_filters( 'bp_review_get_pagination_count', $pagination_count );
	}



/**
 * 
 *
 */
function bp_review_review_pagination() 
{
	echo bp_review_get_review_pagination();
}

	/**
	 * 
	 *
	 */
	function bp_review_get_review_pagination() 
	{
		global $reviews_template;
		return apply_filters( 'bp_review_get_review_pagination', $reviews_template->pag_links );
	}




/**
 * 
 *
 */
function bp_review_reviewer_avatar( $args = array() )
{
	echo bp_review_get_reviewer_avatar( $args );
}

	/**
	 * 
	 * @param mixed $args Accepts WP style arguments - either a string of URL params, or an array
	 * @return str The HTML for a user avatar
	 *
	 */
	function bp_review_get_reviewer_avatar( $args = array() ) 
	{
		$defaults = array
		(
			'review_id' => get_the_author_meta( 'ID' ),
			'object'  	=> 'user'
		);

		$r = wp_parse_args( $args, $defaults );

		return bp_core_fetch_avatar( $r );
	}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// -- OK
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 *
 *
 */
function bp_review_review_content() 
{
	echo bp_review_get_review_content();
}

	/**
	 * 
	 *
	 *
	 */
	function bp_review_get_review_content() 
	{	

		$obj_post = get_post(get_the_ID());	
		$content = $obj_post->post_content;
		
		return apply_filters( 'bp_review_get_review_content', $content, $reviewer_link, $recipient_link );
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ---OK
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 *
 *
 */
function bp_review_review_title() 
{
	echo bp_review_get_review_title();
}

/**
 * 
 *
 *
 */
function bp_review_get_review_title() 
{	
/*
	$reviewer_link   =	bp_core_get_userlink( get_the_author_meta( 'ID' ) );
	$recipient_id    = 	get_post_meta( get_the_ID(), 'bp_review_recipient_id', true );
	$recipient_link  = 	bp_core_get_userlink( $recipient_id );
*/
	//$title 			 = 	sprintf( __( '%1$s ha scritto una review per %2$s!', 'reviews' ), $reviewer_link, $recipient_link );

	///////////////////////////////////			
	$obj_post = get_post(get_the_ID());	
	$title = $obj_post->post_title;
	///////////////////////////////////			
	
	return apply_filters( 'bp_review_get_review_title', $title, $reviewer_link, $recipient_link );
}




//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//		usarla nella 'setup NAV' -----!!!
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 *
 *
 */
function bp_review_total_review_count() 
{
	echo bp_review_get_total_review_count();
}
	
	/**
	 *
	 * @return int
	 */
	function bp_review_get_total_review_count() 
	{
		$reviews = new Review();																	//istanzia oggetto Review
		$reviews->get();

		return apply_filters( 'bp_review_get_total_review_count', $reviews->query->found_posts, $reviews );
	}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 				- ma perche non funziona??!?!?!!?
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 
 *
 */
function bp_review_total_review_count_for_user( $user_id = false ) 
{
	echo bp_review_get_total_review_count_for_user( $user_id = false );
}

	/**
	 *
	 * @return int
	 */
	function bp_review_get_total_review_count_for_user( $user_id = false ) 
	{
		
		if ( !$user_id ) 
		{
			$user_id = bp_loggedin_user_id();
		}

		if ( !$user_id ) 
		{
			return 0;
		}

		$reviews = new Review();																					//istanzia oggetto Review
		
		$reviews->get( array( 'recipient_id' => $user_id ) );														//PARAMETRO!!!

		return apply_filters( 'bp_review_get_total_review_count', $reviews->query->found_posts, $reviews );
	}



//----------------------------varie.....





/**
 *
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 *
 * @return bool True if it's the review component, false otherwise
 */
function bp_is_review_component() 
{
	$is_review_component = bp_is_current_component( 'review' );

	return apply_filters( 'bp_is_review_component', $is_review_component );
}





/**
 *
 *
 */
function bp_review_slug() 
{
	echo bp_get_review_slug();
}

	/**
	 *
	 * @uses apply_filters() Filter 'bp_get_review_slug' to change the output
	 * @return str $review_slug The slug from $bp->review->slug, if it exists
	 */
	function bp_get_review_slug() 
	{
		global $bp;
		
		$review_slug = isset( $bp->review->slug ) ? $bp->review->slug : '';

		return apply_filters( 'bp_get_review_slug', $review_slug );
	}

/**
 * 
 *
 */
function bp_review_root_slug() 
{
	echo bp_get_review_root_slug();
}

	/**
	 * @uses apply_filters() Filter 'bp_get_review_root_slug' to change the output
	 * @return str $review_root_slug The slug from $bp->review->root_slug, if it exists
	 */
	function bp_get_review_root_slug() 
	{
		global $bp;

		$review_root_slug = isset( $bp->review->root_slug ) ? $bp->review->root_slug : '';

		return apply_filters( 'bp_get_review_root_slug', $review_root_slug );
	}
	
?>