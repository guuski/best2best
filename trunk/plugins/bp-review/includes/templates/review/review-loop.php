<?php
/*
-----------------------------------------
Contenuto FILE:
-----------------------------------------

	
----------------------------------------------------
FILE, CLASSI, OGGETTI, METODI collegati o richiamati
----------------------------------------------------
	
	[PHP File]
		'bp-review-template.php'
	
			bp_review_pagination_count
			bp_review_review_pagination
			
			bp_review_has_reviews
			bp_review_the_review
			
-----------------------------------------
FUNZIONI e HOOKS (WordPress - Wp)
-----------------------------------------			
do_action
	  
-----------------------------------------
FUNZIONI e HOOKS (BuddyPress - Bp)
-----------------------------------------

	bp_ajax_querystring
	
-----------------------------------------
global $bp
-----------------------------------------

-----------------------------------------
 [T]
-----------------------------------------
<p><?php _e( 'no reviews trovate', 'buddypress' ); ?></p>

*/
?>

<?php do_action( 'bp_before_review_loop' ); ?>

<?php if ( bp_review_has_reviews( bp_ajax_querystring( 'review' ) ) ) : ?>
	<?php // global $reviews_template; var_dump( $reviews_template ) ?>
	
	<div id="pag-top" class="pagination">
		<div class="pag-count" id="review-dir-count-top">
			<?php bp_review_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="review-dir-pag-top">
			<?php bp_review_review_pagination(); ?>
		</div>
	</div>

	<?php do_action( 'bp_before_directory_review_list' ); ?>

	<ul id="review-list" class="review-list" role="main">
	<?php while ( bp_review_has_reviews() ) : bp_review_the_review(); ?>
		<li>
			<div class="reviewer-avatar">																<!--reviewER-->
				<?php bp_review_reviewer_avatar( 'type=thumb&width=50&height=50' ); ?>					<!--reviewER-->
			</div>

			<div class="review">
				<div class="review-title"><?php bp_review_review_title() ?></div>
				<?php do_action( 'bp_directory_review_item' ); ?>										<!--????-->
			</div>
			<div class="clear"></div>
		</li>
	<?php endwhile; ?>
	</ul>
	
	<?php do_action( 'bp_after_directory_review_list' ); ?>
	
	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="review-dir-count-bottom">
			<?php bp_review_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="review-dir-pag-bottom">
			<?php bp_review_review_pagination(); ?>
		</div>
	</div>
<?php else: ?>
	<div id="message" class="info">
		<p><?php _e( 'no reviews trovate', 'buddypress' ); ?></p>
	</div>
<?php endif; ?>

<?php do_action( 'bp_after_review_loop' ); ?>
