<?php
//--------------------------------------------------- LISTA 4 -Referrals da MODERARE ------------------------------------------------------------------------------->		
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
	
	<!--------------------------------------------------- LISTA 4 -Referrals da MODERARE ------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php _e( 'Le Referrals da MODERARE ', 'referrals' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'pending'																				//----------pending
			
			,	'post_type'			=> 'referral'				//'referral'
			,	'meta_query'		=> array()				//META_QUERY!
		);

		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_referral_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
	
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
		
			<div class="title">				
				<?php $authorlogin = get_the_author_meta('user_login', get_post_meta( $post->ID, 'bp_referral_recipient_id', true ));?>						
				<br />			
				<?php  the_title('<h4 class="pagetitle"> <a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '"rel="bookmark">','</a></h4>');?>
								
			</div>	
<!--
			<small style = "float: right;">
				<strong>
					<?php  _e('inviato a: ');?> 
					<a href="<?php echo bp_core_get_user_domain($authorlogin).$authorlogin?>">
					<?php the_author_meta('user_nicename', 	get_post_meta( $post->ID, 'bp_referral_recipient_id', true )) ?> </a>
				</strong>
			</small>
-->			
						

	<?php 
			$voto_complessivo = $_POST['voto-complessivo'] 	or 0;			
	?>

		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<!--  2 FORM -
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	<!--- referral-FORM ACCETTA-->
	<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> 
		
		<br/>
		
		<!-- TIPOLOGIA Rapporto Commerciale -->
		<div>
			<label for = "referral-tipologia-rapporto"> <?php _e( 'Tipologia Rapporto Commerciale ', 'referrals' ); ?></label>	
			<fieldset name = "referral-tipologia-rapporto" id = "referral-tipologia-rapporto">	  	  
				<label for = "una tantum"	> <input type="radio" name="tipologia" id="una tantum" value="una tantum"/> <?php _e( 'Una Tantum ', 'referrals' ); ?> </label> 
				<label for = "continuativo" > <input type="radio" name="tipologia" id="continuativo" value="continuativo"/> <?php _e( 'Continuativo', 'referrals' ); ?>  </label>
			</fieldset>			
		</div>
		
		<br/>
	
		<!-- ANZIANITA Rapporto Commerciale -->
		<div>
			<label for = "referral-anzianita-rapporto"> <?php _e( 'Anzianita Rapporto Commerciale ', 'referrals' ); ?></label>	
			<fieldset name = "referral-anzianita-rapporto" id = "referral-anzianita-rapporto">	  	  
				<label for = "nuovo"> 		 <input type="radio" name="anzianita" id="nuovo" 	value="nuovo"	/> <?php _e( 'Nuovo', 'referrals' ); ?> </label> 
				<label for = "> 1 anno" > 	 <input type="radio" name="anzianita" id="> 1 anno" value="> 1 anno"/> <?php _e( '> 1 anno', 'referrals' ); ?>  </label>
				<label for = "> 5 anni" >	 <input type="radio" name="anzianita" id="> 5 anni" value="> 5 anni"/> <?php _e( '> 5 anni' , 'referrals' ); ?>  </label>
			</fieldset>			
		</div>
		
		<br/>
		
		<!-- Lo raccomanderesti? - Utente Consigliato-->
		<div>	
			<label for = "utente_consigliato"> <?php _e( 'Lo raccomanderesti?', 'referrals' ); ?></label>	
			<fieldset name = "utente_consigliato" id = "utente_consigliato">	  	  
				<label for = "si"> <input type="radio" name="consigliato" id="si" value="si"/><?php _e( 'Si', 'referrals' ); ?>   </label> 	 	
				<label for = "no"> <input type="radio" name="consigliato" id="no" value="no"/>  <?php _e( 'No', 'referrals' ); ?> </label> 	 	  
				<label for = "nonso"> <input type="radio" name="consigliato" id="nonso" value="nonso"/> <?php _e( 'Non so', 'referrals' ); ?> </label> 
			</fieldset>			
		</div>
	
	
		
		<br/>		
		
		<!----------------sezione RATING ------------------------------------->
		<div>		
		
			<!-- voto-complessivo-->
			<div class="rating-container"><span class="rating-title"><?php _e( 'Giudizio ', 'referrals' ); ?></span> 
				<ul id="voto-complessivo" class='star-rating'>	
					<li class='current-rating' style="width: <?php echo 25*$voto_complessivo;?>px"></li>			
					<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
					<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
					<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
					<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
					<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>				
				</ul><input type="hidden" name="prezzo" value="<?php echo $voto_complessivo?>" />
			</div>		
		</div>			
		
		<br/> <br/>
		
		<div>			
			<!-- bottone ACCETTA -->	
			<div id="referral-moderation-submit">								
				<input type="submit" name="accetta-referral" id="accetta-referral" value="<?php _e( 'Accetta', 'referrals' ); ?>" />
				<!-- <input type="hidden" name="id-post" id="id-post" value="<?php $post->ID ?>" />-->
				<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
			</div>
			<br />
		</div>		
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'accetta-referral' ); ?>				
	</form>		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<!--- referral-FORM RIFIUTA-->
	<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> 

		<div>			
			<!-- bottone RIFIUTA -->	
			<div id="referral-moderation-submit">								
				<input type="submit" name="rifiuta-referral" id="rifiuta-referral" value="<?php _e( 'Rifiuta', 'referrals' ); ?>" />
				<!-- <input type="hidden" name="id-post" id="id-post" value="<?php $post->ID ?>" />-->
				<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
				
			</div>
			<br />
		</div>		
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'rifiuta-referral' ); ?>				
	</form>			
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
			
	<hr />	
			
	<?php endwhile; ?>
	
<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h5><?php _e( ' no referral ', 'referrals' ) ?></h5>												
		
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