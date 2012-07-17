<?php
//---------------------------------------------------------- SCREEN 1 (Le mie Review - quelle Ricevute) -----------------------------------------------------------------------------------------
?><?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="padding:19px 0 0 0;" >

			<div id="item-header" style="padding:19px 0 0 0;">
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

	

<?php if ( $lista_reviewers = bp_review_get_reviewers_list_for_user( bp_displayed_user_id() ) ) : ?>

		
	<!-- MESSAGGIO  -->
	<h5><?php //_e( 'Review ricevute', 'reviews' ) ?></h5>	
		
	<?php		
/*	
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//'review'
			,	'meta_query'		=> array()				//META_QUERY!
																		
			,	'posts_per_page'	=> -1																		//PAGINATION 1/2			//,	'posts_per_page'	=> -1
			,	'paged' 			=> get_query_var('paged')			
		);

		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_review_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
		

		//DEBUG
			//print_r( $query_args);


*/
?>

<!--------------------------------------------------------------------FORM ----------------------------------------------------------->
<form 
	action = ""
	method = "post" id="show-all-reviews-form" 
	class  = "standard-form">							

	<!-- bottone  -->	
	<div >								
		<input type="submit" name="show-all-reviews" id="show-all-reviews" value="<?php _e( 'mostra tutte le review', 'reviews' ); ?>" />
	</div>	
					
</form>		
<!------------------------------------------------------------------------------------------------------------------------------------------------->			
<?php
//---------------------------------------------
if(		
	$_SERVER['REQUEST_METHOD']=='POST' 	|| 	isset($_POST['show-all-reviews']) 	
  )

