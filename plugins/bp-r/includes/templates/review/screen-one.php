<?php
//---------------------------------------------------------- SCREEN 1 (Le mie Review - quelle Ricevute) -----------------------------------------------------------------------------------------
?><?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="background: #EAEAEA; ">

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
	
<div id="item-body">											<!-- ma � ripetuto?!-->
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
	<h4><?php _e( 'Review ricevute', 'reviews' ) ?></h4>	
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//post_type: 'review'
			,	'meta_query'		=> array()				//META_QUERY!
//			,	'orderby'			=> 'title'
//			,	'order'				=> 'ASC'
//			, 	'posts_per_page		=> -1					//(?)
		);

		$query_args['meta_query'][] = array										//META_QUERY!
		(
				'key'	  => 'bp_review_recipient_id',
				//'value'	  => (array)$recipient_id,
				//'value'	  => (array)1,
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array ---eh?!
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
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
				<?php //the_content();  ?>	
				<?php //the_content('Leggi il resto della Review',true);?>				<!-- bisogna aggiungere dall EDITOR o con un filtro il tag <!--more-->
				<?php the_excerpt();  ?>	
			</div>			
			
			<!--CUSTOM FIELDS-->
			<div>								
				<!-- NB: 'true' perch�.... -->
			
				<?php //echo get_post_meta( $post->ID, 'bp_review_recipient_id', true ); ?>		<!-- vabbu� non mi serve!-->
				<?php echo get_post_meta( $post->ID, 'voto_prezzo', true );		?>
				<?php echo get_post_meta( $post->ID, 'voto_servizio', true );	?>	
				
				<!------------------------------------------------------------------------------------------------->
				<?php 	$voto_prezzo = get_post_meta( $post->ID, 'voto_prezzo', true );		?>
																													<!--  un bello SWITCH magari no?! -->
					<?php if ( $voto_prezzo == 1 ) : ?>	
						<img src="<?php echo BP_REVIEW_PLUGIN_DIR;?>/includes/img/star.png" class="star" id="star1">
					<?php endif; ?>					
				<!------------------------------------------------------------------------------------------------->					
			</div>				
			
			<!-- commenti -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			
		<?php endwhile; ?>

		
	<!-- ELSE -->				
	<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>												<!-- DOPPIONE 2 -->					
	
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->

	
	
<!-- ELSE -->				
<?php else: ?>	
	
	<!-- MESSAGGIO -->
	<h5><?php _e( 'L\' utente non ha ricevuto ancora nessuna Reviews', 'reviews' ) ?></h5>									<!-- DOPPIONE 1 -->
														

<?php endif; ?>

</div><!-- #item-body -->
</div><!-- .padder -->
</div><!-- #content -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

<!-- FOOTER -->	
<?php get_footer() ?>