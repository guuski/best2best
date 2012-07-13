<?php get_header() ?>
<div id="content">
<div class="padder">

<!-- DO_ACTION -->
<?php do_action( 'bp_before_blog_single_post' ) ?>

<div class="page" id="blog-single" role="main">

<!-- WP LOOP -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
<!-- POST -->			
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<!-- DESTINATARIO Review BOX -->			
<div class="author-box"> 														<!-- NOTA BENE: continua a chiamarsi AUTHOR BOX! -->
	
	<?php
		
		$destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true );
		$points = get_the_author_meta('media_voto_review',$destinatario_review_id);
		$nome = xprofile_get_field_data( "Nome" , $destinatario_review_id);
	?>					
		
	<?php echo get_avatar( $destinatario_review_id  , '70' ); //echo $destinatario_review_id  ?>		
	<p>	
		<?php printf( _x( 'Recensione su %s', 'Recensione su...', 'reviews' ), bp_core_get_userlink(  $destinatario_review_id ) ) ?>
		<br /><?php if($points != '' ) : ?>(<?php echo __('Media','reviews').": "; printf("%.2d", $points) ?>)
		<?php endif;?>  
	</p>		
	
</div> <!-- fine BOX -->		

<!----------------------------------- CONTENUTO Post----------------------->
<div class="post-content">

<!------ AUTORE Review BOX -------->					
<small style = "float: right;"> <strong>	
	<?php
		$authorlogin= get_the_author_meta('user_login');
		$autore_review_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); 
		$nome = xprofile_get_field_data( "Nome" , $autore_review_id);
	?>	
	
	<?php _e('Autore: ');?> 
	<a href="<?php echo bp_core_get_user_domain($authorlogin).$authorlogin ?>">		
		<?php echo $nome; //the_author_meta('user_nicename');?>
	</a>
</strong> </small> <!-- fine BOX -->									
			<?php 	
	
		$prezzo 			= get_post_meta( $post->ID, 'voto_prezzo', true );		
		$servizio 			= get_post_meta( $post->ID, 'voto_servizio', true );
		$qualita 			= get_post_meta( $post->ID, 'voto_qualita', true );
		$puntualita 		= get_post_meta( $post->ID, 'voto_puntualita', true );
		$affidabilita 		= get_post_meta( $post->ID, 'voto_affidabilita', true );		
		$giudizio_review	= get_post_meta( $post->ID, 'giudizio_review', true );
		$data_rapporto 		= get_post_meta( $post->ID, 'data_rapporto', true );
		$tipologia_rapporto = get_post_meta( $post->ID, 'tipologia_rapporto', true );
		
		$media= ($prezzo + $servizio + $qualita + $puntualita + $affidabilita) / 5;
	
	//	$giudizio_review = '';
		$color = '';
		
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
<!-- -->						
<h2 class="posttitle"><?php the_title(); ?><ul id="prezzo" class='star-rating'>	
						<li class='current-rating' style="width: <?php echo 25*$media;?>px"></li>
					</ul></h2>

	<p class="date">
		<?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?>
		<span class="post-utility alignright"><?php edit_post_link( __( 'Edit this entry', 'buddypress' ) ); ?></span>
	</p>

	<div class="entry">
		<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>			
	</div>
	
	

	<br/>
			
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
			<li class='current-rating' style="width: <?php echo 25*$prezzo;?>px"></li></ul>
		</div>		
		<div class="rating-container"><span class="rating-title"><?php _e( 'Servizio', 'reviews' ); ?></span> <ul id="servizio" class='star-rating'>				
			<li class='current-rating' style="width: <?php echo 25*$servizio;?>px"></li> </ul>
		</div>	
		<div class="rating-container"><span class="rating-title"><?php _e( 'Qualit&agrave;', 'reviews' ); ?></span> <ul id="qualita" class='star-rating'>							
			<li class='current-rating' style="width: <?php echo 25*$qualita;?>px"></li>	</ul>
		</div>		
		<div class="rating-container"><span class="rating-title"><?php _e( 'Puntualit&agrave;', 'reviews' ); ?></span> <ul id="puntualita" class='star-rating'>				
			<li class='current-rating' style="width: <?php echo 25*$puntualita;?>px"></li></ul>
		</div>	
		<div class="rating-container"><span class="rating-title"> <?php _e( 'Affidabilit&agrave;', 'reviews' ); ?></span> <ul id="affidabilita" class='star-rating'>				
			<li class='current-rating' style="width: <?php echo 25*$affidabilita;?>px"></li></ul>
		</div>				
	</div>	<!-- fine sezione RATING -->		
<?php 
		
		if($points != '' && false) 
		{
		?>
			<br/>
			
			<div id="new-review-rating" style="border: 1px solid #CCC;display: inline-block;">		
				<div class="rating-container"><span class="rating-title" style="width:auto;"><?php _e( 'Punteggio medio utente', 'reviews' ); ?></span> 
					<ul id="prezzo" class='star-rating'>	
						<li class='current-rating' style="width: <?php echo 25*$points;?>px"></li>
					</ul>
				</div>	
			</div>
			
			<br/> 
		<?php 
		}?>
<br/> 
<br/>


<!----------------- FORM per COMMENTI ------------------------->


<!-- DO_ACTION --->
<?php do_action( 'bp_before_comments' ) ?>

<?php

comments_template();

if ( !empty( $bp->loggedin_user->id ) ) 
{
		
	$destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true );
	//L' UTENTE � LOGGATO!
	if(
			bp_loggedin_user_id() == $post->post_author 
		||  bp_loggedin_user_id() == $destinatario_review_id
	)  
	{
		//comments_template();
	}		
	else 
	{
		echo "<style>div.comment-options{display:none} div#respond{display:none}</style>";
	}//chiude ELSE
}

?>		

<!-- DO_ACTION --->
<?php do_action( 'bp_after_comments' ) ?>
	
<!-- fine FORM per COMMENTI -->			

					
					
<!-- NAVIGATION -->					
	<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?>&nbsp;</p>
	
	<?php if(function_exists('wp_pagenavi')) 
	{ 
		wp_pagenavi(); 
	} 
	else 
	{
	
	}
	?>  
	<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> %title' ); ?></div>
	<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
	
	<div class="navigation">
		<div class="alignleft"><?php //previous_posts_link('&laquo; Previous') ?></div>
		<div class="alignright"><?php //next_posts_link('More &raquo;') ?></div>
	</div>
	
	
<!-- fine NAVIGATION -->	

</div>

</div>

<?php endwhile; else: ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ) ?></p>						<!-- LOCALIZATION! -->
	
<?php endif; ?>
		
</div> <!-- page -->
<?php do_action( 'bp_after_blog_single_post' ) ?>
</div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar() ?>
<?php get_footer() ?>