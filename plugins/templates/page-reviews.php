<?php
/*
 
Template Name: Elenco Reviews
 
*/
/*
/*

la lista deve essere ordinata per DATA e filtrabile per 

tutti alberghi/ristoranti
tutti fornitori
	
	tutti miei contatti(Amici)

	miei contatti alberghi
	miei contatti fornitori

ordinabile per 
	- DATA 
	- RATINGS (somma dei parametri)				---- OK singolo RATING (prezzo, servizio)

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

	// -- GLOBALS
	global $query; 
	global $query_string;
	global $wp_query;
		
	//------------------------------------------------------------- Inizializza------------------------------------------------------------------
	
	//Recupera...
	$order_by 	= stripslashes($_POST['order_by']);		//striptags 
	$asc_desc 	= stripslashes($_POST['asc_desc']);	
	$author_id  = stripslashes($_POST['author_id']);		
	$author_type  = stripslashes($_POST['author_type']);		
	//-------------------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------
	// DEBUG		
	//--------------------------------------------------------------------------------------------------
	
	if(DEBUG ) 
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			echo 'QUERY_STRING:	 '. "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $query_string;
			echo '<br/>'.'<br/>';
			
			if($query != NULL) {
				echo 'QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$query;
				echo '<br/>'.'<br/>';			
			}
			if($wp_guery != NULL) {
				echo 'WP_QUERY: '.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" .$wp_query;	//var dump
				echo '<br/>'.'<br/>';		
			}
			
			echo 'order_by:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $order_by; 
			echo '<br/>'.'<br/>';			
			echo 'asc_desc:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $asc_desc; 
			echo '<br/>'.'<br/>';	
			echo 'author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
			echo '<br/>'.'<br/>';	
		}
	}
	//---------------------------------------------------------------------------------------------------
?>
	
<!--------------------------------------------------------------------review_filter FORM ----------------------------------------------------------->
<form 
	action = ""
	method="post" id="review-filter-post-form" class="standard-form">
		
	<!-- DO ACTION Before - Search Form -->
	<?php do_action( 'before_review_filter_form' ) ?>

	<!-- MESSAGGIO -->
	<h5> <?php  _e('Filtra le Reviews facendo clic sulle caselle di scelta: ','reviews');?> </h5>
				
	<div id="review-filter-select"> 
			
		<!-- By default the first coded <option> will be displayed or selected as the default. We can change this using the selected attribute.	-->
		<!-- <option selected		=	"yes"			>Conneticut -- CN</option>-->		
		<!--
		Preselected Select Option
		The options can be preselected using the entity "selected".
			<option name=three value=three selected> three </option>	
		-->
				
		<div style = "float: left">
			<label for="order_by"> &nbsp; ORDINAMENTO &nbsp; </label>	
			<select name = "order_by" id = "order_by" 
					size="6"
					onchange = 'this.form.submit()'
			>				
				<!-- <option selected> Ordina per... </option>														-->
				<option value = "data" 				<?php selected( $order_by,'data'); ?>			> Data </option> 
				<option value = "voto_prezzo" 		<?php selected( $order_by,'voto_prezzo'); ?>	> Prezzo </option> 
				<option value = "voto_servizio" 	<?php selected( $order_by,'voto_servizio'); ?>	> Servizio </option> 
				
				<option value = "voto_qualita" > 													Qualit&agrave; </option> 
				<option value = "voto_puntualita" > 												Puntualit&agrave; </option> 
				<option value = "voto_affidabilita" > 												Affidabilit&agrave; </option> 
			</select>			
		</div>
			
		<div>
			<label for="asc_desc"> &nbsp; ASC or DESC &nbsp; </label>		
			<select name = "asc_desc" id = "asc_desc"  
					size="2"
					onchange = 'this.form.submit()'
			>				
				<!-- <option selected> ASC o DESC...</option> -->
				<option value = "ASC" <?php selected( $asc_desc,'ASC'); ?>> ASC </option> 									
				<option value = "DESC" <?php selected( $asc_desc,'DESC'); ?>> DESC </option> 												
			</select>			
		</div>			
<!--		
		<div> 
			<label for="author_id"> &nbsp; AUTHOR ID &nbsp;	</label>					
			<select name = "author_id" id = "author_id" 
					size = "3"
					onchange = 'this.form.submit()'
			>				
				<option value = "0" 	<?php selected( $author_id,0); ?>	> 0 tutti gli autori di Review </option> 									
				<option value = "1"		<?php selected( $author_id,1); ?>	> 1 admin </option> 									
				<option value = "10" 	<?php selected( $author_id,10); ?>	> 10 andrea </option> 									
			</select>			
		</div>
-->
		<div>
			<label for="author_type"> &nbsp; Tipologia AUTORE Review &nbsp; </label>					
			<select name = "author_type" id = "author_type" 					
					size = "6"
					onchange = 'this.form.submit()'
			>	
				<option value = "tutti" 					<?php selected( $author_type,'tutti') ?>					  	> tutti </option> 									
<!--				<option value = "solo_amici" 				<?php selected( $author_type,'solo_amici') ?>			  		> tutti gli AMICI </option>			-->
<!--				<option value = "amici_fornitori" 			<?php selected( $author_type,'amici_fornitori') ?>			  	> AMICI fornitori </option> 				-->									
<!--				<option value = "amici_alberghi_ristoranti"	<?php selected( $author_type,'amici_alberghi_ristoranti'); ?>	> AMICI Alberghi/Ristoranti </option> 	-->												
				<option value = "Fornitore" 				<?php selected( $author_type,'Fornitore') ?>				 	> Fornitore </option> 									
				<option value = "Albergo/Ristorante"		<?php selected( $author_type,'Albergo/Ristorante'); ?>		  	> Albergo/Ristorante </option> 													

			</select>			
		</div>

		
<!--		
		<div>
			<label for="recipient_type"> &nbsp; Tipologia DESTINATARIO Review &nbsp; </label>					
			<select name = "recipient_type" id = "recipient_type" 					
					size = "3"
					onchange = 'this.form.submit()'
			>	
				<option value = "tutti" 				<?php selected( $recipient_type,'tutti') ?>					> tutti </option> 									
				<option value = "fornitori" 			<?php selected( $recipient_type,'fornitori') ?>				> fornitori </option> 									
				<option value = "alberghi/ristoranti"	<?php selected( $recipient_type,'alberghi/ristoranti'); ?>	> alberghi/ristoranti </option> 													
			</select>			
		</div>
-->		


		<!--		
		<div id="review-filter-submit">								
			<input type="submit" name="review-filter-submit" id="review-filter-submit" value="<?php _e( 'Filtra', 'reviews' ); ?>" />
		</div>			
		-->					
	</div>		
	 
	<!-- DO ACTION After - Search Form -->
	<?php do_action( 'after_review_filter_form' ) ?>	
				
	<?php //wp_nonce_field( 'bp_review_filter_review' ); ?>	 <!-- [WPNONCE] -->
</form>		

<?php
	
	//----------------------------------------------------------------------------------------------------------------------------------------------			
	if(		
			$_SERVER['REQUEST_METHOD']=='POST' 
		|| 	isset($_POST['order_by']) 
		|| 	isset($_POST['asc_desc']) 
		|| 	isset($_POST['author_id'])
		|| 	isset($_POST['author_type'])
	)
	//---------------------------------------------
	
	{		
		//Recupera...
		$order_by 	 = stripslashes($_POST['order_by']);		//striptags  
		$asc_desc 	 = stripslashes($_POST['asc_desc']);
		$author_id	 = stripslashes($_POST['author_id']);		
		$author_type = stripslashes($_POST['author_type']);		

		//---------------------------------------------------------------------------------------------------
		// DEBUG		
		//---------------------------------------------------------------------------------------------------
		if(DEBUG) 
		{
			echo '<br/>'.'<br/>';
			echo 'FORM inviato....';
			echo '<br/>'.'<br/>';
			
			echo 'POST order_by:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $order_by; 
			echo '<br/>'.'<br/>';			
			echo 'POST asc_desc:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $asc_desc; 
			echo '<br/>'.'<br/>';			
			echo 'author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
			echo '<br/>'.'<br/>';	

		}
		//---------------------------------------------------------------------------------------------------		
		
		$query_args = array
		(
				'post_status'		=> 'publish'
			,	'post_type'			=> 'review'				//post_type: 'review'

//			,	'meta_query'		=> array()				//META_QUERY!
//			,	'meta_key'			=> $order_by			//Meta_KEY!
//			,	'orderby'			=> $order_by
			
			, 	'order'				=> $asc_desc			//MET 2		
		);
	
		//è stato specificato....ASC o DESC		
		if( $asc_desc =='ASC' ||  $asc_desc =='DESC' ) 
		{
			//$query_args['order'] = $asc_desc;			// MET 1 - non va! --> vd MET 2	
		}
					
		// è stato specificato un AUTORE id
		if ( $author_id )
		{					
			$query_args['author'] = $author_id; //$query_args['author'] = (array)$author_id;
			
			if(DEBUG) 
			{
				echo 'AUTORE scelto!'.'--> author_id:	'.  "&nbsp" . "&nbsp" . "&nbsp" . "&nbsp" . $author_id; 
				echo '<br/>'.'<br/>';	
			}
		}

		//è stato specificato....
		if( $order_by =='voto_prezzo' || $order_by =='voto_servizio'  ) 
		{		
			//													//FUNZIONA!!!
			$query_args['meta_key'] = $order_by;			
			$query_args['orderby'] = 'meta_value_num';								
			//$query_args['orderby'] = 'meta_value';			
		}
		else //ORDINA PER DATA
		{
			//$query_args['orderby'] = 'date';								
		}

		//lancia la QUERY!
		$loop = new WP_Query($query_args);						
	} 
	else 
	{		
			
		$loop = new WP_Query('post_type=review');				//post_type='review'
	
	} //end IF Request - form inviato

			
?>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
	
<!-- IF -->					
<?php if ( $loop->have_posts() ) : ?>	
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-above' ); ?>
	<!------------------------------------------>
	
	<!-- WHILE -->
	<?php while($loop->have_posts()): $loop->the_post();?>		
	
			
		<?php
		
		if($author_type == 'Fornitore' || $author_type == 'Albergo/Ristorante' )
		{			
			$tipo_profilo = xprofile_get_field_data( "Tipo profilo" , $post->post_author);			
			
			if($tipo_profilo != $author_type)  
			{				
				continue;						
			}
			else 
			{
				echo $tipo_profilo;
			}
			
		}
		?>		
		
		<!-- DO-ACTION -->
		<?php do_action( 'bp_before_blog_post' ) ?>			

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
		<!-- <article id="post-<?php //the_ID(); ?>" <?php //post_class(); ?>>
		
			<!-- domain SBAGLIATO! -- correggere! ES: __( '%1$s <span>in %2$s</span>', 'buddypress' ) ----- sost buddypress con revies ?!-->

			<div class="author-box">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
				<p><?php printf( _x( 'by %s', 'Post written by...', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
			</div>
			

			<div class="post-content">
				<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

				<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>

				<!-- ENTRY -->
				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				</div>
			
				<!-- -->
				<p class="postmetadata">
					<?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> 
					<span class="comments">
						<?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?>
					</span>
				</p>							
				
				<!-- REVIEW-META-->
				<div class = "review-meta" >
					<h4> Prezzo: 				<?php echo get_post_meta($post->ID, 'voto_prezzo',true); 		?></h4>
					<h4> Serivzio: 				<?php echo get_post_meta($post->ID, 'voto_servizio',true); 		?></h4>
					<h4> Qualit&agrave;: 		<?php echo get_post_meta($post->ID, 'voto_qualita',true); 		?></h4>
					<h4> Puntualit&agrave;:		<?php echo get_post_meta($post->ID, 'voto_puntualita',true); 	?></h4>
					<h4> Affidabilit&agrave;: 	<?php echo get_post_meta($post->ID, 'voto_affidabilita',true);	?></h4>
				</div>
				
			</div>	<!-- chiude CONTENT -->							
		</div> <!-- chiude POST -->
		<!-- </article>-->
		
		<?php
			if (($wp_query->current_post + 1) < ($wp_query->post_count)) 
			{
				echo '<div class="post-item-divider"><hr/></div>';				
			}
		?>
		
		<!-- DO-ACTION -->
		<?php do_action( 'bp_after_blog_post' ) ?>

	<!--END WHILE -->					
	<?php endwhile; ?>
	
	<!------------------------------------------>
	<?php bp_dtheme_content_nav( 'nav-below' ); ?>
	<!------------------------------------------>	
	
<!-- ELSE -->
<?php else : ?>
	
	<h2 class="center"><?php //_e( 'Not Found', 'buddypress' ) ?></h2>
	<?php //get_search_form() ?>
	
<!-- End IF -->
<?php endif; ?>
			
								<!-- Reset POSTDATA	-->													<!-- IMPORTANTE ! -->
								<?php wp_reset_postdata() ?>																	
								
								<!-- Reset QUERY	-->													<!-- IMPORTANTE ! -->
								<?php //wp_reset_query(); ?>									<!-- solo quando si usa 'query_posts()'-->
								
</div><!-- .page -->

<!-- DO ACTION -->
<?php do_action( 'bp_after_blog_page' ) ?>

</div><!-- .padder -->
</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>