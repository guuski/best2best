<?php
//--------------------------------------------------------------- SCREEN 2 --> Scrivi Review-----------------------------------------------------------------------------------------
get_header() ?>

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

	
<!-- MESSAGGIO  Opt 1 -->
<h5><?php _e( 'Scrivi una review per '.bp_get_displayed_user_fullname() , 'reviews' ) ?></h5>

							
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM - met 2	- no inclusione ESTERNA
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<form action = "<?php bp_reviews_post_form_action() ?> " method="post" id="review-form" class="standard-form"> <!--- REVIEW-FORM-->
	
		<!-- DO ACTION -->
		<?php do_action( 'bp_before_review_post_form' ); ?>

		<!-- Avatar -->
		<div id="review-writer-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
			</a>
		</div>				
		
		<?php 
				$prezzo 			= $_POST['prezzo'] 			or 0;
				$servizio 			= $_POST['servizio'] 		or 0;
				$qualita 			= $_POST['qualita'] 		or 0;
				$puntualita 		= $_POST['puntualita'] 		or 0;
				$affidabilita 		= $_POST['affidabilita'] 	or 0;
				$titolo 			= $_POST['review-title'] 	or '';
				$contenuto 			= $_POST['review-content']	or '';

				$giudizio_review    = $_POST['giudizio_review']	or ''; 
		?>
				
		
		<!-- Review -->
		<div id="new-review-content">
<!--		
			<div id="radio-toolbar">
				<fieldset name = "review-giudizio" id = "review-giudizio">
				  <label> <input type="radio" name="giudizio_review" id="positivo" value="positivo" /> Positiva </label>				  
				  <label> <input type="radio" name="giudizio_review" id="neutro"   value="neutro"/>    Neutra   </label>				  
				  <label> <input type="radio" name="giudizio_review" id="negativo" value="negativo"/>  Negativa </label>
				</fieldset>			
			</div>	
-->			
			<div id="radio-toolbar">			<!-- -->
				<!-- <label for = "review-giudizio"> Giudizio Complessivo Review </label>					-->
				<!-- <fieldset name = "review-giudizio" id = "review-giudizio">-->
<!--				
				  <input type="radio" name="giudizio_review" id="positivo" value="positivo" checked = "<?php checked( $giudizio_review,'positivo') ?>"/> <label for = "positivo" > Positiva </label>				  
				  <input type="radio" name="giudizio_review" id="neutro"   value="neutro"  checked = "<?php checked( $giudizio_review,'neutro') ?>"/>     <label for = "neutro" > Neutra   </label>				  
				  <input type="radio" name="giudizio_review" id="negativo" value="negativo" checked = "<?php checked( $giudizio_review,'negativo') ?>"/>  <label for = "negativo"> Negativa </label>								 
-->				  
  				  <input type="radio" name="giudizio_review" id="positivo" value="positivo"/> <label style="color:green;" for = "positivo" > <?php _e( 'Positiva', 'reviews' ); ?>  </label>				  
				  <input type="radio" name="giudizio_review" id="neutro"   value="neutro"  />     <label style="color:orange;"  for = "neutro" > <?php _e( 'Neutra', 'reviews' ); ?>    </label>				  
				  <input type="radio" name="giudizio_review" id="negativo" value="negativo"/>  <label style="color:red;" for = "negativo"><?php _e( 'Negativa', 'reviews' ); ?>   </label>								 
				<!-- </fieldset>			-->
			</div>	
			
			<!-- <option value = "tutti" 	<?php selected( $author_type,'tutti') ?>> tutti </option> 	-->
			
			<br/>			
			
			<div id="new-review-textarea">	
				<label for="review-title"> <?php _e( 'Titolo Review', 'reviews' ); ?>  </label>			
				<textarea name="review-title" id="review-title" cols="2" rows="2"><?php 
				echo $titolo
				?></textarea>
				<span id="nameInfo"> <?php _e( 'Inserisci un titolo...', 'reviews' ); ?>  </span>
			</div>
			
			<br/>
			
			<div id="new-review-textarea">			
				<label for="review-content">  <?php _e( 'Testo', 'reviews' ); ?>  </label>			
				<textarea name="review-content" id="review-content" cols="50" rows="10"><?php 
				echo $contenuto
				?></textarea>
			</div>

<br/>	

