<?php
/*
 
Template Name: Elenco Reviews
 
*/




/*
	[C] - 3 righe commentate
*/




/*

la lista deve essere ordinata per DATA e filtrabile per 

1)

tutti alberghi/ristoranti						-- OK
tutti fornitori									-- OK
	
	tutti miei contatti(Amici)

	miei contatti alberghi
	miei contatti fornitori

2)

ordinabile per 

	- DATA 										-- OK
	- RATINGS (somma dei parametri)				-- solo singolo RATING (prezzo, servizio,....)

*/
get_header() ?>

<div id="content">
<div class="padder">

<!-- DO ACTION ->
<?php do_action( 'bp_before_blog_page' ) ?>			

<!-- PAGE-->
<div class="page" id="blog-page" role="main">

<?php

	//DEBUG
	define('DEBUG',false);	//define('DEBUG',true); 
	
	//TEST
	define('TEST',false);	//define('TEST',true); 

	$order_by 	 = '';
	$asc_desc 	 = '';
	$author_type = '';
	
																		//[C] - 3 righe commentate
	//Recupera...
												//$order_by 	 = stripslashes($_POST['order_by']) or '';		//striptags 
												//$asc_desc 	 = stripslashes($_POST['asc_desc']) or '';		
												//$author_type = stripslashes($_POST['author_type']) or '';			
?>
	
<!--------------------------------------------------------------------review_filter_post_FORM ----------------------------------------------------------->
<form 
	action = ""
	method = "post" id="review-filter-post-form" 
	class  = "standard-form">										<!-- CLASS -->
		
	<!-- DO ACTION Before Review Filter Form -->
	<?php do_action( 'before_review_filter_form' ) ?>

	<!-- MESSAGGIO -->
	<h5> <?php // _e('Filtra le Reviews facendo clic sulle caselle di scelta: ','reviews');?> </h5>
				
	<div id="review-filter-select" > <!-- style = "display: inline; ">-->

	   <ul style = "display: inline; "> 
			<li style = "float: left;"> 
				<!-- <label for="order_by"> &nbsp; ORDINAMENTO &nbsp; </label>	-->
				<select name = "order_by" id = "order_by" 										
						onchange = "this.form.submit()"
				>				
					<!-- <option selected> Ordina per... </option>														-->
					<option value = "data" 				<?php selected( $order_by,'data'); ?>			> Data </option> 
					<option value = "voto_prezzo" 		<?php selected( $order_by,'voto_prezzo'); ?>	> Prezzo </option> 
					<option value = "voto_servizio" 	<?php selected( $order_by,'voto_servizio'); ?>	> Servizio </option> 
					
					<option value = "voto_qualita" > 													Qualit&agrave; </option> 
					<option value = "voto_puntualita" > 												Puntualit&agrave; </option> 
					<option value = "voto_affidabilita" > 												Affidabilit&agrave; </option> 
				</select>				
			</li>
			<li>
				<!-- <label for="asc_desc"> &nbsp; ASC or DESC &nbsp; </label>	-->
				<select name = "asc_desc" id = "asc_desc"  								
						onchange = 'this.form.submit()'
				>								
					<option value = "ASC" <?php selected( $asc_desc,'ASC'); ?>> ASC </option> 									
					<option value = "DESC" <?php selected( $asc_desc,'DESC'); ?>> DESC </option> 												
				</select>			
			</li>
			<li>
				<label for="author_type"> &nbsp; Autori </label>					
				<select name = "author_type" id = "author_type" 										
						onchange = 'this.form.submit()'
				>	
					<option value = "tutti" 					<?php selected( $author_type,'tutti') ?>					  	> tutti </option> 									
					<option value = "solo_amici" 				<?php selected( $author_type,'solo_amici') ?>			  		> tutti gli AMICI </option>			
					<option value = "amici_fornitori" 			<?php selected( $author_type,'amici_fornitori') ?>			  	> AMICI fornitori </option> 				
					<option value = "amici_alberghi_ristoranti"	<?php selected( $author_type,'amici_alberghi_ristoranti'); ?>	> AMICI Alberghi/Ristoranti </option> 												
					<option value = "Fornitore" 				<?php selected( $author_type,'Fornitore') ?>				 	> Fornitore </option> 									
					<option value = "Albergo/Ristorante"		<?php selected( $author_type,'Albergo/Ristorante'); ?>		  	> Albergo/Ristorante </option> 													

				</select>	
			</li>
