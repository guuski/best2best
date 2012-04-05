<?php
//--------------------------------------------------------- SCREEN 3 - Le mie Review (qulle Scritte da me) -----------------------------------------------------------------------------------------
?>

<!-- HEADER -->
<?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="background: #EAEAEA;  padding: 19px 19px 0;">

			<div id="item-header">
				<!-- buddypress MEMBER HEADER -->
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>		<!-- locate_template () -->
			</div>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<!-- -->
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>
			
<!------------------------------------------>			
<div id="sidebar-squeeze">										<!-- pezza per FRISCO -->
	<div id="main-column">
<!------------------------------------------>				
	
<div id="item-body">											<!-- ma � ripetuto?!-->

	<?php do_action( 'bp_before_member_body' ); ?>
	
		<div class="item-list-tabs no-ajax" id="subnav">
			<ul>
				<!-- -->
				<?php bp_get_options_nav() ?>
			</ul>
		</div>


	
	<!--------------------------------------------------- LISTA 3  Le Reviews scritta da me ------------------------------------------------------------------------------->	
	
	
	<!-- MESSAGGIO  -->
	<h4><?php _e( 'Le Reviews scritta da me', 'reviews' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//post_type: 'review'
			,	'meta_query'		=> array()										//META_QUERY!
			,   'author'			=> bp_displayed_user_id()											
		);
		
		//bp_core_get_user_displayname (	)

		$query_args['meta_query'][] = array											//META_QUERY!
		(
			'key'	  => 'bp_review_reviewer_id',																		
//			'value'	  => (array)bp_loggedin_user_id(),			//-----� SBAGLIATO!!
//			'value'	  => (array)bp_displayed_user_id(),			//OK funziona!
			'compare' => 'IN' 							
		);		

		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
		
		// set $more to 0 in order to only get the first part of the post
		global $more;
		$more = 0;

	?>
		
		
	<!-- IF -->					
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
		
			<div class="title">		<!-- boh-----ho sparato! -->
				<?php 
					the_title('<h4 class="pagetitle"> <a href="' . 	get_permalink() . '" title="'    .	the_title_attribute('echo=0')    .	'"rel="bookmark">','</a></h4>');
				?>
			</div>	
			
			<div class="entry">
				<?php //the_excerpt();  ?>	
				<?php the_content( 'Read the full post »' );?>	
			</div>			
			
			<!--CUSTOM FIELDS-->
			<div>											
				<?php echo get_post_meta( $post->ID, 'voto_prezzo', true );		?>
				<?php echo get_post_meta( $post->ID, 'voto_servizio', true );	?>							
			</div>				
			
			<!-- commenti -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			
		<?php endwhile; ?>

		
	<!-- ELSE -->				
	<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h5><?php _e( 'L\' utente non ha scritto nessuna Review ', 'reviews' ) ?></h5>												<!-- DOPPIONE 2 -->					
	
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->


	
</div><!-- #item-body -->
</div><!-- .padder -->


<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->
</div><!-- #content -->
</div>
</div>
<!-- FOOTER -->	
<?php get_footer() ?>