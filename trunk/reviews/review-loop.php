<?php 
if(review_current_user_can_write())
    bp_reviews_post_form();?>

<?php if(bp_has_reviews()):?>
<div id="pag-top" class="pagination">

		<div class="pag-count" id="review-count-top">

			<?php bp_reviews_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="review-pag-top">

			<?php bp_reviews_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_reviews_list' ); ?>
<ul id="review-list" class="item-list" role="main">
<?php while(bp_reviews()):bp_the_review();

        bp_reviews_load_template('reviews/entry.php')
?>  
 
<?php endwhile;?>
</ul>
<?php else:?>
<div class="error" id="message">
    <?php _e('There is no review available at the moment!','reviews');?>
</div>
<?php endif;?>