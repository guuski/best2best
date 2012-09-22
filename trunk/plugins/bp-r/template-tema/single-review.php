<?php get_header() ?>
<div id="content">
<div class="padder">

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
		
		$authorlogin		= get_the_author_meta('user_login');
		
		$autore_review_id 	= get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); //TODO bp_review_reviewer_id sostituire con AUTHOR 
		//ricava il DESTINATARIO
			//$obj_post 		 = get_post($id_post);			
			//$post_author_id  	 = $obj_post->post_author;
		
		$nome 				= xprofile_get_field_data( "Nome" , $autore_review_id);
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
<?php if ( is_user_logged_in() ) : ?>
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

<?php do_action( 'bp_before_blog_single_post' ) ?>
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
				
<?php else : ?>
	<?php $length= 100; { // Outputs an excerpt of variable length (in characters)
// 		global $post;
		$text = $post->post_excerpt;
		if ( '' == $text ) {
			$text = get_the_content('');
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]>', $text);
		}
			$text = strip_shortcodes( $text ); // optional, recommended
			$text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' to keep some formats; optional
			$text = substr($text,0,$length)."[..]";

		echo $text;
		
	}  ?>
	
	<div class="entry"><p><?php printf(__('<a href="%s">Registrati</a> oppure <a href="%s">accedi</a> per leggere questa recensione', 'custom'), esc_url( site_url( 'wp-login.php?action=register', 'login' ) ) , wp_login_url( get_permalink() )); ?></p></div>
	
<?php endif; ?>
	
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
