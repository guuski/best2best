<?php
//---------------------------------------------------------- SCREEN 1 (Le mie Review - quelle Ricevute) -----------------------------------------------------------------------------------------
?><?php get_header() ?>

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


	
	
	
<!----------------------------------------------------------- LISTA 2 - Reviews scritte per l'utente del profilo -------------------------------------------------------->	

	
<!-- IF -->							<!-- va bene sta CONDIZIONE?! per ora s� ...fa cagare!-->		

<?php if ( $lista_reviewers = bp_review_get_reviewers_list_for_user( bp_displayed_user_id() ) ) : ?>

		
	<!-- MESSAGGIO  -->
	<h5><?php //_e( 'Review ricevute', 'reviews' ) ?></h5>	
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//'review'
			,	'meta_query'		=> array()				//META_QUERY!
		);

		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_review_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
	?>
				
	<!-- IF -->					
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
				
			<div class="title">		
			<?php $authorlogin= get_the_author_meta('user_login')?>
				<small style = "float: right;"><strong><?php _e('Autore: ');?> <a href="<?php echo bp_core_get_user_domain($authorlogin).$authorlogin?>"><?php the_author_meta('user_nicename');?></a></strong></small>

				<br /> 				
				<?php  
					the_title('<h4 class="pagetitle"> <a href="' . 	get_permalink() . '" title="'    .	the_title_attribute('echo=0')    .	'"rel="bookmark">','</a></h4>');
				?>
			</div>	
			
			<div class="entry">
				<?php //the_content();  ?>	
				<?php //the_content('Leggi il resto della Review',true);?>				<!-- bisogna aggiungere dall EDITOR o con un filtro il tag <!--more-->
				<?php the_excerpt();  ?>	
			</div>			
			
			
			<br/> 
			
			
			<!--CUSTOM FIELDS-->
<div>								
				<?php 	
					$prezzo = get_post_meta( $post->ID, 'voto_prezzo', true );		
					$servizio = get_post_meta( $post->ID, 'voto_servizio', true );
					$qualita = get_post_meta( $post->ID, 'voto_qualita', true );
					$puntualita = get_post_meta( $post->ID, 'voto_puntualita', true );
					$affidabilita = get_post_meta( $post->ID, 'voto_affidabilita', true );
				?>
		<div id="new-review-rating">	
			<div class="rating-container"><span class="rating-title">Prezzo</span> <ul id="prezzo" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$prezzo;?>px"></li>			
			</ul>
			</div>		
			<div class="rating-container"><span class="rating-title">Servizio</span> <ul id="servizio" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$servizio;?>px"></li>
			</ul>
			</div>	
			<div class="rating-container"><span class="rating-title">Qualit&agrave;</span> <ul id="qualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$qualita;?>px"></li>			
			</ul>
			</div>		
			<div class="rating-container"><span class="rating-title">Puntualit&agrave;</span> <ul id="puntualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$puntualita;?>px"></li>
			</ul>
			</div>	
			<div class="rating-container"><span class="rating-title">Affidabilit&agrave;</span> <ul id="affidabilita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$affidabilita;?>px"></li>			
			</ul>
			</div>		
			<!-- <div id='current-rating-result'></div>  used to show "success" message after vote -->
					  
		</div>	<!-- fine sezione RATING -->
  																								
																								
				<!------------------------------------------------------------------------------------------------->					
</div>				
			
			<br/> 
			
			<!-- commenti -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			
			<hr />
			
		<?php endwhile; ?>

		
	<?php else: ?>		
		
	
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>																	
	
	<?php endif; ?>
	
	
	<?php wp_reset_postdata() ?>		
			
<?php else: ?>	
	
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>	
														

<?php endif; ?>

</div><!-- #item-body -->
</div><!-- .padder -->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						
</div><!-- #content -->
</div>
<!-- SIDEBAR --->


</div>
<!-- FOOTER -->	
<?php get_footer() ?>