<!--			
			<li>			
				<label for="recipient_type"> &nbsp; Destinatari </label>					
				<select name = "recipient_type" id = "recipient_type" 										
						onchange = 'this.form.submit()'
				>	
					<option value = "tutti" 				<?php //selected( $recipient_type,'tutti') ?>					> tutti </option> 									
					<option value = "fornitori" 			<?php //selected( $recipient_type,'fornitori') ?>				> fornitori </option> 									
					<option value = "alberghi/ristoranti"	<?php //selected( $recipient_type,'alberghi/ristoranti'); ?>	> alberghi/ristoranti </option> 													
				</select>					
			</li>
-->			
		</ul>	
	</div>

	<hr/>
 
	<!-- DO ACTION After Review Filter Form -->
	<?php do_action( 'after_review_filter_form' ) ?>	
					
</form>		
<!------------------------------------------------------------------------------------------------------------------------------------------------->

<?php
	
//---------------------------------------------
if(		
		$_SERVER['REQUEST_METHOD']=='POST' 
	|| 	isset($_POST['order_by']) 
	|| 	isset($_POST['asc_desc']) 		
	|| 	isset($_POST['author_type'])
)
//---------------------------------------------	
{		
	//Recupera...
	$order_by 	 = stripslashes($_POST['order_by']);		//striptags  
	$asc_desc 	 = stripslashes($_POST['asc_desc']);
	$author_id	 = stripslashes($_POST['author_id']);		
	$author_type = stripslashes($_POST['author_type']);			
	
	$query_args = array
	(
			'post_status'		=> 'publish'
		,	'post_type'			=> 'review'
																						//NAVIGATION 1/3
		,	'posts_per_page'	=> 5																				//,	'posts_per_page'	=> -1
		,	'paged' 			=> get_query_var('paged')
		
		
		, 	'order'				=> $asc_desc			//MET 2		
	);

	//� stato specificato ASC o DESC		
	if( $asc_desc =='ASC' ||  $asc_desc =='DESC' ) 
	{
		//$query_args['order'] = $asc_desc;			// MET 1 - non va! --> vd MET 2	sopra
	}
				
	//� stato specificato....
	if( $order_by =='voto_prezzo' || $order_by =='voto_servizio' 							 ) 
	{					
		$query_args['meta_key'] = $order_by;			
		$query_args['orderby'] = 'meta_value_num';	//'meta_value';						
	}
	else 
	{
		//ORDINA PER DATA
			//$query_args['orderby'] = 'date';								
	}

	//lancia la QUERY!
	$loop = new WP_Query($query_args);						
} 
else 
{	

	
	$query_args_alt = array
	(
			'post_status'		=> 'publish'
		,	'post_type'			=> 'review'
																						//PAGINATION 2/3
		,	'posts_per_page'	=> 5																				//,	'posts_per_page'	=> -1
		,	'paged' 			=> get_query_var('paged')
		
	);
	
	//lancia la QUERY!	
	$loop = new WP_Query($query_args_alt);											

}//end IF Request - FORM inviato			
?>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
	
<!-- IF -->					
<?php if ( $loop->have_posts() ) : ?>	
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-above' ); ?>
	<!------------------------------------------>
	
	<!-- WHILE 1 loop -->
	<?php while($loop->have_posts()): $loop->the_post();?>		
	
			
		<?php
			
			$author_type_params_checked = false;
			
			//WHILE 2
