<?php








//  IMPORTANTE: una volta che la DIRECTORY è disabilitata questo FILE non serve più a niente!
















/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
	
	bp_get_review_root_slug()
	
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			

	get_header
	get_sidebar
	get_footer
	
	do_action  
	
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------
	
	bp_core_load_template
	bp_get_root_domain
-----------------------------------------
global $bp
-----------------------------------------

-----------------------------------------
[T]
-----------------------------------------

	_e( 'Reviews Directory', 'bp-review' ); 
	
	
*/
?>

<!-- buddypress HEADER -->
<?php get_header( 'buddypress' ); ?>

	<!-- DO_ACTION -->
	<?php do_action( 'bp_before_directory_review_page' ); ?>

	<!-- CONTENT -->
	<div id="content">		
		
		<!-- padder -->
		<div class="padder">
		
			<!-- DO_ACTION -->
			<?php do_action( 'bp_before_directory_review' ); ?>

			<!-- form ??? --> 
			<form action="" method="post" id="review-directory-form" class="dir-form">

				<!-- TITOLO -->
				<h3> 
					<?php _e( 'Lista Reviews ', 'bp-review' ); ?> 
				</h3>
				
				<!-- DO_ACTION -->
				<?php do_action( 'bp_before_directory_review_content' ); ?>
				
				<!-- DO_ACTION -->
				<?php do_action( 'template_notices' ); ?>

				<div class="item-list-tabs no-ajax" role="navigation">
					<ul>
						<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_review_root_slug() ); ?>"><?php printf( __( 'All Reviews <span>%s</span>', 'buddypress' ), bp_review_get_total_review_count() ); ?></a></li>
						
						<!-- DO_ACTION -->
						<?php do_action( 'bp_review_directory_review_filter' ); ?>
					</ul>
				</div><!-- .item-list-tabs -->

				<div id="review-dir-list" class="review dir-list">
					<?php bp_core_load_template( 'review/review-loop' ); ?>				<!-- carica template file 'review-loop.php' -->
				</div><!-- #reviews-dir-list -->

				<!-- DO_ACTION -->
				<?php do_action( 'bp_directory_review_content' ); ?>
				
				<?php wp_nonce_field( 'directory_review', '_wpnonce-review-filter' ); ?>	<!-- wp_nonce_field -- FILTER -->

				<!-- DO_ACTION -->
				<?php do_action( 'bp_after_directory_review_content' ); ?>
			</form><!-- #review-directory-form -->

			<!-- DO_ACTION -->
			<?php do_action( 'bp_after_directory_review' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<!-- DO_ACTION -->
	<?php do_action( 'bp_after_directory_review_page' ); ?>
	
<!-- buddypress SIDEBAR -->
<?php get_sidebar( 'buddypress' ); ?>

<!-- buddypress FOOTER -->
<?php get_footer( 'buddypress' ); ?>

