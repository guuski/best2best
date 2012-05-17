<?php get_header() ?>

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

				
				
	
				<!-- MESSAGGIO  Opt 1 -->
				<h4><?php //_e( 'Screen One', 'referrals' ) ?></h4>
				
				<br />
				
				<!-- MESSAGGIO  Opt 2 -->
				<h5><?php _e( 'Chiedi un Referral a '.bp_get_displayed_user_fullname() , 'referrals' ) ?></h5>
				
				<br />

<!-------------------------------------------------------------------------------------------------------->				

							
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM - met 2	- no inclusione ESTERNA
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<form action = "<?php bp_ref_post_form_action() ?> " method="post" id="referral-form" class="standard-form"> <!--- referral-FORM-->
	
		<!-- DO ACTION -->
	

		<!-- Avatar -->
		<div id="review-writer-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
			</a>
		</div>				
		
		<!-- MESSAGGIO  Opt 3 -->
		<h5><?php //_e( 'Chiedi un Referral a '.bp_get_displayed_user_fullname() , 'referrals' ) ?></h5>


<?php
//SPOSTA nelle OPZIONDI del Plugin nel Back End
$contenuto = 'Gentile Cliente, avendo ....';			//
?>				
			
			
		<!--  -->
		<div id="new-referral-content">

			<div id="new-referral-textarea">			
				<label for="referral-content">  <?php //_e( 'Testo', 'referrals' ); ?>  </label>			
				<textarea name="referral-content" id="referral-content" cols="50" rows="10"><?php 
				echo $contenuto
				?></textarea>
			</div>
		
			<br />
		
			<!-- bottone INVIA -->	
			<div>
				<br />
				<div id="new-referral-submit">								
					<input type="submit" name="referral-submit" id="referral-submit" value="<?php _e( 'Invia', 'referrals' ); ?>" />
				</div>
				<br />
			</div>		
					
		</div> <!-- chiude NEW REFERRAL -->
				
        <!-- DO ACTION -->														
		
		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'bp_ref_new_referral' ); ?>		
		
</form>
<!--------------------------------------------------------------------------fine FORM -------------------------------------------------------------------------------------->						



</div><!-- #item-body -->
</div> <!--#main-column -->

<?php locate_template( array( 'sidebar.php' ), true ) ?>	
</div><!-- .padder -->
</div><!-- #content -->
</div>

<!-- FOOTER -->	
<?php get_footer() ?>