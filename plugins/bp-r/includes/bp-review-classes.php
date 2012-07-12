<?php
/*




		REMINDER: 		devo aggiungere la variabile '$content',no?
		
		






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

	(contine funzioni usate dai file di TEMPLATE 
	
	1) 'review-loop.php'
	
			the_post()
			have_post()
		
		oppure:
		
			


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
	
	
																														//aggiungere la variabile '$content',no?

	/**
	 * bp_review_tablename()
	 *
	 * Crea a nuovo oggetto vuoto se non viene fornito un ID o preleva la riga corrispondente nel DB se l'ID viene fornito
     *
	 */
	function __construct( $args = array() ) 
	{	
		$defaults = array
		(
			'id'			=> 0,
			'reviewer_id' 	=> 0,
			'recipient_id'  => 0,
			'date' 			=> date( 'Y-m-d H:i:s' )																	//aggiungere la variabile '$content',no?
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

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
			
																													//aggiungere la variabile '$content',no?
		}
	}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/**
	 * save()
	 *
	 */
	function save($review_title, $review_content, $giudizio_review, $data_rapporto, $tipologia_rapporto, $voti) 														
	{
		//[C] Rating
		global $wpdb, $bp;
	
		$this->reviewer_id		= apply_filters( 'bp_review_data_reviewer_id_before_save', 		$this->reviewer_id, 	$this->id );
		$this->recipient_id		= apply_filters( 'bp_review_data_recipient_id_before_save', 	$this->recipient_id, 	$this->id );
		$this->date	     		= apply_filters( 'bp_review_data_date_before_save', 			$this->date,		 	$this->id );
		
// 		aggiungere la variabile '$content' e title
		$voto_prezzo 		= $voti['prezzo'];
		$voto_servizio		= $voti['servizio'];
		$voto_qualita		= $voti['qualita'];
		$voto_puntualita	= $voti['puntualita'];
		$voto_affidabilita	= $voti['affidabilita'];
		
//DO ACTION
do_action( 'bp_review_data_before_save', $this );

		if ( $this->id ) 			
		{					
// 			$review_title = "titolo vuoto";
// 			$review_title = sprintf( __( 'Review di %1$s per %2$s', 'reviews' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) );
			
			$wp_update_post_args = array
			(
					'ID'			=> $this->id
				,	'post_author'	=> $this->reviewer_id
				,	'post_title'	=> wp_strip_all_tags($review_title) 				
				//'post_title'	=> sprintf( __( 'Review di %1$s per %2$s', 'reviews' ), bp_core_get_user_displayname( $this->reviewer_id ), bp_core_get_user_displayname( $this->recipient_id ) )
				,	'post_content'  => $review_content				
			);
			
			$result = wp_update_post( $wp_update_post_args );

			if ( $result ) 
			{
				update_post_meta( $result, 'voto_prezzo',$voto_prezzo);										//[C] Rating
				update_post_meta( $result, 'voto_servizio',$voto_servizio);
				update_post_meta( $result, 'voto_qualita',$voto_qualita);
				update_post_meta( $result, 'voto_puntualita',$voto_puntualita);
				update_post_meta( $result, 'voto_affidabilita',$voto_affidabilita);
				update_post_meta( $result, 'bp_review_recipient_id', $this->recipient_id );				
				update_post_meta( $result, 'bp_review_reviewer_id', $this->reviewer_id );		
				
				update_post_meta( $result, 'giudizio_review', $giudizio_review );		
				update_post_meta( $result, 'data_rapporto', $data_rapporto );		
				update_post_meta( $result, 'tipologia_rapporto', $tipologia_rapporto );		
							
			}
		} 
		else 
		{			
			$wp_insert_post_args = array
			(
					'post_status'	=> 'publish'
				,	'post_type'		=> 'review'										//post_type
				,   'post_author'	=> $this->reviewer_id
				,	'post_title'	=> wp_strip_all_tags($review_title)
				,	'post_content'  => $review_content				
			);
		
			//
			$result = wp_insert_post( $wp_insert_post_args );
			
			if ( $result ) 			
			{
				update_post_meta( $result, 'voto_prezzo',$voto_prezzo);										
				update_post_meta( $result, 'voto_servizio',$voto_servizio);
				update_post_meta( $result, 'voto_qualita',$voto_qualita);
				update_post_meta( $result, 'voto_puntualita',$voto_puntualita);
				update_post_meta( $result, 'voto_affidabilita',$voto_affidabilita);	
				update_post_meta( $result, 'bp_review_recipient_id', $this->recipient_id );			
				update_post_meta( $result, 'bp_review_reviewer_id', $this->reviewer_id );					// nu poco inutile! a
				
				update_post_meta( $result, 'giudizio_review', $giudizio_review );		
				update_post_meta( $result, 'data_rapporto', $data_rapporto );		
				update_post_meta( $result, 'tipologia_rapporto', $tipologia_rapporto );		
				
			}
			
		////////////////////////////////////////

		
		
		////////////////////////////////////////	
						
		}//chiude l' IF
		
		
	
	////////////////////////////////////////
	
	//-------------------calcola Media RATING
	$media_voti_rating = ($voto_prezzo + $voto_servizio + $voto_qualita + $voto_puntualita + $voto_affidabilita) / 5;	
	
	
	//recipient_id
	//$this->recipient_id 	
	
	//$user_id = $this->recipient_id;
	$user_id = get_post_meta( $result, 'bp_review_recipient_id', true );		
	
					
	$num_review_ricevute = get_user_meta( $user_id , 'num_review_ricevute', true );
	$media_voto_review   = get_user_meta( $user_id , 'media_voto_review', true );
					
	//-------------------aggiorna MEDIA
	
	//� la prima volta?
	if(	$num_review_ricevute == 0) 
	{	
		$media_voto_review = $media_voti_rating;
	}
	else 
	{
	
		$media_voto_review = $media_voti_rating;
	/*
		for( $i=0; $i< $num_review_ricevute; $i++) {
			$media_voto_review = 
		}
	*/
	}

	//
	$num_review_ricevute = $num_review_ricevute + 1;	
	
	update_usermeta( $user_id, 'num_review_ricevute', $num_review_ricevute );		
	update_usermeta( $user_id, 'media_voto_review'	, $media_voto_review   );		
	////////////////////////////////////////		
		
		
//DO ACTION
do_action( 'bp_review_data_after_save', $this );

		return $result;
	}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	
	/**
	 * lancia la WP_Query
	 *
	 */
	function get( $args = array() ) 						//PARAMETRI!!!
	{		
		if ( empty( $this->query ) ) 
		{
			$defaults = array
			(
				'reviewer_id'	=> 0,												//reviewER
				'recipient_id'	=> 0,
				'per_page'		=> 10,
				'paged'			=> 1
																										//aggiungere la variabile '$content',no?
			);

			$r = wp_parse_args( $args, $defaults );
			extract( $r );

			$query_args = array
			(
				'post_status'	 	=> 'publish',
				'post_type'		 	=> 'review',									//post_type: 'review'
				'posts_per_page'	=> $per_page,
				'paged'		 		=> $paged,
				'meta_query'	 	=> array()										//META_QUERY!
			);

//-------------------------------------------------------------------------------------------------------  	//PARAMETRI!!!

			// posso specificare.....
			if ( $reviewer_id )
			{
				$query_args['author'] = (array)$reviewer_id;
			}
			
			// posso specificare.....
			if ( $recipient_id ) 
			{
				$query_args['meta_query'][] = array
				(
					'key'	  => 'bp_review_recipient_id',
					'value'	  => (array)$recipient_id,
					'compare' => 'IN' 							// Allows $recipient_id to be an array ---?!
				);
			}

//------------------------------------
			

			$this->query = new WP_Query( $query_args );													//WP_QUERY
			
			$this->pag_links = paginate_links( array
			(
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


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------		
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