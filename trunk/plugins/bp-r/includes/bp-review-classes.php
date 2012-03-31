<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------
Questo file contiene tutte le classi e le funzioni che accedono al database.

																												[T]
	This function uses WP_Query and wp_insert_post() to fetch and store data, using WordPress custom
	post types. 

	The suggested implementation here (where the WP_Query object is set as the query property on the
	'Review' object in get()) is one suggested implementation.
	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------

	(funzioni usate da 'review-loop.php')
	
		the_post()
		have_post()
	
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			
	
	wp_parse_args()	
	apply_filters()
	do_action()
	wp_insert_post()
	wp_update_post()
	update_post_meta()
	paginate_links()

WP_Query
add_query_arg
	
	wp_trash_post()
	
	
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------
	
	bp_core_get_user_displayname()
	
	
-----------------------------------------
global $bp, $wpdb, ($creds)
-----------------------------------------

																													[T]
																													
'post_title'	=> sprintf( __( '%1$s review %2$s', 'bp-review' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) )
'post_title'	=> sprintf( __( '%1$s review %2$s', 'bp-review' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) )

/**
 *
 */
class Review
{
	var $id;
	var $reviewer_id;														//reviewER
	var $recipient_id;
	var $date;
	var $query;

	/**
	 * bp_review_tablename()
	 *
	 * Crea a nuovo oggetto vuoto se non viene fornito un ID o preleva la riga corrispondente nel DB se l'ID viene fornito
     *
	 */
	function __construct( $args = array() ) 
	{	
		$defaults = array(
			'id'			=> 0,
			'reviewer_id' 	=> 0,
			'recipient_id'  => 0,
			'date' 			=> date( 'Y-m-d H:i:s' )
		);
		
		$r = wp_parse_args( $args, $defaults );
		extract( $r );

		if ( $id ) 
		{
			$this->id = $id;
			$this->populate( $this->id );
		}
		else 
		{
			foreach( $r as $key => $value ) 
			{
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * populate()
	 *	 
	 */
	function populate() 
	{
		global $wpdb, $bp; //$creds;

		if ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$bp->review->table_name} WHERE id = %d", $this->id ) ) ) 
		{
			$this->reviewer_id	 = $row->reviewer_id;
			$this->recipient_id  = $row->recipient_id;
			$this->date 	     = $row->date;
		}
	}

	/**
	 * save()
	 *
	 */
	function save($review_content) 
	{
		global $wpdb, $bp;
	
		$this->reviewer_id		= apply_filters( 'bp_review_data_reviewer_id_before_save', 		$this->reviewer_id, 	$this->id );
		$this->recipient_id		= apply_filters( 'bp_review_data_recipient_id_before_save', 	$this->recipient_id, 	$this->id );
		$this->date	     		= apply_filters( 'bp_review_data_date_before_save', 			$this->date,		 	$this->id );
		
		do_action( 'bp_review_data_before_save', $this );

		if ( $this->id ) 
		{			
			$wp_update_post_args = array
			(
				'ID'			=> $this->id,
				'post_author'	=> $this->reviewer_id,
				'post_title'	=> sprintf( __( '%1$s review %2$s', 'bp-review' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) )
				,
				/////////////////////////////////////
				'post_content'  => $review_content
				/////////////////////////////////////
			);
			
			$result = wp_update_post( $wp_update_post_args );

			if ( $result ) {
				///////////////////////////////////
				update_post_meta( $result, 'bp_review_content_review', $review_content );		//[C]
				///////////////////////////////////
				update_post_meta( $result, 'bp_review_recipient_id', $this->recipient_id );
			}
		} 
		else 
		{			
			$wp_insert_post_args = array
			(
				'post_status'	=> 'publish',
				'post_type'		=> 'review',											//post_type
				'post_author'	=> $this->reviewer_id,
				'post_title'	=> sprintf( __( '%1$s review %2$s', 'bp-review' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) )
				,
				///////////////////////////////////				
				'post_content'  => $review_content
				///////////////////////////////////
			);
		
			$result = wp_insert_post( $wp_insert_post_args );
			
			if ( $result ) 
			{				
				///////////////////////////////////
				update_post_meta( $result, 'bp_review_content_review', $review_content );		//[C]
				///////////////////////////////////
				update_post_meta( $result, 'bp_review_recipient_id', $this->recipient_id );
			}
		}
		
		do_action( 'bp_review_data_after_save', $this );

		return $result;
	}

	/**
	 * lancia la WP_Query
	 *
	 */
	function get( $args = array() ) 
	{		
		if ( empty( $this->query ) ) 
		{
			$defaults = array(
				'reviewer_id'	=> 0,												//reviewER
				'recipient_id'	=> 0,
				'per_page'		=> 10,
				'paged'			=> 1
			);

			$r = wp_parse_args( $args, $defaults );
			extract( $r );

			$query_args = array(
				'post_status'	 	=> 'publish',
				'post_type'		 	=> 'review',									//post_type
				'posts_per_page'	=> $per_page,
				'paged'		 		=> $paged,
				'meta_query'	 	=> array()
			);

			if ( $reviewer_id )
			{
				$query_args['author'] = (array)$reviewer_id;
			}
			
			if ( $recipient_id ) 
			{
				$query_args['meta_query'][] = array(
					'key'	  => 'bp_review_recipient_id',
					'value'	  => (array)$recipient_id,
					'compare' => 'IN' // Allows $recipient_id to be an array
				);
			}

			$this->query = new WP_Query( $query_args );													//WP_QUERY
			
			$this->pag_links = paginate_links( array(
				'base' => add_query_arg( 'items_page', '%#%' ),											//reviews_page
				'format' => '',
				'total' => ceil( (int) $this->query->found_posts / (int) $this->query->query_vars['posts_per_page'] ),
				'current' => (int) $paged,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'mid_size' => 1
			) );
		}
	}

		/**
		 * vedi 'bp_review_has_reviews()' loop
		 *
		 */
		function have_posts()
		{
			return $this->query->have_posts();
		}

		/**
		 * vedi 'bp_review_has_reviews()' loop
		 *	 
		 */
		function the_post() 
		{
			return $this->query->the_post();
		}

	/**
	 * delete()
	 *	 
	 */
	function delete() 
	{
		return wp_trash_post( $this->id );
	}

}//chiude la CLASSE

?>