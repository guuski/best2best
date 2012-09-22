<?php
//--------------------------------------------------- Review NEGATIVE da MODERARE ------------------------------------------------------------------------------->		
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
	
	<!--------------------------------------------------- Review NEGATIVE da MODERARE ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Le Review NEGATIVE da MODERARE ', 'reviews' ) ?></h4>
		
	<?php		
		$query_args = array
		(
				'post_status'		=> 'pending'			// PENDING
			,	'post_type'			=> 'review'				//'review'
		);

		//lancia la QUERY!
		$loop = new WP_Query($query_args);		
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
							
			<!-------------------------------------- get AUTHOR --Autore -------------------------------------->
			
			<?php 
				$autore_id	   = $post->post_author; 
				$autore_nome   = xprofile_get_field_data( "Nome" , $autore_id); 		
				//$autore_nome = bp_core_get_user_displayname($autore_id); 
				//$autore_nome = bp_core_get_user_displayname( $autore_id, false ); 
				//$autore_nome = bp_members_get_user_nicename( $autore_id ); 			
				$authorlogin	  = get_the_author_meta('user_login');
				$autore_review_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); // TODO bp_review_reviewer_id sostituire con AUTHOR 
				$nome 			  = xprofile_get_field_data( "Nome" , $autore_review_id);
			?>	
									
			<div class="title">												
				<!------------------------------------------------------------------------------------------------------------------->				
				<small style = "float: right;"><strong>
					<?php _e('Autore: ');?> <a href="<?php echo bp_core_get_user_domain($autore_review_id)?>">					
					<?php echo $nome; ?>	
				</a></strong></small>				
				<br /> 												
				<h4><?php  
					the_title('<a href="' . get_permalink() . '" title="' .	the_title_attribute('echo=0') .	'"rel="bookmark">','</a>');
					?>
				</h4>			
				<!------------------------------------------------------------------------------------------------------------------->								
			</div>									
			<br/>											
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<!--  2 FORM -
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<!--- (1) review-moderation[FORM] ACCETTA -->
	<form action = "<?php bp_review_form_action_screen_four() ?> " method="post" id="review-moderation-form" class="standard-form"> 
							
		<!-- bottone ACCETTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="accetta-review-negativa" id="accetta-review-negativa" value="<?php _e( 'Pubblica', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
		</div>					
										
		<!-- [WPNONCE] ACCETTA -->
		<?php wp_nonce_field( 'accetta-review-negativa' ); ?>				
	</form>		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
	
	<!--- (2) review-moderation[FORM] RIFIUTA -->
	<form action = "<?php bp_review_form_action_screen_four() ?> " method="post" id="review-moderation-form" class="standard-form"> 

		<!-- bottone RIFIUTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="rifiuta-review-negativa" id="rifiuta-review-negativa" value="<?php _e( 'Elimina', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />				
		</div>					
										
		<!-- [WPNONCE] RIFIUTA-->
		<?php wp_nonce_field( 'rifiuta-review-negativa' ); ?>				
	</form>			
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
							
	<?php endwhile; ?>
	
<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php _e( ' nessuna Review NEGATIVA da moderare', 'reviews' ) ?></h6>												
		
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