//			while( !$author_type_param_checked ) 
//			{
//----------------------------------------------------------

				//$post->post_author; --> salvalo in una VAR
				
			if(	$author_type != 'tutti' )
			{

				// IF - E1
				if(		
						$author_type == 'Fornitore' 
					||  $author_type == 'Albergo/Ristorante' 
					|| 	$author_type == 'amici_fornitori' 
					|| 	$author_type == 'amici_alberghi_ristoranti'
					//||  !$author_type_params_checked					//? � sicuro FALSE! togliere!
				)
				{			
				
					//---------------//---------------//---------------//---------------
					
					if(DEBUG) 
						echo '<br/>' . 'DENTRO IF - E1';
					
					$type = $author_type;
					
					if(DEBUG) {
						echo '<br/>' . 'type: ' . $type;
						echo '<br/>' . 'author_type: ' . $author_type;					
						echo '<br/>' . '...cambia type: ';
					}
					
					//---------------------------------------------------------------------------------------------
					if ($author_type == 'amici_fornitori' || $author_type == 'Fornitore') 
					{
						$type = 'Fornitore';
					}
					else if ($author_type == 'amici_alberghi_ristoranti' || $author_type == 'Albergo/Ristorante') 
					{
						$type = 'Albergo/Ristorante';
					}
					//---------------------------------------------------------------------------------------------	
					
					if(DEBUG) {
						echo '<br/>' . 'type: ' . $type;
						echo '<br/>' . 'author_type: ' . $author_type;
					}
					//---------------//---------------//---------------//---------------
				
					//IMPORTANTE
					$tipo_profilo = xprofile_get_field_data( "Tipo profilo" , $post->post_author);			
					
					//IF - I 						
					if($tipo_profilo != $type)  //if($tipo_profilo != $author_type)  			
					{				
					
						if(DEBUG) 
							echo '<br/>' . 'DENTRO IF - I';	
					
						//IMPORTANTE
						//------------------------------------------
						//
						$author_type_params_checked = true;
						//vai avanti!
						continue;						
						//------------------------------------------
					}
					else 
					{
						if(DEBUG) {
							echo '<br/>' .'<br/>' . $tipo_profilo;						
							echo '<br/>' . 'ID: ' . $post->post_author;
						}
					}			
				}//chiude IF - E1

				// IF - E2
				if(		
						//!$author_type_params_checked //== false
					//||	
						$author_type == 'solo_amici' 
					|| 	$author_type == 'amici_fornitori' 
					|| 	$author_type == 'amici_alberghi_ristoranti'					
				)
				{
				
				
// 	[BUG] - senza l'IF 'andrea' utente loggato viene escluso da risultati della ricerca!		
					if(bp_loggedin_user_id() != $post->post_author) 
					{
						$are_friends = friends_check_friendship( bp_loggedin_user_id() , $post->post_author );					
					
						//IF - I 
						if( !$are_friends )  
						{				
						
							//IMPORTANTE
							//------------------------------------------
							//
							$author_type_params_checked = true;
							//
							//vai avanti!
							continue;						
							//------------------------------------------
						}
						else 
						{
							if(DEBUG) {
								echo 'AMICI?: ' . $are_friends;	
								echo '<br/>' . 'ID: ' . $post->post_author;						
							}
						}							
					}	
					else 
					{
						continue;	
					}
				}//chiude IF - E2
				
			}//end IF	
			
			$author_type_params_checked = false;

//----------------------------------------------------------
				//$author_type_params_checked = false;
				
//			}// chiude il WHILE 2
		?>		
		
		<!-- DO-ACTION -->
		<?php do_action( 'bp_before_blog_post' ) ?>			
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 		
		
			<?php 
			//<!-- domain SBAGLIATO! -- correggere! ES: __( '%1$s <span>in %2$s</span>', 'buddypress' ) ----- sost buddypress con revies !-->
			?>

