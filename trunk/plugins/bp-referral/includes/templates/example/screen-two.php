<?php
//------------------------------------------- SCREEN 2  - Chiedi REFERRAL ---------------------------------------------------------------------------------
?><?php get_header() ?>


	<div id="content">
		<div class="padder">

			<div id="item-header">
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
			</div>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>
			
			
<!------------------------------------------>			
<div id="sidebar-squeeze">										<!-- pezza per FRISCO -->
	<div id="main-column">
<!------------------------------------------>							

			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php bp_get_options_nav() ?>
					</ul>
				</div>
												
				<!-- MESSAGGIO -->
				<h6 style = "display: inline;">										
					<?php _e( 'Chiedi un Referral a', 'referrals' ) ?> 
					<h5 style = "display: inline;">										
						<strong> <?php _e( ''.bp_get_displayed_user_fullname(), 'referrals' ) ?> </strong>
					</h5>
				</h6>
								
				<br />				
				<br />
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

<!-- 

NOTA BENE: ci sono 2 redirect: 

	1) della funzione  'bp_ref_post_form_action()' 
	
	2) la funzione 'invia_richiesta_referral()' nel FILE 'bp-referral-actions.php'
	
-->

<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> <!--- referral-FORM-->
	
	<!-- DO ACTION -->																				<!-- missing! -->

	<!-- Avatar -->
	<div id="review-writer-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>				
	
	<?php
	//SPOSTA nelle OPZIONDI del Plugin nel Back End															//PLUGIN Options!
		$contenuto = 'Egr. '.bp_get_displayed_user_fullname().',
vi chiediamo di confermare la nostra relazione commerciale.

Cordiali saluti,';			//
	?>				
					
	<!--  -->
	<div id="new-referral-content">
		
		<!-- TextArea -->
		<div id="new-referral-textarea">			
			<label for="referral-content">  <?php //_e( 'Testo', 'referrals' ); ?>  </label>		
			<!-- READ ONLY -->	
			<textarea name="referral-content" id="referral-content" cols="50" rows="10"  readonly><?php 	
			echo $contenuto
			?></textarea>
		</div>
	
		<br />
	
		<!-- bottone INVIA -->	
		<div id="new-referral-submit">								
			<input type="submit" name="referral-submit" id="referral-submit" value="<?php _e( 'Invia', 'referrals' ); ?>" />
		</div>							
		
	</div> <!-- chiude NEW REFERRAL CONTENT?-->
			
	<!-- DO ACTION -->																				<!-- missing! -->											
	
	<!-- [WPNONCE] -->
	<?php wp_nonce_field( 'bp_ref_new_referral' ); ?>				
</form>
<!--------------------------------------------------------------------------fine FORM -------------------------------------------------------------------------------------->						

</div><!-- #item-body -->
</div><!-- .padder -->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						
</div><!-- #content -->
</div>
<!-- SIDEBAR --->
</div>
<!-- FOOTER -->	
<?php get_footer() ?>