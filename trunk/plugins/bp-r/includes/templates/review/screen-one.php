<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
	
	member-header.php
		
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	get_header()	  
	get_footer()
	
	locate_template()
	
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_get_options_nav()
	bp_get_displayed_user_nav()
	bp_displayed_user_id
	
-----------------------------------------
global $bp
-----------------------------------------
*/
?>

<!-- HEADER -->
<?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
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
<div id="sidebar-squeeze">			
	<div id="main-column">

<!------------------------------------------>				
	
			<div id="item-body">

				<?php do_action( 'bp_before_member_body' ); ?>
				
				
				
			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<!-- -->
						<?php bp_get_options_nav() ?>
					</ul>
				</div>

				<!-- MESSAGGIO -->
				<h4><?php _e( 'Scrivi una review per '.bp_get_displayed_user_fullname() , 'bp-review' ) ?></h4>
											
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->
<!--  FORM - met 2	- no inclusione ESTERNA
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
	
<!-- ROLE -->	
				
<!-- ACTIONS
	//ES
		<!-- bp_messages_form_action('compose')-->
		<!-- bp_code_snippets_form_action-->

	<!--FILE 'bp-review-functions.php'-->
		<!-- action = " <?php //bp_review_form_action();?>"-->
		<!-- action = " <?php //bp_reviews_post_form_action() ?>" -->
		
<form action = "<?php bp_review_form_action() ?> " method="post" id="reviews-form" class="standard-form">

<?php do_action( 'bp_before_review_post_form' ); ?>

	<div id="review-writer-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>

	<h5>       
       <?php  //_e('Scrivi una nuova review!','reviews');?> </h5>

	<div id="new-review-content">
		
		<div id="new-review-textarea">			
			<textarea name="review-content" id="review-content" cols="50" rows="10"></textarea>
		</div>

		<div id="new-review-options">
			<div id="new-review-submit">								
				<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Post', 'reviews' ); ?>" />
			</div>
        </div>
	</div>
      
<?php do_action( 'bp_after_review_post_form' ); ?>								

<!-- [WPNONCE] -->
<?php wp_nonce_field( 'bp_review_new_review' ) ?>
</form>

<!--

<form action="<?php //bp_reviews_post_form_action(); ?>" method="post" id="review-form"  role="complementary">

	<?php //do_action( 'bp_before_review_post_form' ); ?>

	<div id="review-writer-avatar">
		<a href="<?php //echo bp_loggedin_user_domain(); ?>">
			<?php //bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>

	<h5><?php
        
      //  if()?>
        
       <?php  //_e('Scrivi una nuova review!','reviews');?> </h5>

	<div id="new-review-content">
		<div id="new-review-textarea">
			<textarea name="new-review" id="new-review" cols="50" rows="10"></textarea>
		</div>

		<div id="new-review-options">
			<div id="new-review-submit">
				<input type="submit" name="review-submit" id="review-submit" value="<?php //_e( 'Post', 'reviews' ); ?>" />
			</div>
                </div>
	</div>
      
        <?php //do_action( 'bp_after_review_post_form' ); ?>
	<?php //wp_nonce_field( 'new_review', '_wpnonce_new_review' ); ?>
	
</form>
				
-->				
				
<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						
				
				<!-- contenuto stringa...?? -->
				<p><?php //printf( __( 'Manda %s una <a href="%s" title="Send review">review!</a>', 'bp-review' ), 
						//			bp_get_displayed_user_fullname(), 
						//			wp_nonce_url(bp_displayed_user_domain() . 
						//			bp_current_component() . '/screen-one/send-review/','bp_review_send_review' ) )  //send-review
					?></p>
				<!-- ----------------------------------------------------------------------------------------------------------------------------------------->						

				<!-- Reviews RICEVUTE-->					
				<?php if ( $reviews = bp_review_get_reviews_for_user( bp_displayed_user_id() ) ) : ?>
					<h4><?php _e( 'Reviews Ricevute', 'bp-review' ) ?></h4>

					<table id="reviews">
						<?php foreach ( $reviews as $user_id ) : ?>
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
				
<?php else: ?>	
<h5><?php _e( 'L\' utente non ha ricevuto ancora nessuna Reviews', 'bp-review' ) ?></h5>

				<?php endif; ?>

			</div><!-- #item-body -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<!-- SIDEBAR --->
	<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->
	
<!-- FOOTER -->	
<?php get_footer() ?>