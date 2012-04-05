<?php
/*
 
Template Name: Elenco Reviews
 
*/
/*
/*
la lista deve essere ordinata per data e filtrabile per 
tutti alberghi/ristoranti
tutti fornitori
tutti miei contatti(Amici)
miei contatti alberghi
miei contatti fornitori

ordinabile per data o per ratings (somma dei parametri)				---- OK singolo RATING (prezzo, servizio)

*/
get_header() ?>
<div id="content">
<div class="padder">

<!-- DO ACTION ->
<?php do_action( 'bp_before_blog_page' ) ?>			

<!-- PAGE-->
<div class="page" id="blog-page" role="main">


							
<?php

	//DEBUG
	define('DEBUG',false);	//define('DEBUG',true); 

	// -- GLOBALS
	global $query; 
	global $query_string;
	global $wp_query;
		
	//------------------------------------------------------------- Inizializza------------------------------------------------------------------
	
	//Recupera...
	$order_by 	= stripslashes($_POST['order_by']);		//striptags 
	$asc_desc 	= stripslashes($_POST['asc_desc']);	
	$author_id  = stripslashes($_POST['author_id']);		
	//-------------------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------
	// DEBUG		
	//--------------------------------------------------------------------------------------------------
	
	if(DEBUG ) 
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			echo 'QUERY_STRING:	 '. "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $query_string;
			echo '<br/>'.'<br/>';
			
			if($wp_guery != NULL) {
				echo 'QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$query;
				echo '<br/>'.'<br/>';			
			}
			if($wp_guery != NULL) {
				echo 'WP_QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$wp_query;	//var dump
				echo '<br/>'.'<br/>';		
			}
			
			echo 'order_by:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $order_by; 
			echo '<br/>'.'<br/>';
			echo 'meta value:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $meta_value; 
			echo '<br/>'.'<br/>';
			echo 'asc_desc:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $asc_desc; 
			echo '<br/>'.'<br/>';	
			echo 'author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
			echo '<br/>'.'<br/>';	
		}
	}
	//---------------------------------------------------------------------------------------------------
?>
	
<!--------------------------------------------------------------------review_filter FORM ----------------------------------------------------------->
<form 
	action = ""
	method="post" id="review-filter-post-form" class="standard-form">
		
	<!-- DO ACTION Before - Search Form -->
	<?php do_action( 'before_review_filter_form' ) ?>

	<!-- MESSAGGIO -->
	<h5> <?php  //_e('Ordina....','reviews');?> </h5>
				
	<div id="review-filter-select">
			
		<p>	&nbsp; Orderby &nbsp;				
			<select name = "order_by" id = "order_by" >
				<option selected> Ordina per... </option>										
				<option value = "voto_prezzo" 	<?php selected( $order_by,'voto_prezzo'); ?>> voto prezzo </option> 
				<option value = "voto_servizio" <?php selected( $order_by,'voto_servizio'); ?>> voto servizio </option> 
			</select>			
		</p>		
		
		<p>	&nbsp; ASC or DESC &nbsp;				
			<select name = "asc_desc" id = "asc_desc" >								
				<option selected> ASC o DESC...</option>						
				<option value = "asc" <?php selected( $asc_desc,'ASC'); ?>> ASC </option> 									
				<option value = "desc" <?php selected( $asc_desc,'DESC'); ?>> DESC </option> 								
				<!-- <option value = "0" <?php //selected( $asc_desc,0); ?>> non specificato </option> 									-->				
			</select>			
		</p>	

		<p>	&nbsp; AUTHOR ID &nbsp;				
			<select name = "author_id" id = "author_id" >				
				<option value = "0" <?php //selected( $author_id,0); ?>> 0 tutti gli autori di Review </option> 									
				<option value = "1" <?php //selected( $author_id,1); ?>> 1 admin </option> 									
				<option value = "10" <?php //selected( $author_id,10); ?>> 10 andrea </option> 									
			</select>			
		</p>	
		
		<div id="review-filter-submit">								
			<input type="submit" name="review-filter-submit" id="review-filter-submit" value="<?php _e( 'Filtra', 'reviews' ); ?>" />
		</div>			
			
	</div>		
	 
	<!-- DO ACTION After - Search Form -->
	<?php do_action( 'after_review_filter_form' ) ?>	
			
	<!-- [WPNONCE] -->
	<?php //wp_nonce_field( 'bp_review_filter_review' ); ?>				
</form>		

