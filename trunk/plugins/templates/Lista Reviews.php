<?php 
/*
 
Template Name: Lista Reviews
 
*/

get_header() ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<!--MOI -- Query reviews from the database. -->
		<?php		
		$loop = new WP_Query(
			array(
				'post_type' => 'review',
				'post_per_page' => 10
			)
		);
		?>			
		<div class="page" id="blog-page" role="main">
			
			<!--MOI -- -->
			<?php while($loop->have_posts()): $loop->the_post();?>
				
				<h2 class="pagetitle"><?php the_title(); ?></h2>							
				
				<!--MOI -- -->
				<?php the_title('<h2 class="pagetitle"> <a href="' . 
									get_permalink() . '" title="'    .
									the_title_attribute('echo=0')    .
									'"rel="bookmark">','</a></h2>');
				?>
				
				<!--MOI -- -->
				<div class="entry">
					<?php the_content();  ?>				
				</div>
			<?php endwhile; ?>
			
			<!--MOI -- RESET POSTDATA	-->
			<?php wp_reset_postdata() ?>							
			
		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>