<!-- DESTINATARIO BOX -->			
<div class="author-box"> <!-- continua a chiamarsi AUTHOR BOX! -->

	<?php 
		$destinatario_review_id = get_post_meta( $post->ID, 'bp_review_recipient_id', true ); 
		$points = get_the_author_meta('media_voto_review',$destinatario_review_id);
	 	$nome = xprofile_get_field_data( "Nome" , $destinatario_review_id);
	 ?>	
			
	<?php //echo $destinatario_review_id ?>
	<?php echo get_avatar( $destinatario_review_id  , '70' ); ?>
	
	<p>		
		<?php //printf( _x( 'Recensione su %s', 'Recensione su...', 'reviews' ), bp_core_get_userlink(  $destinatario_review_id ) ) ?>
		<?php printf( _x('%s', 'reviews' ), bp_core_get_userlink(  $destinatario_review_id ) )  ?>
		<!-- <br />-->
		<?php if($points != '' ) : ?><br />(<?php echo __('Media','reviews').": "; printf("%.2d", $points) ?>)
		<?php endif;?>
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
<?php 						
						$prezzo 		= get_post_meta( $post->ID, 'voto_prezzo', true );		
						$servizio 		= get_post_meta( $post->ID, 'voto_servizio', true );
						$qualita 		= get_post_meta( $post->ID, 'voto_qualita', true );
						$puntualita		= get_post_meta( $post->ID, 'voto_puntualita', true );
						$affidabilita	= get_post_meta( $post->ID, 'voto_affidabilita', true );
						
						$media= ($prezzo + $servizio + $qualita + $puntualita + $affidabilita) / 5;
						
	//---------------------------------------------------------------------------------------
				
				$giudizio_review	 = get_post_meta( $post->ID, 'giudizio_review', true );
				$data_rapporto 		 = get_post_meta( $post->ID, 'data_rapporto', true );
				$tipologia_rapporto  = get_post_meta( $post->ID, 'tipologia_rapporto', true );
														
			?>
															
				<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				<ul class='star-rating'><li class='current-rating' style="width: <?php echo 25*$media;?>px"></li>				
			</ul></h2>



				<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>

				<!-- ENTRY -->
				<div class="entry">
					<?php //the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
					<?php $length= 200; { // Outputs an excerpt of variable length (in characters)
// 		global $post;
		$text = $post->post_excerpt;
		if ( '' == $text ) {
			$text = get_the_content('');
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]>', $text);
		}
			$text = strip_shortcodes( $text ); // optional, recommended
			$text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' to keep some formats; optional

			$output = strlen($text);
			
			$text = substr($text,0,$length).' <span class="read_more"><a href="'. get_permalink($post->ID) . '" class="read_more_link" >[...]</a></span>';
			

		echo apply_filters('the_excerpt',$text);
	}  ?>

					<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				</div>
			
				<br/>
				
				<!--CUSTOM FIELDS-->
				<div>								
					
			
				
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
    </div>		
		<!-- fine sezione RATING -->		
		
					
				</div>								
				
				<br/>

				<!-- -->
				<p class="postmetadata" style="margin:0; padding: 0; clear: none; overflow: visible;">
					<?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> 
					<span class="comments">
						<?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?>
					</span>
				</p>											
			</div>	<!-- chiude CONTENT -->							
		</div> <!-- chiude POST -->		
		
		<?php if (($wp_query->current_post + 1) < ($wp_query->post_count)) 	
				echo '<div class="post-item-divider"><hr/></div>';	?>

							
		<!-- DO-ACTION -->
		<?php do_action( 'bp_after_blog_post' ) ?>

	<!--End WHILE 1 loop -->					
	<?php endwhile; ?>
	

<!-- PAGINATION --->																										<!-- PAGINATION 3/3 -->
<!--	
	<div class="navigation">
		<div class="next-posts" style="float:left;"><?php next_posts_link(__('&laquo; Precedenti Recensioni'), $loop->max_num_pages) ?></div>
		<div class="prev-posts" style="float:right;"><?php previous_posts_link(__('Nuove Recensioni&raquo;'), $loop->max_num_pages) ?></div>
	</div>
-->

<?php		

$total_pages = $loop->max_num_pages;  	//loop
  
if ($total_pages > 1)
{  
  
  $current_page = max(1, get_query_var('paged'));  
    
  echo '<div class="page_nav">';  
    
  echo paginate_links(array
	(  
      'base' => get_pagenum_link(1) . '%_%',  
      'format' => '/page/%#%',  
      'current' => $current_page,  
      'total' => $total_pages,  
      'prev_text' => __('&larr; Previous Entries', 'buddypress'),  
      'next_text' => __( 'Next Entries &rarr;', 'buddypress' )  
    )
	);  
  
	echo '</div>';      
}  	
?>	
<!-- fine PAGINATION --->		
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-below' ); ?>
	<!------------------------------------------>	
	
<!-- ELSE -->
<?php else : ?>
																										<!-- [I] -->
	<h2 class="center"><?php //_e( 'Not Found', 'buddypress' ) ?></h2>
	<?php //get_search_form() ?>
	
<!-- End IF -->
<?php endif; ?>


<!-- Reset POSTDATA	-->													<!-- IMPORTANTE ! -->
<?php wp_reset_postdata() ?>																	
								
</div><!-- .page -->

<!-- DO ACTION -->
<?php do_action( 'bp_after_blog_page' ) ?>

</div><!-- .padder -->
</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>