<?php
	
	//----------------------------------------------------------------------------------------------------------------------------------------------			
	if(		
			$_SERVER['REQUEST_METHOD']=='POST' 
		|| 	isset($_POST['order_by']) 
		|| 	isset($_POST['asc_desc']) 
		|| 	isset($_POST['author_id']) 
	)//---------------------------------------------
	
	{		
		//Recupera...
		$order_by 	= stripslashes($_POST['order_by']);		//striptags  
		$asc_desc 	= stripslashes($_POST['asc_desc']);
		$author_id  = stripslashes($_POST['author_id']);		

				
		//---------------------------------------------------------------------------------------------------
		// DEBUG		
		//---------------------------------------------------------------------------------------------------
		if(DEBUG) 
		{
			echo '<br/>'.'<br/>';
			echo 'FORM inviato....';
			echo '<br/>'.'<br/>';
			echo 'POST order_by:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $order_by; 
			echo '<br/>'.'<br/>';
			echo 'POST meta value:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $meta_value; 
			echo '<br/>'.'<br/>';
			echo 'POST asc_desc:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $asc_desc; 
			echo '<br/>'.'<br/>';			
			echo 'author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
			echo '<br/>'.'<br/>';	
			//echo 'QUERY_STRING:	 '. "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $query_string;
			//echo '<br/>'.'<br/>';
			//echo 'QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$query;
		}
		//---------------------------------------------------------------------------------------------------		
		
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//post_type: 'review'

//			,	'meta_query'		=> array()				//META_QUERY!

//			,	'meta_key'			=> $order_by			//Meta_KEY!
//			,	'orderby'			=> $order_by
			
//			, 	'order'				=> 'DESC'
			, 	'order'				=> $asc_desc			

//			,   'author'			=> $author_id
//			,   'author'			=> 0
		);
	
		//è stato specificato....ASC o DESC		
		if( $asc_desc =='ASC' ||  $asc_desc =='DESC' ) 
		{
			//$query_args['order'] = $asc_desc;
		}
					
		// è stato specificato un AUTORE id
		if ( $author_id )
		{					
			$query_args['author'] = $author_id; //$query_args['author'] = (array)$author_id;
			
			if(DEBUG) 
			{
				echo 'AUTORE scelto!'.'--> author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
				echo '<br/>'.'<br/>';	
			}
		}

		//è stato specificato....
		if( $order_by =='voto_prezzo' || $order_by =='voto_servizio'  ) 
		{		
			//													//FUNZIONA!!!
			$query_args['meta_key'] = $order_by;			
			$query_args['orderby'] = 'meta_value_num';								
		}

		//lancia la QUERY!
		$loop = new WP_Query($query_args);						
	} 
	else 
	{		
			
		$loop = new WP_Query('post_type=review');				//post_type='review'
	
	} //end IF Request - form inviato
			
?>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
	
<!-- IF -->					
<?php if ( $loop->have_posts() ) : ?>	
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-above' ); ?>
	<!------------------------------------------>
	
	<!-- WHILE -->
	<?php while($loop->have_posts()): $loop->the_post();?>		
	
		<!-- DO-ACTION -->
		<?php do_action( 'bp_before_blog_post' ) ?>		

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
		<!-- <article id="post-<?php //the_ID(); ?>" <?php //post_class(); ?>>
		
			<!-- domain SBAGLIATO! -- correggere! ES: __( '%1$s <span>in %2$s</span>', 'buddypress' ) ----- sost buddypress con revies ?!-->

			<div class="author-box">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
				<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
			</div>

			<div class="post-content">
				<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

				<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>

				<!-- ENTRY -->
				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				</div>
			
				<!-- -->
				<p class="postmetadata">
					<?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> 
					<span class="comments">
						<?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?>
					</span>
				</p>							
				
				<!-- REVIEW-META-->
				<div class = "review-meta" >
					<h4> Prezzo: 	<?php echo get_post_meta($post->ID, 'voto_prezzo',true); 	?> 	  </h4>
					<h4> Serivzio: 	<?php echo get_post_meta($post->ID, 'voto_servizio',true); ?> </h4>
				</div>
				
			</div>	<!-- chiude CONTENT -->							
		</div> <!-- chiude POST -->
		<!-- </article>-->
		
		<?php
			if (($wp_query->current_post + 1) < ($wp_query->post_count)) 
			{
				echo '<div class="post-item-divider"><hr/></div>';				
			}
		?>
		
		<!-- DO-ACTION -->
		<?php do_action( 'bp_after_blog_post' ) ?>

	<!--END WHILE -->					
	<?php endwhile; ?>
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-below' ); ?>
	<!------------------------------------------>	
	
<!-- ELSE -->
<?php else : ?>
	
	<h2 class="center"><?php //_e( 'Not Found', 'buddypress' ) ?></h2>
	<?php //get_search_form() ?>
	
<!-- End IF -->
<?php endif; ?>
			
								<!-- Reset POSTDATA	-->													<!-- IMPORTANTE ! -->
								<?php wp_reset_postdata() ?>																	
								
								<!-- Reset QUERY	-->													<!-- IMPORTANTE ! -->
								<?php //wp_reset_query(); ?>									<!-- solo quando si usa 'query_posts()'-->
								
</div><!-- .page -->

<!-- DO ACTION -->
<?php do_action( 'bp_after_blog_page' ) ?>

</div><!-- .padder -->
</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>