<div id="new-review-tipologia">		
	<label for = "review-tipologia-rapporto"> <?php _e( 'Tipologia Rapporto Commerciale ', 'reviews' ); ?></label>	
	<fieldset name = "review-tipologia-rapporto" id = "review-tipologia-rapporto">	  	  
		<label for = "una tantum"	> <input type="radio" name="tipologia" id="una tantum" value="una tantum"/> <?php _e( 'Una Tantum ', 'reviews' ); ?> </label> 
		<label for = "continuativo" > <input type="radio" name="tipologia" id="continuativo" value="continuativo"/> <?php _e( 'Continuativo', 'reviews' ); ?>  </label>
	</fieldset>			
</div>

<br/>	

<!-- <div id="new-review-input">		-->
	<label for = "datepicker"> <?php _e( 'Data Inizio Rapporto Comm. ', 'reviews' ); ?><input type="text" name ="datepicker" id="datepicker" maxlength="12" size="20"> </label>		
	<!-- </div>-->
<!-- </div>-->

<br/>	
<br/>	

<div id="new-review-consigliato">		
	<label for = "utente_consigliato"> <?php _e( 'Lo raccomanderesti?', 'reviews' ); ?></label>	
	<fieldset name = "utente_consigliato" id = "utente_consigliato">	  	  
		<label for = "si"> <input type="radio" name="consigliato" id="si" value="si"/><?php _e( 'Si', 'reviews' ); ?>   </label> 	 	
		<label for = "no"> <input type="radio" name="consigliato" id="no" value="no"/>  <?php _e( 'No', 'reviews' ); ?> </label> 	 	  
		<label for = "nonso"> <input type="radio" name="consigliato" id="nonso" value="nonso"/> <?php _e( 'Non so', 'reviews' ); ?> </label> 
	</fieldset>			
</div>
	
<!--------------------------------------------- sezione RATING ------------------------------------->
		<div id="new-review-rating">			
					
			<div class="rating-container"><span class="rating-title"><?php _e( 'Prezzo', 'reviews' ); ?></span> 
				<ul id="prezzo" class='star-rating'>	
					<li class='current-rating' style="width: <?php echo 25*$prezzo;?>px"></li>			
					<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
					<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
					<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
					<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
					<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>				
				</ul><input type="hidden" name="prezzo" value="<?php echo $prezzo?>" />
			</div>		
			
			<div class="rating-container"><span class="rating-title"><?php _e( 'Servizio', 'reviews' ); ?></span> <ul id="servizio" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$servizio;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="servizio" value="<?php echo $servizio?>" />
			</div>	
			<div class="rating-container"><span class="rating-title"><?php _e( 'Qualit&agrave;', 'reviews' ); ?></span> <ul id="qualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$qualita;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="qualita" value="<?php echo $qualita?>" />
			</div>		
			<div class="rating-container"><span class="rating-title"><?php _e( 'Puntualit&agrave;', 'reviews' ); ?></span> <ul id="puntualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$puntualita;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="puntualita" value="<?php echo $puntualita?>" />
			</div>	
			<div class="rating-container"><span class="rating-title"> <?php _e( 'Affidabilit&agrave;', 'reviews' ); ?></span> <ul id="affidabilita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$affidabilita;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="affidabilita" value="<?php echo $affidabilita?>" />
			</div>		
			<!-- <div id='current-rating-result'></div>  used to show "success" message after vote -->
					  
		</div>	<!-- fine sezione RATING -->			

<br />
		
<div id="new-review-disclaimer">		
	<label for = "disclaimer"> <?php _e( 'Disclaimer, Termini e Condizioni', 'reviews' ); ?></label>	
	<fieldset name = "disclaimer" id = "disclaimer">	  
		<label for = "disclaimer"> <?php _e( 'Accetto', 'reviews' ); ?></label> <input type="checkbox" name="disclaimer" value="si"/>	  
	</fieldset>			
</div>

<br />
		<!-- bottone INVIA -->	
		<div id="new-review-options">
			<br />
			<div id="new-review-submit">								
				<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Invia', 'reviews' ); ?>" />
				<!-- <input type="submit" hidden="hidden" name="review-submit" id="review-submit" value="<?php _e( 'Invia', 'reviews' ); ?>" />-->
				<!-- <input type="button" name="form_button" id="form_button" onclick="form_submit()" value="<?php _e( 'Invia', 'reviews' ); ?>" />-->
			</div>
			<br />
		</div>		
		
			
</div> <!-- chiude NEW REVIEW CONTENT -->
		  					  	  		  		 
		<!-- DO ACTION -->
		<?php do_action( 'bp_after_review_post_form' ); ?>								

		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'bp_review_new_review' ); ?>		
		
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