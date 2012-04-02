<?php

//--------------------------------------------------------------- SCREEN 2 --> Scrivi Review-----------------------------------------------------------------------------------------

get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="background: #EAEAEA; ">

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
	
<div id="item-body">											<!-- ma � ripetuto?!-->

	<?php do_action( 'bp_before_member_body' ); ?>
	
	<div id="item-body">											<!-- ma � ripetuto?!-->
		<div class="item-list-tabs no-ajax" id="subnav">
			<ul>
				<!-- -->
				<?php bp_get_options_nav() ?>
			</ul>
		</div>


	
	
	
	
	
	
	
<!-- MESSAGGIO  Opt 1 -->
<h4><?php _e( 'Scrivi una review per '.bp_get_displayed_user_fullname() , 'reviews' ) ?></h4>

							
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM - met 2	- no inclusione ESTERNA
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

	<form action = "<?php bp_review_form_action() //bp_reviews_post_form_action() ?> " method="post" id="review-form" class="standard-form">
	
		<!-- DO ACTION -->
		<?php do_action( 'bp_before_review_post_form' ); ?>

		<!-- Avatar -->
		<div id="review-writer-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
			</a>
		</div>

		<!-- MESSAGGIO  Opt 2 -->
		<h5> <?php  //_e('Scrivi una nuova review!','reviews');?> </h5>

		<!-- Review -->
		<div id="new-review-content">

			<div id="new-review-textarea">	
				<label for="review-title"> Titolo Review </label>			
				<textarea name="review-title" id="review-title" cols="2" rows="2"></textarea>
			</div>
			
			<br/>
			
			<div id="new-review-textarea">			
				<label for="review-content"> Testo </label>			
				<textarea name="review-content" id="review-content" cols="50" rows="10"></textarea>
			</div>
										
			<div id="new-review-options">
				<div id="new-review-submit">								
					<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Post', 'reviews' ); ?>" />
				</div>
			</div>
		
			<!-- aqui? -->

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

Qualit�

Puntualit�
Affidabilit�
Innovazione
-->		  
		  		  		  
		<!-- DO ACTION -->
		<?php do_action( 'bp_after_review_post_form' ); ?>								

		<!-- [WPNONCE] -->
		<?php wp_nonce_field( 'bp_review_new_review' ); ?>		
		
	</form>
<!--------------------------------------------------------------------------fine FORM -------------------------------------------------------------------------------------->						


</div><!-- #item-body -->
</div><!-- .padder -->
</div><!-- #content -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

<!-- FOOTER -->	
<?php get_footer() ?>