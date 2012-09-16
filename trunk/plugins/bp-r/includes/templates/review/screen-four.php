<?php
//--------------------------------------------------- Review ANONIME da MODERARE ------------------------------------------------------------------------------->		
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
	
	<!--------------------------------------------------- Review ANONIME da MODERARE ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Le Review ANONIME da MODERARE ', 'reviews' ) ?></h4>
		
	<?php		

		$query_args = array
		(
				'post_status'		=> 'pending'						//----------pending			
			,	'post_type'			=> 'review'				//'review'
			//,   'author'			=> bp_displayed_user_id()	
			//,	'posts_per_page'	=>  -1	
			//,	'meta_query'		=> array()				//META_QUERY!
		);
/*
		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_referral_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
*/		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
	
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
							
			<!-------------------------------------- get AUTHOR --Autore -------------------------------------->
			
			<?php $autore_id	 = $post->post_author; ?>			
			<?php $autore_nome 	 = xprofile_get_field_data( "Nome" , $autore_id); ?>					
			<?php //$autore_nome 	 = bp_core_get_user_displayname($autore_id); ?>		
			<?php //$autore_nome 	 = bp_core_get_user_displayname( $autore_id, false ); ?>		
			<?php //$autore_nome 	 = bp_members_get_user_nicename( $autore_id ); ?>		
			
						
			
			<div class="title">				
								
				<!-- MESSAGGIO  -->
				<h5><?php _e( 'REVIEW ANONIMA scritta da: ', 'reviews' ) ?>
				
					<span>
						<a href = "<?php echo bp_core_get_user_domain($autore_id) ?>">	
							<?php echo $autore_nome; ?> <!-- $autore_id-->
						</a>								
					</span>					
				</h5>
			</div>	
								
			<br/>									
		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<!--  2 FORM -
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	<!--- review-FORM ACCETTA-->
	<form action = "<?php 
	
			bp_reviews_post_form_action_SCREEN_4() 
	
	?> " method="post" id="review-anonime-form" class="standard-form"> 
							
		<!-- bottone ACCETTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="accetta-review-anonima" id="accetta-review-anonima" value="<?php _e( 'Pubblica', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
		</div>					
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'accetta-review-anonima' ); ?>				
	</form>		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
	
	<!--- review-FORM RIFIUTA-->
	<form action = "<?php 
		
		bp_reviews_post_form_action_SCREEN_4() 
			
			?> " method="post" id="review-anonime-form" class="standard-form"> 

		<!-- bottone RIFIUTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="rifiuta-review-anonima" id="rifiuta-review-anonima" value="<?php _e( 'Elimina', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />				
		</div>					
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'rifiuta-review-anonima' ); ?>				
	</form>			
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
							
	<?php endwhile; ?>
	
<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php _e( ' nessuna Review Anonima da moderare', 'reviews' ) ?></h6>												
		
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