<?php


?>

<form action="<?php bp_reviews_post_form_action(); ?>" method="post" id="review-form"  role="complementary">

	<?php do_action( 'bp_before_review_post_form' ); ?>

	<div id="review-writer-avatar">
		<a href="<?php echo bp_loggedin_user_domain(); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>

	<h5><?php
        
      //  if()?>
        
       <?php  _e('Scrivi una nuova review!','reviews');?> </h5>

	<div id="new-review-content">
		<div id="new-review-textarea">
			<textarea name="new-review" id="new-review" cols="50" rows="10"></textarea>
		</div>

		<div id="new-review-options">
			<div id="new-review-submit">
				<input type="submit" name="review-submit" id="review-submit" value="<?php _e( 'Post', 'reviews' ); ?>" />
			</div>
                </div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->
      
        <?php do_action( 'bp_after_review_post_form' ); ?>
	<?php wp_nonce_field( 'new_review', '_wpnonce_new_review' ); ?>
	

</form>