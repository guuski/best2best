<?php
//--------------------------------------------------- LISTA 5 - Le mie Referrals  ------------------------------------------------------------------------------->		
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
	
	<!--------------------------------------------------- LISTA 5 - Le mie Referrals  ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Le mie Referrals', 'referrals' ) ?></h4>
		
	<?php		

		$query_args = array
		(
				'post_status'		=> 'publish'																				//---------PUBLISH
			
			,	'post_type'			=> 'referral'				//'referral'
			,   'author'			=> bp_displayed_user_id()											
			
//			,	'meta_query'		=> array()				//META_QUERY!
		);
/*
		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => bp_displayed_user_id(),
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
		
			
			
			<div class="title">				
											
				<br/>
				
				<!-------------------------------------- get AUTHOR --Autore REFERRAL -------------------------------------->
													
				<!-------------------------------------- confusione AUTORE RECIPIENT!! -------------------------------------->
				<!-- $author_referral_name , $author_referral_id
				<!----------------------------------------------------------------------------------------------------------->
				
				<?php $recipient_referral_id	 = get_post_meta( $post->ID, 'bp_referral_recipient_id', true ); ?>			
				
				<?php $recipient_referral_name 	 = xprofile_get_field_data( "Nome" , $recipient_referral_id); ?>													
				<?php $authorlogin 				 = get_the_author_meta('user_login', $recipient_referral_id);?>										

				<small style = "float: right;">
					<strong>
						<?php  _e('ricevuto da: ');?> 						
						
						<a href="
							<?php echo bp_core_get_user_domain($authorlogin).$authorlogin?>">							
							<?php echo $recipient_referral_name; ?>							
						</a>
						
					</strong>
				</small>
			
				<!-- TITOLO -->						
				<h5> <?php the_title();?> </h5> 		
				
			</div>	
								
			
			<?php 	
					
				$tipologia_rapporto 	= get_post_meta( $post->ID, 'tipologia_rapporto', true );		
				$anzianita_rapporto 	= get_post_meta( $post->ID, 'anzianita_rapporto', true );
				$utente_consigliato 	= get_post_meta( $post->ID, 'utente_consigliato', true );
				$voto_complessivo 		= get_post_meta( $post->ID, 'voto_complessivo', true );
			?>
			
			<br/> 
			
			<p><strong> <?php _e( 'Tipologia Rapporto', 'referrals' ); ?>:  </strong> <?php echo $tipologia_rapporto ?></p>
									
			<p><strong> <?php _e( 'Anzianit&agrave; Rapporto', 'referrals' ); ?>:  </strong> <?php echo $anzianita_rapporto ?></p>						
			
			<p><strong> <?php _e( 'Utente Consigliato', 'referrals' ); ?>:  </strong> <?php echo $utente_consigliato ?></p>
									
			<div class="rating-container"><span class="rating-title"><?php _e( 'Giudizio: ', 'referrals' ); ?></span> 
				<ul id="prezzo" class='star-rating'>	
					<li class='current-rating' style="width: <?php echo 25*$voto_complessivo;?>px"></li>			
				</ul>
			</div>	
	
	
			<hr />
		<?php endwhile; ?>
	
<?php else: ?>		
		
	<!-- MESSAGGIO -->
	<h6><?php _e( 'L\'utente non ha ricevuto alcun REFERRAL ancora', 'referrals' ) ?></h6>												
		<h5><?php //_e( 'le richieste REFERRAL non sono state ancore accettate (controlla richieste pendenti nella TAB: REFERRAL RICHIESTE DA ME) o non hai ancora chiesto nessun REFERRAL', 'referrals' ) ?></h5>														
		
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