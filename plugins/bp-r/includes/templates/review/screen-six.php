<?php
//--------------------------------------------------- Review ANONIME scritte da me -------------------------------------------------------------------------------		
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
	
<div id="item-body">									

	<?php do_action( 'bp_before_member_body' ); ?>
	
		<div class="item-list-tabs no-ajax" id="subnav">
			<ul>
				<!-- -->
				<?php bp_get_options_nav() ?>
			</ul>
		</div>
	
	<!-- --------------------------------------------------- Review ANONIME scritte da me ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Review ANONIME scritte da me', 'reviews' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status' => 'publish'			// PUBLISH
			,	'post_type'	  => 'review'			// 'review'			
			,	'meta_query'  => array()							//META_QUERY!
		);
		
		//META_QUERY!
		$query_args['meta_query'][] = array					
		(
				'key'	  => 'bp_review_anonymous_reviewer_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 									
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);		
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			

			<div class="title">																			
				<h4><?php  
					the_title('<a href="' . get_permalink() . '" title="' .	the_title_attribute('echo=0') .	'"rel="bookmark">','</a>');
					?>
				</h4>			
				<!------------------------------------------------------------------------------------------------------------------->								
			</div>									
			<br/>												
							
		<?php endwhile; ?>
	
	<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php _e('nessuna Review Anonime', 'reviews' ) ?></h6>												
		
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