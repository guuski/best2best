<?php get_header() ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			
			
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<!-- DESTINATARIO BOX -->			
<div class="author-box"> <!-- continua a chiamarsi AUTHOR BOX! -->

	<?php $destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true ); ?>
	<?php $nome = xprofile_get_field_data( "Nome" , $destinatario_review_id);?>	
			
	<?php //echo $destinatario_review_id ?>
	<?php echo get_avatar( $destinatario_review_id  , '70' ); ?>
	
	<p>		
		<?php printf( _x( 'Recensione su %s', 'Recensione su...', 'reviews' ), bp_core_get_userlink(  $destinatario_review_id ) ) ?>
	</p>
	
</div>
<!-- fine BOX -->		

					<div class="post-content">

<!------v2 AUTORE: ------------------------------->					
<small style = "float: right;"> <strong>	

	<?php $authorlogin= get_the_author_meta('user_login')?>
	<?php $autore_review_id = get_post_meta( $post->ID, 'bp_review_reviewer_id', true ); ?>
	<?php $nome = xprofile_get_field_data( "Nome" , $autore_review_id);?>	
	
	<?php _e('Autore: ');?> 
	<a href="<?php echo bp_core_get_user_domain($authorlogin).$authorlogin ?>">
		<?php //the_author_meta('user_nicename');?>
		<?php echo $nome; ?>	
	</a>
</strong></small>								
						<h2 class="posttitle"><?php the_title(); ?>				</h2>

						
						<p class="date">
							<?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?>
							<span class="post-utility alignright"><?php edit_post_link( __( 'Edit this entry', 'buddypress' ) ); ?></span>
						</p>

						<div class="entry">
							<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>

							
			

							
							
							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							
							
							<?php //wp_list_comments();							 ?>
							
							
						</div>
						
						
<?php 	
				$prezzo = get_post_meta( $post->ID, 'voto_prezzo', true );		
				$servizio = get_post_meta( $post->ID, 'voto_servizio', true );
				$qualita = get_post_meta( $post->ID, 'voto_qualita', true );
				$puntualita = get_post_meta( $post->ID, 'voto_puntualita', true );
				$affidabilita = get_post_meta( $post->ID, 'voto_affidabilita', true );
//---------------------------------------------------------------------------------------
			
			$giudizio_review	 = get_post_meta( $post->ID, 'giudizio_review', true );
			$data_rapporto 		 = get_post_meta( $post->ID, 'data_rapporto', true );
			$tipologia_rapporto  = get_post_meta( $post->ID, 'tipologia_rapporto', true );
													
		?>


		
<br/>
			
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


		
<br/> <br/>

