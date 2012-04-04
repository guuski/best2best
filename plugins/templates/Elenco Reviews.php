<?php 
/*
 
Template Name: Elenco Reviews
 
*/

get_header() ?>
<div id="content">
<div class="padder">

<!-- DO ACTION ->
<?php do_action( 'bp_before_blog_page' ) ?>			

<!-- PAGE-->
<div class="page" id="blog-page" role="main">
					
					
					
					
<!---------------------------------------review_filter FORM ---------------------------------------->
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
					<!-- <option selected> date </option>		-->
					<option value = "date" <?php selected( $order_by,'date'); ?>> date </option> 					
					<option value = "voto_prezzo" <?php selected( $order_by,'voto_prezzo'); ?>> voto prezzo </option> 
					<option value = "voto_servizio" <?php selected( $order_by,'voto_servizio'); ?>> voto servizio </option> 
					<option value = "rating" <?php selected( $order_by,'rating'); ?>> rating (voto totale) </option> 					
				</select>			
			</p>		
			
			<p>	&nbsp; ASC or DESC &nbsp;				
				<select name = "asc_desc" id = "asc_desc" >
					<!-- <option selected> date </option>		-->
					<option value = "asc" <?php selected( $asc_desc,'ASC'); ?>> ASC </option> 					
					<option value = "desc" <?php selected( $asc_desc,'DESC'); ?>> DESC </option> 					
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

	// -- GLOBALS
	global $query, $query_string;
	
	// -- RESET previous QUERY
	//wp_reset_query();
	//wp_reset_postdata();
	
	// -- Inizializza
	$order_by = 'date';
	$asc_desc = 'ASC';
	
	echo 'QUERY_STRING:	 '. "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $query_string;
	echo '<br/>'.'<br/>';
	echo 'QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$query;
	echo '<br/>'.'<br/>';
	
	//-----------------------------------------------------------------------------------------------------------------------------
	if($_SERVER['REQUEST_METHOD']=='POST' || isset($_POST['order_by']) ||  isset($_POST['asc_desc'])) 	
	//if($_SERVER['REQUEST_METHOD']=='GET' || isset($_GET['order_by']) ) 	
	{		
			echo '<br/>'.'<br/>';
			echo 'FORM inviato....';
			echo 'POST order_by:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .stripslashes($_POST['order_by']); 
			echo '<br/>'.'<br/>';
			echo 'POST asc_desc:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .stripslashes($_POST['asc_desc']); 
			echo '<br/>'.'<br/>';
			echo 'QUERY_STRING:	 '. "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $query_string;
			echo '<br/>'.'<br/>';
			echo 'QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$query;
					
			//
			$order_by = stripslashes($_POST['order_by']);
			$asc_desc = stripslashes($_POST['asc_desc']);
			//$asc_desc = $_POST['asc_desc'];
			//$asc_desc = $_GET['asc_desc'];
			
			$query_args = array
			(
					'post_status'		=> 'publish'
				,	'post_type'			=> 'review'				//post_type: 'review'
				,	'meta_query'		=> array()				//META_QUERY!
				,	'orderby'			=> $order_by
				, 	'order'				=> $asc_desc
	//			, 	'posts_per_page		=> -1					//(?)	
			);

			$query_args['meta_query'][] = array								
			(
	//				'key'	  => 'voto_prezzo',
					'key'	  => $order_by,
					//'value'	  => (array)1,
					//'value'	  => 3,
					'compare' => 'IN' 							
			);		


			//lancia la QUERY!
			$loop = new WP_Query($query_args);	
	}		
	?>
	
	
	<!-- IF -->					
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!------------------------------------------>
		<?php //bp_dtheme_content_nav( 'nav-above' ); ?>
		<!------------------------------------------>
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>		
		
			<!-- DO-ACTION -->
			<?php //do_action( 'bp_before_blog_post' ) ?>		

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="author-box">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
					<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
				</div>

				<div class="post-content">
					<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

					<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>

					<div class="entry">
						<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
					</div>

					<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
				</div>

			</div>
			
			<!-- DO-ACTION -->
			<?php //do_action( 'bp_after_blog_post' ) ?>

		<!--END WHILE -->					
		<?php endwhile; ?>
		
		<!------------------------------------------>
		<?php //bp_dtheme_content_nav( 'nav-below' ); ?>
		<!------------------------------------------>	
		
	<!-- ELSE -->
	<?php else : ?>
		
		<h2 class="center"><?php //_e( 'Not Found', 'buddypress' ) ?></h2>
		<?php //get_search_form() ?>
		
	<!-- End IF -->
	<?php endif; ?>

			
	<!-- RESET POSTDATA	-->																	<!-- IMPORTANTE ! -->
	<?php wp_reset_postdata() ?>							
						
						
						
</div><!-- .page -->

<!-- DO ACTION -->
<?php do_action( 'bp_after_blog_page' ) ?>

</div><!-- .padder -->
</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>
