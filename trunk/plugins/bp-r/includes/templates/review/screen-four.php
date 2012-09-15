<?php
//--------------------------------------------------- Review ANONIME da MODERARE ------------------------------------------------------------------------------->		
?>

<!-- HEADER -->
<?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="background: #EAEAEA;  padding: 19px 19px 0;">

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
	
<div id="item-body">									

	<?php do_action( 'bp_before_member_body' ); ?>
	
		<div class="item-list-tabs no-ajax" id="subnav">
			<ul>
				<!-- -->
				<?php bp_get_options_nav() ?>
			</ul>
		</div>
	
	<!--------------------------------------------------- Review ANONIME da MODERARE ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( 'Le Review ANONIME da MODERARE ', 'reviews' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'pending'																				//----------pending
			
			,	'post_type'			=> 'review'				//'review'
			,	'meta_query'		=> array()				//META_QUERY!
		);
/*
		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_referral_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
*/		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
	
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
							
			<!-------------------------------------- get AUTHOR --Autore -------------------------------------->
			
			<?php //$referral_requester_id	 = $post->post_author; ?>			
			<?php //$referral_requester_name 	 = xprofile_get_field_data( "Nome" , $referral_requester_id); ?>					
								
			
			<div class="title">				
								
				<!-- MESSAGGIO  -->
				<h5><?php //_e( 'REFERRAL per: ', 'referrals' ) ?>
				
					<span>
						<a href = "<?php //echo bp_core_get_user_domain($referral_requester_id) ?>">	
							<?php //echo $referral_requester_name; ?>
						</a>								
					</span>					
				</h5>
			</div>	
								
			<br/>									
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<?php 
			//$voto_complessivo = $_POST['voto-complessivo'] 	or 0;			
	?>
		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<!--  2 FORM -
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	<!--- review-FORM ACCETTA-->
	<form action = "<?php 
	
								bp_ref_post_form_action() 
	
	?> " method="post" id="review-form" class="standard-form"> 
				
		
	
		
		<!-- Lo raccomanderesti? - Utente Consigliato-->
		<!--
		<div id="new-referral-consigliato">	
			<label for = "utente_consigliato"> <?php _e( 'Lo raccomanderesti?', 'referrals' ); ?></label>	
			<fieldset name = "utente_consigliato" id = "utente_consigliato">	  	  
				<label for = "si"> <input type="radio" name="consigliato" id="si" value="si"/><?php _e( 'Si', 'referrals' ); ?>   </label> 	 	
				<label for = "no"> <input type="radio" name="consigliato" id="no" value="no"/>  <?php _e( 'No', 'referrals' ); ?> </label> 	 	  
				<label for = "nonso"> <input type="radio" name="consigliato" id="nonso" value="nonso"/> <?php _e( 'Non so', 'referrals' ); ?> </label> 
			</fieldset>			
		</div>
			-->
		<br/>		
	
			
		<!-- bottone ACCETTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="accetta-review" id="accetta-review" value="<?php _e( 'Accetta', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
		</div>					
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'accetta-review' ); ?>				
	</form>		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
	
	<!--- review-FORM RIFIUTA-->
	<form action = "<?php 
	
			bp_ref_post_form_action() 
			
			
			?> " method="post" id="review-form" class="standard-form"> 

		<!-- bottone RIFIUTA -->	
		<div id="review-moderation-submit">								
			<input type="submit" name="rifiuta-review" id="rifiuta-review" value="<?php _e( 'Rifiuta', 'reviews' ); ?>" />			
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />				
		</div>					
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'rifiuta-review' ); ?>				
	</form>			
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
			
	<hr />	
			
	<?php endwhile; ?>
	
<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php _e( ' nessuna Review Anonima da moderare', 'reviews' ) ?></h6>												
		
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->

</div><!-- #item-body -->
</div><!-- .padder -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

</div><!-- #content -->
</div>
</div>

<!-- FOOTER -->	
<?php get_footer() ?>