<!-- FORM per COMMENTI -->

	<?php
		$destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true );
	
		if(
				bp_loggedin_user_id() == $post->post_author 
			||  bp_loggedin_user_id() == $destinatario_review_id
		)  
		{
			comments_template();
		}		
		else 
		{
		
			//echo 'dovresti vedere i commenti qui!'; 
			
			
//---------------------------------------------------------------------------------------------------//---------------------------------------------------------------------------------------------------								
			
			
			$post_id = $post->ID;
			//$post_id_2 = the_ID();
			
			
			
			$pre_HTML ="";
			$post_HTML ="";

			global $wpdb;
/*
			$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
			WHERE comment_approved = '1' 	
			AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 10";
*/			
		//AND ID = '1'

		
		

/*					
			$sql = 				
				"SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt 
			
				FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
			
				WHERE comment_approved = '1' 				
				AND comment_type = '' 
				AND post_password = '' 
			
				AND post_type='review'
				AND ID='262'
			
				ORDER BY comment_date_gmt DESC LIMIT 10
			";


			//$comments = $wpdb->get_results($sql);

			
			

		$comments = 
			$wpdb->get_results( 
				$wpdb->prepare( 
					"SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt 
				
					FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
				
					WHERE comment_approved = '1' 				
					AND comment_type = '' 
					AND post_password = '' 
				
					AND post_type='review'
					AND ID=%s
				
					ORDER BY comment_date_gmt DESC LIMIT 10
					
					",
					$post_id
				)
			);
			
			
			
			
			
			$output = $pre_HTML;
			
				$output .= '<div id="comments">';
			
			//$output .= "\n<ul>";
				$output .= '<ol class = "commentlist">';

			foreach ($comments as $comment) 
			{
				$output .= 
				
				//"\n<li>" 
				'<div class="comment-body" >'
				
				.	strip_tags($comment->comment_author) .":" 
				.   "<a href=\"" . get_permalink($comment->ID)
				.   "#comment-" . $comment->comment_ID 
				. 	"\" title=\"on ".$comment->post_title 
				.   "\">" . strip_tags($comment->com_excerpt)
				.   "</a>"
				
					.	"</div>";
				//.	"</li>";
				
			}

			//$output .= "\n</ul>";
				$output .= "</ol>";
				$output .= "</div>";
			
			$output .= "\n\n post_ID:   " . $post_id . "  post_ID 2:   " . $post_id_2;
			
			
			$output .= $post_HTML;
	
			echo $output;

*/			
			
			
//---------------------------------------------------------------------------------------------------
  
  
			$args = array(
				'walker'            => null,
				'max_depth'         => 5,
				'style'             => 'ul',
				//'callback'          => null,
												'callback'          => mytheme_comment,
				'end-callback'      => null,
				'type'              => 'all',
/*				
				'page'              => ,
				'per_page'          => ,
*/				
				'avatar_size'       => 32,
				'reverse_top_level' => null
				//,
				//'reverse_children'  =>  
			); 
				
			//echo '<ol class="commentlist">';
				//wp_list_comments('type=comment');			
			//echo '</ol>';
			
				//wp_list_comments($args);
			
			//<ul class="commentlist">
				//wp_list_comments('type=comment&callback=mytheme_comment'); 
				//wp_list_comments('type=comment&callback=mytheme_comment_2'); 
			//</ul>
			
			
			
			//comments_template('/frisco-comments.php');
		}
				
	?>		
<!-- fine FORM per COMMENTI -->			
			
			
			

		
		
		
		
		
		
		
		
						<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?>&nbsp;</p>

						<div class="alignleft"><?php //previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> %title' ); ?></div>
						<div class="alignright"><?php //next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
						
						
<!--	
						<?php if($comments) : ?>  
    <?php foreach($comments as $comment) : ?>  
        <?php if ($comment->comment_approved == '0') : ?>  
        <?php endif; ?>  
    <?php endforeach; ?>  
<?php else : ?>  
	<?php echo 'no commenti';?>
<?php endif; ?>  
-->
		
		<?php //wp_list_comments();?>
		
		
<?php
/*
$args = array(
    'status' => 'approved',
    'number' => '5',
    'post_id' => get_the_ID()
    );
    $comments = get_comments($args);
    foreach($comments as $comment) :
        echo( $comment->get_avatar . $comment->comment_author . '<br />' . $comment->comment_content);
    endforeach;		
*/		
?>



<?php
    
	global $post, $wp_query;
	
    $args = array(
        'ID'       => $post->ID,
        'status' => 'approve',
        'order'   => 'ASC'
    );
	
	
	
	$args_2 = array(
			'post_id'	 => get_the_ID()
		,	'status'  	 => 'approved'
		,	'style'		 => 'div'
		,	'reply_text' => 'ciaodssfsd'
	  //, 'number' => '5'		
    );


	$wp_query->comments = get_comments( $args_2);	
    //$wp_query->comments = get_comments( $args );
?>

	<div class="commentlist">
	<?php wp_list_comments( ); ?>
	</div>

<?php	
    //comment_form();
?>	
		
		
</div>

</div>
				
				

			
<?php

 

?>
			
			


			<?php endwhile; else: ?>

				<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ) ?></p>

			<?php endif; ?>


		
</div> <!-- page -->
<?php do_action( 'bp_after_blog_single_post' ) ?>
</div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar() ?>
<?php get_footer() ?>