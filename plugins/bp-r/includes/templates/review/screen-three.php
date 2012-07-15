<?php
//--------------------------------------------------------------- SCREEN 2 --> Scrivi Review-----------------------------------------------------------------------------------------
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
	
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'										//'review'
			,   'author'			=> bp_displayed_user_id()							

			
																									//PAGINATION 1/2
			,	'posts_per_page'	=> 3																				//,	'posts_per_page'	=> -1
			,	'paged' 			=> get_query_var('paged')						
		);
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);			
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
		
			<div class="title">
				
				<?php $authorlogin = get_the_author_meta('user_login', get_post_meta( $post->ID, 'bp_review_recipient_id', true ));?>
			<?php $autore_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true ); ?>
	<?php $nome = xprofile_get_field_data( "Nome" , $autore_review_id);?>	
			
			
				<small style = "float: right;"><strong>
					<?php  _e('Recensione su: ');?> 
					<a href="<?php echo bp_core_get_user_domain(get_post_meta( $post->ID, 'bp_review_recipient_id', true ))?>">
					<?php  // the_author_meta('user_nicename', get_post_meta( $post->ID, 'bp_review_recipient_id', true )) 
					echo $nome;?> 
					</a></strong></small>
					<br />
					<?php  the_title('<h4 class="pagetitle"> <a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '"rel="bookmark">','</a></h4>');?>
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
					$prezzo 		= get_post_meta( $post->ID, 'voto_prezzo', true );		
					$servizio 		= get_post_meta( $post->ID, 'voto_servizio', true );
					$qualita 		= get_post_meta( $post->ID, 'voto_qualita', true );
					$puntualita 	= get_post_meta( $post->ID, 'voto_puntualita', true );
					$affidabilita 	= get_post_meta( $post->ID, 'voto_affidabilita', true );
		//---------------------------------------------------------------------------------------
					
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
		<!-------------------------------------------------------------------------------------->		
		
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
		
			<!------------------------------------------------------------------------------------------------->					
			</div>				
			
			<br/>
			
			<!-- COMMENTI -->
			<?php comments_popup_link('Nessun Commento', '1 Commento', '% Commenti'); ?> 
			<hr />
			
		<?php endwhile; ?>
		
		<!-- PAGINATION --->																											<!-- PAGINATION 2/2 -->
			
				<!-- <div class="navigation">-->
					<div class="next-posts"><?php next_posts_link('&laquo; Older Entries', $loop->max_num_pages) ?></div>
					<div class="prev-posts"><?php previous_posts_link('Newer Entries &raquo;', $loop->max_num_pages) ?></div>
				<!-- </div>-->
			

			<?php		

			$total_pages = $loop->max_num_pages;  	//loop
			  
			if ($total_pages > 1)
			{  
			  
				$current_page = max(1, get_query_var('paged'));  
					
				 echo '<div class="page_nav">';  
					
				 echo paginate_links(array
					(  
					  'base' 		=> get_pagenum_link(1) . '%_%',  
					  'format' 		=> '/page/%#%',  
					  'current' 	=> $current_page,  
					  'total' 		=> $total_pages,  
					  'prev_text' 	=> 'Prev',  
					  'next_text' 	=> 'Next'  
					)
				);  
				  
				echo '</div>';      
			}  	
			?>	
			<!-- fine PAGINATION --->			
			
	<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h5><?php _e( 'L\' utente non ha scritto nessuna Review ', 'reviews' ) ?></h5>												
		
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- -------------------------------check chiusura DIV sottostanti--------------------------------------------------------------------------------------------------------------->

</div><!-- #item-body -->
</div><!-- .padder -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

</div><!-- #content -->
</div>
</div>

<!-- FOOTER -->	
<?php get_footer() ?>