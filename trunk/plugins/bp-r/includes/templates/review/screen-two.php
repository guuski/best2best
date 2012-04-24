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

	<form action = "<?php bp_reviews_post_form_action() ?> " method="post" id="review-form" class="standard-form">
	
		<!-- DO ACTION -->
		<?php do_action( 'bp_before_review_post_form' ); ?>

		<!-- Avatar -->
		<div id="review-writer-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
			</a>
		</div>		
		
		<?php 
				$prezzo 		= $_POST['prezzo'] 			or 0;
				$servizio 		= $_POST['servizio'] 		or 0;
				$qualita 		= $_POST['qualita'] 		or 0;
				$puntualita 	= $_POST['puntualita'] 		or 0;
				$affidabilita 	= $_POST['affidabilita'] 	or 0;
				$titolo 		= $_POST['review-title'] 	or '';
				$contenuto 		= $_POST['review-content']	or '';
		?>
		
		<!-- Review -->
		<div id="new-review-content">

			<div id="new-review-textarea">	
				<label for="review-title"> Titolo Review </label>			
				<textarea name="review-title" id="review-title" cols="2" rows="2"><?php 
				echo $titolo
				?></textarea>
			</div>
			
			<br/>
			
			<div id="new-review-textarea">			
				<label for="review-content"> Testo </label>			
				<textarea name="review-content" id="review-content" cols="50" rows="10"><?php 
				echo $contenuto
				?></textarea>
			</div>

<br/>	

<div id="new-review-fieldset">		
	<label for = "review-giudizio"> Giudizio Complessivo Review </label>	
	<fieldset name = "review-giudizio" id = "review-giudizio">
	  <!-- <legend> Voto Complessivo Review </legend>				  -->				  	
	  Positiva <input type="radio" name="giudizio_review" value="positivo"/>
	  Neutra   <input type="radio" name="giudizio_review" value="neutro"  />
	  Negativa <input type="radio" name="giudizio_review" value="negativo"/>
	</fieldset>			
</div>

<br/>	

<div id="new-review-fieldset">		
	<label for = "review-tipologia-rapporto"> Tipologia Rapporto Commerciale </label>	
	<fieldset name = "review-tipologia-rapporto" id = "review-tipologia-rapporto">	  	  
	  Una Tantum   <input type="radio" name="tipologia" value="una tantum"/>
	  Continuativo <input type="radio" name="tipologia" value="continuativo"/>
	</fieldset>			
</div>

<br/>	

<!-- <div id="new-review-input">		-->
	<label for = "datepicker"> Data Inizio Rapporto Commerciale</label>	
	<!-- <div name = "datepicker" id = "datepicker">-->	
		<p>Data: <input type="text" name ="datepicker" id="datepicker" maxlength="10" size="10" value="<?php get_post_meta($post->ID, 'data_rapporto', TRUE) ?>">
		</p>
	<!-- </div>-->
<!-- </div>-->

<br/>	

<div id="new-review-fieldset">		
	<label for = "utente_consigliato"> Lo raccomanderesti?</label>	
	<fieldset name = "utente_consigliato" id = "utente_consigliato">	  	  
	  Si<input type="radio" name="consigliato" value="si"/>
	  No<input type="radio" name="consigliato" value="no"/>
	  Non so<input type="radio" name="consigliato" value="nonso"/>
	</fieldset>			
</div>

<br/>	

<div id="new-review-fieldset">		
	<label for = "disclaimer"> Disclaimer, Termini e Condizioni</label>	
	<fieldset name = "disclaimer" id = "disclaimer">	  	  
	  Accetto  <input type="checkbox" name="disclaimer" value="si"/>	  
	</fieldset>			
</div>

		
			<div id="new-review-options">
				<div id="new-review-submit">								
					<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Invia', 'reviews' ); ?>" />
				</div>
			</div>
			
		</div>
		  
			
		<!--------------------------------------------- sezione RATING ------------------------------------->
		<div id="new-review-rating">			
					
			<div class="rating-container"><span class="rating-title">Prezzo</span> 
				<ul id="prezzo" class='star-rating'>	
					<li class='current-rating' style="width: <?php echo 25*$prezzo;?>px"></li>			
					<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
					<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
					<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
					<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
					<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>				
				</ul><input type="hidden" name="prezzo" value="<?php echo $prezzo?>" />
			</div>		
			
			<div class="rating-container"><span class="rating-title">Servizio</span> <ul id="servizio" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$servizio;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="servizio" value="<?php echo $servizio?>" />
			</div>	
			<div class="rating-container"><span class="rating-title">Qualit&agrave;</span> <ul id="qualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$qualita;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="qualita" value="<?php echo $qualita?>" />
			</div>		
			<div class="rating-container"><span class="rating-title">Puntualit&agrave;</span> <ul id="puntualita" class='star-rating'>	
				<li class='current-rating' style="width: <?php echo 25*$puntualita;?>px"></li>			
				<li><a href="#" onclick="return vote(1, this);" title='1 / 5' class='one-star'>1</a></li>
				<li><a href="#" onclick="return vote(2, this);" title='2 / 5' class='two-stars'>2</a></li>
				<li><a href="#" onclick="return vote(3, this);" title='3 / 5' class='three-stars'>3</a></li>
				<li><a href="#" onclick="return vote(4, this);" title='4 / 5' class='four-stars'>4</a></li>
				<li><a href="#" onclick="return vote(5, this);" title='5 / 5' class='five-stars'>5</a></li>
			</ul><input type="hidden" name="puntualita" value="<?php echo $puntualita?>" />
			</div>	
			<div class="rating-container"><span class="rating-title">Affidabilit&agrave;</span> <ul id="affidabilita" class='star-rating'>	
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