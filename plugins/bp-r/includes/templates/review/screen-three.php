<?php
//------------------------------------------------ SCREEN 3 - Le Reviews scritta da me------------------------------------------------
get_header() ?>
	
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
		
	<!--------------------------------------------------- LISTA 3  Le Reviews scritta da me ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Le Reviews scritta da me', 'reviews' ) ?></h4>

<?php

//---------------------------------------------------------
if(		
		$_SERVER['REQUEST_METHOD']=='POST' 	
	||  isset($_POST['show-all-reviews']) 		
  )

{														
	$query_args = array
	(
			'post_status'		=> 'publish'
		,	'post_type'			=> 'review'						
		,   'author'			=> bp_displayed_user_id()							
												
		,	'posts_per_page'	=>  -1	
	);															
	
} 
else 
{	

	$query_args = array
	(
			'post_status'		=> 'publish'
		,	'post_type'			=> 'review'						
		,   'author'			=> bp_displayed_user_id()																			
		,	'posts_per_page'	=> 3	
	);

}//end IF Request - FORM inviato	

//langia la QUERY!
$loop = new WP_Query($query_args);

?>			
			
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
		
		 <!------------------------------------------------>			
		 <!--//  if tipo review negativa  � ANONIMA
		 <!------------------------------------------------>	
		
		 <!------------------------------------------------>	
		 <!-- Patch 1
		 <!------------------------------------------------>	
		 <?php //if ( $post->post_type == "pending") : ?>	
			<?php //continue;?>
		 <?php //endif; ?>
		 
		 <!------------------------------------------------>	
		 <!-- Patch 2
		 <!------------------------------------------------>			 
		 <?php
/*		 
			$autore_review_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); 
		 	if ($autore_review_id != $post->post_author) 
			  continue;
*/			  
		?>					
		
		<!------------------------------------------------>			
		<?php
			$id_autore_anonimo_review 	= get_post_meta( $post->ID, 'bp_review_anonymous_reviewer_id', true ); 
			$nome_autore_anonimo_review = xprofile_get_field_data( "Nome" , $id_autore_anonimo_review);
		?>	
				
			<div class="title">
								
				<small style = "float: right;"><strong>
					<?php  _e('Recensione su: ');?> 

					<a href="<?php echo bp_core_get_user_domain(get_post_meta( $post->ID, 'bp_review_recipient_id', true ))?>">	
							<?php  the_author_meta('user_nicename', get_post_meta( $post->ID, 'bp_review_recipient_id', true ))	?>			
					</a></strong>
				</small>
				
				<br />
				
				<!-- Se l'utente � dello STAFF--->
				<?php if (	bp_review_loggedin_user_is_staff_member() && bp_review_displayed_user_is_staff_member() ) : ?>					
														
					<small style = "float: right;"><strong>
						<?php  _e('Autore Anonimo: ');?> 

						<a href="<?php  echo bp_core_get_user_domain($autore_anonimo_review_id) ?>">	
								<?php  echo $nome_autore_anonimo_review	?>			
						</a></strong>
					</small>			
				<?php endif ?>				
									
				<?php  the_title('<h4 class="pagetitle"> <a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '"rel="bookmark">','</a></h4>');?>
					
			</div>	

<?php if ( is_user_logged_in() ) : ?>
			<div class="entry">
				<?php the_excerpt();  ?>	
			</div>			
			
			<br/> 
			
			<!--CUSTOM FIELDS-->
			<div>								
				<?php 	
					$prezzo 		= get_post_meta( $post->ID, 'voto_prezzo', true );		
					$servizio 		= get_post_meta( $post->ID, 'voto_servizio', true );
					$qualita 		= get_post_meta( $post->ID, 'voto_qualita', true );
					$puntualita 	= get_post_meta( $post->ID, 'voto_puntualita', true );
					$affidabilita 	= get_post_meta( $post->ID, 'voto_affidabilita', true );
					
					$giudizio_review	 = get_post_meta( $post->ID, 'giudizio_review', true );
					$data_rapporto 		 = get_post_meta( $post->ID, 'data_rapporto', true );
					$tipologia_rapporto  = get_post_meta( $post->ID, 'tipologia_rapporto', true );
				?>				
			<div>
			
			<?php 			
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
					
			</div>							
		
			<br/>
			
			<!-- COMMENTI -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			<hr />
	
<?php else : ?>
	
		<?php 
			$length= 100; 
	{ 
			// Outputs an excerpt of variable length (in characters)
			//global $post;
			$text = $post->post_excerpt;
			if ( '' == $text ) 
			{
				$text = get_the_content('');
				$text = apply_filters('the_content', $text);
				$text = str_replace(']]>', ']]>', $text);
			}
			$text = strip_shortcodes( $text ); // optional, recommended
			$text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' to keep some formats; optional
			$text = substr($text,0,$length);

			echo apply_filters('the_excerpt',$text);
	}  ?>
	
	<div class="entry">
		<p><?php printf(__('<a href="%s">Registrati</a> oppure <a href="%s">accedi</a> per leggere questa recensione', 'custom'), esc_url( site_url( 'wp-login.php?action=register', 'login' ) ) , wp_login_url( get_permalink() )); ?></p>
	</div>
	
<?php endif; ?>
					

<!-----------------------------------------fine WHILE ------------------------------------------------------------------------------->							
<?php endwhile; ?>			

			
<!-------------------FORM bottone ------------------------>			
<?php if ( 		!($_SERVER['REQUEST_METHOD']=='POST' 	
			|| 	isset($_POST['show-all-reviews']) )) : ?>	
			
<form action = ""	method = "post" id="show-all-reviews-form" 	class  = "standard-form">							
	<div >								
		<input type="submit" name="show-all-reviews" id="show-all-reviews" value="<?php _e( 'mostra tutte le review', 'reviews' ); ?>" />
	</div>	
</form>		

<br/>
<br/>

<?php endif; ?>
<!---------------fine FORM bottone ------------------------>		


<?php else: ?>		
		
	<!-- MESSAGGIO -->
	<h5><?php _e( 'L\' utente non ha scritto nessuna Review ', 'reviews' ) ?></h5>												
		
<?php endif; ?>
	
<!-- IMPORTANTE -->
<?php wp_reset_postdata() ?>		
	
<!-- -------------------------------//TODO check chiusura DIV sottostanti------------------------------------------------------------------->

</div><!-- #item-body -->
</div><!-- .padder -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

</div><!-- #content -->
</div>
</div>

<!-- FOOTER -->	
<?php get_footer() ?>
