<div id="sidebar-squeeze">			
	<div id="main-column">

			<div id="item-body">

				<?php do_action( 'bp_before_member_body' ); ?>

<?php
// Include il FORM contenuto nel file di template 'post-form.php'
// L'inclusione avviene tramite la funzione 'bp_reviews_post_form()'
// del file 'template-tags.php' nella cartella '/core'


//if(review_current_user_can_write())
//   bp_reviews_post_form();
?>
   

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
    <?php _e('Non ci sono review al momento','reviews');?>
</div>
<?php endif;

				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_home_content' ); ?>

	</div><!-- #main-column -->
		<?php get_sidebar( 'buddypress' ); ?>
</div><!-- #sidebar-squeeze -->

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>

