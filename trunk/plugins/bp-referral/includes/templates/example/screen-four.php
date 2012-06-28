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
	<h4><?php //_e( 'Le Referrals da MODERARE ', 'referrals' ) ?></h4>
		
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
							
			<!-------------------------------------- get AUTHOR --Autore Richiesta REFERRAL -------------------------------------->
			
			<?php $referral_requester_id	 = $post->post_author; ?>			
			<?php $referral_requester_name 	 = xprofile_get_field_data( "Nome" , $referral_requester_id); ?>					
								
			
			<div class="title">				
								
				<!-- MESSAGGIO  -->
				<h5><?php _e( 'REFERRAL per: ', 'referrals' ) ?>
				
					<span>
						<a href = "<?php echo bp_core_get_user_domain($referral_requester_id) ?>">	
							<?php echo $referral_requester_name; ?>
						</a>								
					</span>					
				</h5>
			</div>	
								
			<br/>									
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<?php 
			$voto_complessivo = $_POST['voto-complessivo'] 	or 0;			
	?>
	
	<?php	
/*
		msg = 
						_e(
						'Egr. '.bp_get_displayed_user_fullname().',		
						mi piacerebbe avere la tua referenza per il rapporto commerciale intercorso tra le nostre aziende										
						Cordiali saluti,						
						' . bp_loggedin_user_fullname()
						//;
						);
*/		
	
/*
		$referral_msg = 
						_e(
						'Egr. '.bp_get_displayed_user_fullname().',		
						mi piacerebbe avere la tua referenza per il rapporto commerciale intercorso tra le nostre aziende										
						Cordiali saluti,						
						' . bp_loggedin_user_fullname()
						//;
						);
*/		

//', 'referrals' ), bp_get_displayed_user_fullname(), bp_loggedin_user_fullname()
//', 'referrals' ), $receiver, $sender


$sender	  = bp_get_displayed_user_fullname();
$receiver = bp_loggedin_user_fullname();

$message = sprintf( __(
		
'Egr. %s,

mi piacerebbe avere la tua referenza per il rapporto commerciale intercorso tra le nostre aziende

Cordiali saluti,
%s

', 'referrals' ), $receiver, $sender
);

	
	?>						
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
	<!--  2 FORM -
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	<!--- referral-FORM ACCETTA-->
	<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> 

		<br/>
		
		<!-- Referral MSG-->
		<div id="new-referral-message">						
			<p><?php 	
				
				//echo $msg;
				//echo $referral_msg;
				echo $message;

			?></p>
		</div>	
		
		<br/>
		
		<!-- TIPOLOGIA Rapporto Commerciale -->
		<div id="new-referral-tipologia">	
			<label for = "referral-tipologia-rapporto"> <?php _e( 'Tipologia Rapporto Commerciale ', 'referrals' ); ?></label>	
			<fieldset name = "referral-tipologia-rapporto" id = "referral-tipologia-rapporto">	  	  
				<label for = "una tantum"	> <input type="radio" name="tipologia" id="una tantum" value="una tantum"/> <?php _e( 'Una Tantum ', 'referrals' ); ?> </label> 
				<label for = "continuativo" > <input type="radio" name="tipologia" id="continuativo" value="continuativo"/> <?php _e( 'Continuativo', 'referrals' ); ?>  </label>
			</fieldset>			
		</div>
		
		<br/>
	
		<!-- ANZIANITA Rapporto Commerciale -->
		<div id="new-referral-anzianita">	
			<label for = "referral-anzianita-rapporto"> <?php _e( 'Anzianit&agrave Rapporto Commerciale ', 'referrals' ); ?></label>	
			<fieldset name = "referral-anzianita-rapporto" id = "referral-anzianita-rapporto">	  	  
				<label for = "nuovo"> 		 <input type="radio" name="anzianita" id="nuovo" 	value="nuovo"	/> <?php _e( 'Nuovo', 'referrals' ); ?> </label> 
				<label for = "> 1 anno" > 	 <input type="radio" name="anzianita" id="> 1 anno" value="> 1 anno"/> <?php _e( '> 1 anno', 'referrals' ); ?>  </label>
				<label for = "> 5 anni" >	 <input type="radio" name="anzianita" id="> 5 anni" value="> 5 anni"/> <?php _e( '> 5 anni' , 'referrals' ); ?>  </label>
			</fieldset>			
		</div>
		
		<br/>
		
		<!-- Lo raccomanderesti? - Utente Consigliato-->
		<div id="new-referral-consigliato">	
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
				</ul><input type="hidden" name="voto-complessivo" value="<?php echo $voto_complessivo?>" />
			</div>		
		</div>			
		
		<br/> 
			
		<!-- bottone ACCETTA -->	
		<div id="referral-moderation-submit">								
			<input type="submit" name="accetta-referral" id="accetta-referral" value="<?php _e( 'Accetta', 'referrals' ); ?>" />
			<!-- <input type="hidden" name="id-post" id="id-post" value="<?php $post->ID ?>" />-->
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />
		</div>					
										
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'accetta-referral' ); ?>				
	</form>		
	<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
	<br/> 	
	
	<!--- referral-FORM RIFIUTA-->
	<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> 

		<!-- bottone RIFIUTA -->	
		<div id="referral-moderation-submit">								
			<input type="submit" name="rifiuta-referral" id="rifiuta-referral" value="<?php _e( 'Rifiuta', 'referrals' ); ?>" />
			<!-- <input type="hidden" name="id-post" id="id-post" value="<?php $post->ID ?>" />-->
			<input type="hidden" name="id-post" id="id-post" value="<?php the_ID() ?>" />				
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
		<h6><?php _e( ' nessun Referral da moderare', 'referrals' ) ?></h6>												
		
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