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

			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php bp_get_options_nav() ?>
					</ul>
				</div>

				<h4><?php _e( 'Welcome to Screen One', 'bp-review' ) ?></h4>
				<p><?php printf( __( 'Send %s a <a href="%s" title="Send review">review!</a>', 'bp-review' ), 
								bp_get_displayed_user_fullname(), 
								wp_nonce_url( 
									bp_displayed_user_domain() . bp_current_component() . '/screen-one/send-review/', 
									'bp_review_send_review' ) ) ?></p>

				<?php if ( $reviews = bp_review_get_reviews_for_user( bp_displayed_user_id() ) ) : ?>
					<h4><?php _e( 'Received Reviews!', 'bp-review' ) ?></h4>

					<table id="reviews">
						<?php foreach ( $reviews as $user_id ) : ?>
						<tr>
							<td width="1%"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 25, 'height' => 25 ) ) ?></td>
							<td>&nbsp; <?php echo bp_core_get_userlink( $user_id ) ?></td>
			 			</tr>
						<?php endforeach; ?>
					</table>
				<?php endif; ?>

			</div><!-- #item-body -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>
<?php get_footer() ?>