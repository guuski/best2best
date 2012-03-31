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

	bp_ajax_querystring																		//??
	
-----------------------------------------
global $bp
-----------------------------------------

-----------------------------------------
 [T]
-----------------------------------------


*/
?>

<!-- DO_ACTION -->
<?php do_action( 'bp_before_review_loop' ); ?>

<!-- IF -->
<!-- richiama la funzione 'bp_review_has_reviews()' in.... -->
<?php if ( bp_review_has_reviews( bp_ajax_querystring( 'review' ) ) ) : ?>			<!-- AJAX ?! -->
	<?php // global $reviews_template; var_dump( $reviews_template ) ?>				<!-- DEBUG -->
		
	<div id="pag-top" class="pagination">
		<div class="pag-count" id="review-dir-count-top">
			<?php bp_review_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="review-dir-pag-top">
			<?php bp_review_review_pagination(); ?>
		</div>
	</div>

	<!-- DO_ACTION -->
	<?php do_action( 'bp_before_directory_review_list' ); ?>

	<!-- lista delle Reviews -->
	<ul id="review-list" class="review-list" role="main">		
		<!-- WHILE-->
		<?php while ( bp_review_has_reviews() ) : bp_review_the_review(); ?>
			<li>
				<div class="reviewer-avatar">																<!--reviewER-->
					<?php bp_review_reviewer_avatar( 'type=thumb&width=50&height=50' ); ?>					<!--reviewER-->
				</div>

				<div class="review">

					<div class="review-title"><?php //bp_review_review_title() ?></div>
					
						<!---------------------------------------------------------------->
						<div class="review-title">
							<?php //the_title() ?>
							<?php the_title('<h2 class="pagetitle"> <a href="' . 	get_permalink() . '" title="'    .	the_title_attribute('echo=0')    .	'"rel="bookmark">','</a></h2>');?>
						</div>
						<!---------------------------------------------------------------->
						
					<div class="review-content"><?php //bp_review_review_content() ?></div>
						<!---------------------------------------------------------------->
						<div class="review-content"><?php the_content() ?></div>
						<!---------------------------------------------------------------->
						
						
										
						
						
					<!-- DO_ACTION -->
					<?php do_action( 'bp_directory_review_item' ); ?>										<!--item????-->
				</div>
				
				
				<div class="clear"></div>
			</li>
		<?php endwhile; ?>
	</ul>
	
	<!-- DO_ACTION -->
	<?php do_action( 'bp_after_directory_review_list' ); ?>
	
	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="review-dir-count-bottom">
			<?php bp_review_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="review-dir-pag-bottom">
			<?php bp_review_review_pagination(); ?>
		</div>
	</div>

<!-- ELSE -->	
<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'nessuna review', 'buddypress' ); ?></p>
	</div>
	
<?php endif; ?>

<!-- DO_ACTION -->
<?php do_action( 'bp_after_review_loop' ); ?>