{		

	$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//'review'
			,	'meta_query'		=> array()				//META_QUERY!
																		
			,	'posts_per_page'	=> 3																		//PAGINATION 1/2			//,	'posts_per_page'	=> -1
//			,	'paged' 			=> get_query_var('paged')			
		);

		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_review_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
		

		//DEBUG
			//print_r( $query_args);					
} 
else 
{	

/*
	$query_args = array
	(
			'post_status'		=> 'publish'
		,	'post_type'			=> 'review'				//'review'
		,	'meta_query'		=> array()				//META_QUERY!
																	
		,	'posts_per_page'	=> -1																		//PAGINATION 1/2			//,	'posts_per_page'	=> -1
//		,	'paged' 			=> get_query_var('paged')			
	);

	$query_args['meta_query'][] = array					//META_QUERY!
	(
			'key'	  => 'bp_review_recipient_id',
			'value'	  => (array)bp_displayed_user_id(),
			'compare' => 'IN' 							// Allows $recipient_id to be an array 
	);		
	
	//lancia la QUERY!
	$loop = new WP_Query($query_args);	
	

	//DEBUG
		//print_r( $query_args);
*/		

}//end IF Request - FORM inviato	
?>			
				
	<!-- IF 2/2 annidato -->					
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
		
			<?php 	
				$prezzo 			= get_post_meta( $post->ID, 'voto_prezzo', true );		
				$servizio 			= get_post_meta( $post->ID, 'voto_servizio', true );
				$qualita 			= get_post_meta( $post->ID, 'voto_qualita', true );
				$puntualita 		= get_post_meta( $post->ID, 'voto_puntualita', true );
				$affidabilita 		= get_post_meta( $post->ID, 'voto_affidabilita', true );				
				$giudizio_review	= get_post_meta( $post->ID, 'giudizio_review', true );
				$data_rapporto 		= get_post_meta( $post->ID, 'data_rapporto', true );
				$tipologia_rapporto = get_post_meta( $post->ID, 'tipologia_rapporto', true );														
				
				//MEDIA
				$media= ($prezzo + $servizio + $qualita + $puntualita + $affidabilita) / 5;
				
				if($giudizio_review == 'positivo')  			
				{
					$color = 'green';
				}
				if($giudizio_review == 'neutro')  
				{
					$color = 'orange';
				}
				
				if($giudizio_review == 'negativo')  
				{
					$color = 'red';
				}
							
			?>	
			
			<div class="title">		

				<?php $authorlogin= get_the_author_meta('user_login')?>
	<?php $autore_review_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); ?>
	<?php $nome = xprofile_get_field_data( "Nome" , $autore_review_id);?>	
	
		<small style = "float: right;"><strong>
					<?php _e('Autore: ');?> <a href="<?php echo bp_core_get_user_domain($autore_review_id)?>">
					<?php //the_author_meta('user_nicename');?>
					<?php echo $nome; ?>	</a></strong></small>

				<br /> 				
				
				<h4><?php  
					the_title('<a href="' . get_permalink() . '" title="' .	the_title_attribute('echo=0') .	'"rel="bookmark">','</a>');
				?><ul id="prezzo" class='star-rating'>	
						<li class='current-rating' style="width: <?php echo 25*$media;?>px"></li>
				</ul>
				</h4>				
			</div>	
			
			<div class="entry">
				<?php the_excerpt(); ?>	
			</div>			
						
			<br/> 
						
			<!--CUSTOM FIELDS-->
			<div>								
							
				<div>
					<p>
						<strong > <?php _e( 'Giudizio Review: ', 'reviews' ); ?></strong> 
						<span style = "color: <?php echo $color?>"> <?php echo $giudizio_review ?></span>
					</p>
					<p><strong> <?php _e( 'Data Inizio Rapporto: ', 'reviews' ); ?> </strong><?php echo $data_rapporto ?></p>
					<p><strong> <?php _e( 'Tipologia', 'reviews' ); ?>:  </strong> <?php echo $tipologia_rapporto ?></p>
				</div>		
					
				<br/> 		
					
				<div id="new-review-rating">	
				
					<div class="rating-container"><span class="rating-title"><?php _e( 'Prezzo', 'reviews' ); ?></span> <ul id="prezzo" class='star-rating'>	
						<li class='current-rating' style="width: <?php echo 25*$prezzo;?>px"></li>			
					</ul>
					</div>		
					<div class="rating-container"><span class="rating-title"><?php _e( 'Servizio', 'reviews' ); ?></span> <ul id="servizio" class='star-rating'>				
						<li class='current-rating' style="width: <?php echo 25*$servizio;?>px"></li>
					</ul>
					</div>	
					<div class="rating-container"><span class="rating-title"><?php _e( 'Qualit&agrave;', 'reviews' ); ?></span> <ul id="qualita" class='star-rating'>							
						<li class='current-rating' style="width: <?php echo 25*$qualita;?>px"></li>			
					</ul>
					</div>		
					<div class="rating-container"><span class="rating-title"><?php _e( 'Puntualit&agrave;', 'reviews' ); ?></span> <ul id="puntualita" class='star-rating'>				
						<li class='current-rating' style="width: <?php echo 25*$puntualita;?>px"></li>
					</ul>
					</div>	
					<div class="rating-container"><span class="rating-title"> <?php _e( 'Affidabilit&agrave;', 'reviews' ); ?></span> <ul id="affidabilita" class='star-rating'>				
						<li class='current-rating' style="width: <?php echo 25*$affidabilita;?>px"></li>			
					</ul>
					</div>		
					<!-- <div id='current-rating-result'></div>  used to show "success" message after vote -->
							  
				</div>	<!-- fine sezione RATING -->
																																																						
			</div>	<!-- fine CUSTOM FIELDS-->			
					
			<br/> 
					
			<!-- commenti -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			
			<hr />
					
					
					
		<?php endwhile; ?>
		
				

				
				
<!--DEBUG -->				 
<?php print_r( $query_args); ?>



	<?php else: ?>		
		
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>																	

	<?php endif; ?>

	<!-- RESET -->
	<?php wp_reset_postdata() ?>		
		

<?php else: ?>	
	
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>	

<?php endif; ?>

<!-- -------------------------------chiusura DIV sottostanti CHECKED  --------------------------------------------------------------------------------------------------------------->

</div><!-- #item-body -->					<!-- OK -->
</div><!-- .padder -->						<!-- chiude MAIN COLUMN! -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						

</div><!-- #content -->						<!-- chiude SIDEBAR SQUEEZE -->	
</div>										<!-- chiude PADDER -->			
</div>										<!-- chiude CONTENT-->			

<!-- FOOTER -->	
<?php get_footer() ?>