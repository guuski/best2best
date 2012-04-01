<?php
/*
	<?php if ( is_user_logged_in() ) : ?>
		<?php include( apply_filters( 'bpgr_post_template', BP_GROUP_REVIEWS_DIR . 'templates/post.php' ) ) ?>
	<?php endif ?>
*/
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
	
	member-header.php
		
	bp_review_form_action()

	[PHP FILE]
		
		review-loop
		...
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	get_header()	  
	get_footer()
	
	locate_template()
	...
	
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_get_options_nav()
	bp_get_displayed_user_nav()
	bp_displayed_user_id()
	....
	
-----------------------------------------
global $bp
-----------------------------------------
*/
?>

<!-- HEADER -->
<?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder">

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
	
<div id="item-body">											<!-- ma è ripetuto?!-->

	<?php do_action( 'bp_before_member_body' ); ?>
	
<div id="item-body">											<!-- ma è ripetuto?!-->
	<div class="item-list-tabs no-ajax" id="subnav">
		<ul>
			<!-- -->
			<?php bp_get_options_nav() ?>
		</ul>
	</div>


	
	
	
	
	
	
	
<!-- MESSAGGIO -->
<h4><?php _e( 'Scrivi una review per '.bp_get_displayed_user_fullname() , 'reviews' ) ?></h4>

							
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM - met 2	- no inclusione ESTERNA
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<form action = "<?php bp_review_form_action() ?> " method="post" id="reviews-form" class="standard-form">

		<?php do_action( 'bp_before_review_post_form' ); ?>

		<div id="review-writer-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
			</a>
		</div>

		<h5> <?php  //_e('Scrivi una nuova review!','reviews');?> </h5>

		<div id="new-review-content">
			
			<div id="new-review-textarea">			
				<textarea name="review-content" id="review-content" cols="50" rows="10"></textarea>
			</div>
			
			<div id="new-review-options">
				<div id="new-review-submit">								
					<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Post', 'reviews' ); ?>" />
				</div>
			</div>
		
			<!-- aqui? -->
			<!-- sezione RATING -->
			
			<!-- fine sezione RATING -->
		</div>
		  
		<br/>  <br/> <br/>  <br/>
		
		<!-- aqui? -->  
		<!--------------------------------------------- sezione RATING ------------------------------------->
		<div id="new-review-rating">			
		
			<?php 
				$prezzo = 0;
				$servizio = 0;
			?>
			
			<p>
				&nbsp; Prezzo &nbsp;				
				<select name = "prezzo" id = "prezzo" >
					<option selected> 0 </option>
					<option value = "1"	<?php selected( $prezzo,1); ?>> 1 </option> 
					<option value = "2"	<?php selected( $prezzo,2); ?>> 2 </option> 
					<option value = "3"	<?php selected( $prezzo,3); ?>> 3 </option> 
					<option value = "4"	<?php selected( $prezzo,4); ?>> 4 </option> 
					<option value = "5"	<?php selected( $prezzo,5); ?>> 5 </option> 											
				</select>
				
			</p>					

			<p> 
				&nbsp;
				Servizio
				&nbsp;
				<select name = "servizio" id = "servizio" >
					<option selected> seleziona&nbsp;&nbsp;&nbsp;</option>
					<option value = "1"	<?php selected( $servizio,1); ?>> 1 </option> 
					<option value = "2"	<?php selected( $servizio,2); ?>> 2 </option> 											
				</select>
				
			</p>			
						
		  
		</div>	<!-- fine sezione RATING -->


<!--		  

Qualità

Puntualità
Affidabilità
Innovazione
-->		  
		  
		  	
		  
		<!-- DO ACTION -->
		<?php do_action( 'bp_after_review_post_form' ); ?>								

		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'bp_review_new_review' ) ?>		
		
	</form>
<!-- --------------------fine FORM ------------------------------------------------------------------------------------------------------------------>						


<br/>
<br/>
				
	
<!-- IF -->							<!-- va bene sta CONDIZIONE?! per ora sì ...fa cagare!-->		

<?php if ( $lista_reviewers = bp_review_get_reviewers_list_for_user( bp_displayed_user_id() ) ) : ?>

	
	
	<!----------------------------------(Temporanea!) - NUMERO TOTALE delle review per l'utente -------------------------------------------------------->	
	<h4>
		<?php //_e( 'TOTALE: ', 'reviews' ) ?>
		<?php //_e( 'Numero TOTALE Reviews: ' . bp_review_total_review_count_for_user(bp_displayed_user_id()) , 'reviews' ) ?>
		<?php //echo bp_review_total_review_count_for_user(bp_displayed_user_id());?>	
	</h4>

	
	<!-------------------------------------- LISTA 2 - Reviews scritte per l'utente del profilo -------------------------------------------------------->
	
	<br/><br/>
	
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
				<?php the_content('Leggi il resto della Review');?>				
			</div>			
			
			<!--CUSTOM FIELDS-->
			<div>								
				<!-- NB: 'true' perchè.... -->
			
				<?php //echo get_post_meta( $post->ID, 'bp_review_recipient_id', true ); ?>		<!-- vabbuò non mi serve!-->
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
		<h5><?php _e( 'nessuna Review per quest\'utente!', 'reviews' ) ?></h5>
		
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->


	<!----------------------------------(Temporanea!) - LISTA 1 Lista degli utenti che hanno fatto una reviuw su questo PROFILO ------------------------------------------>	
	
	<br/><br/>
	
	<!-- MESSAGGIO  -->
	<h4><?php _e( 'Lista utenti che hanno scritto una Review per l utente', 'reviews' ) ?></h4>
	
	<table id="lista_reviewers">
		<?php foreach ( $lista_reviewers as $user_id ) : ?>
		<tr>
			<td width="1%">
				<?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 25, 'height' => 25 ) ) ?>
			</td>
			<td>&nbsp; 
				<?php echo bp_core_get_userlink( $user_id ) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>			
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->
	
	
<!-- ELSE -->				
<?php else: ?>	
	
	<!-- MESSAGGIO -->
	<h5><?php _e( 'L\' utente non ha ricevuto ancora nessuna Reviews', 'reviews' ) ?></h5>

<?php endif; ?>

</div><!-- #item-body -->
</div><!-- .padder -->
</div><!-- #content -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

<!-- FOOTER -->	
<?php get_footer() ?>