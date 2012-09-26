<?php
//--------------------------------------------------- Review NEGATIVE inviate da me in attesa di esser Moderate ------------------------------------------------------------------------------->		
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
	
	<!-- --------------------------------------------------- Review NEGATIVE inviate da me in attesa di esser Moderate ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Review NEGATIVE inviate da me in attesa di esser Moderate', 'reviews' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status' => 'pending'				// PENDING
			,	'post_type'	  => 'review'				//'review'
			,   'author'	  => bp_displayed_user_id()	
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
				//$autore_id   = $post->post_author; 
				//$autore_nome = xprofile_get_field_data( "Nome" , $autore_id); 		
		
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
							
	<?php endwhile; ?>
	
<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php _e( ' non ci sono Review Negative che attendono di essere moderate', 'reviews' ) ?></h6>												
		
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