<?php
/*
 
Template Name: Home Page B2B
 
*/

/*
<?php if ( is_user_logged_in() ) : ?>
	<?php locate_template( array( 'activity/post-form.php'), true ); ?>
<?php endif; ?>
                        * 
                        * 
                        * 
<div style="width:960px; margin:0 auto;"><div>
<div>
<h2 style="border-bottom: 2px solid #057022;">Che cosa siamo?</h2>
</div>
<p><img class="alignleft size-full wp-image-56" title="Best 2 Best Network" src="wp-content/uploads/2012/01/network.jpg" alt="Best 2 Best Network" width="225" height="224">Best2best &egrave; la novit&agrave; nel mondo del B2B per il settore turismo.</p>
<p>Grazie a Best2best infatti, le aziende dell&apos;Alto Adige avranno a disposizione uno strumento valido per incrementare le efficienze delle proprie relazioni d&apos;affari ed approfittare delle occasioni che le aziende vorranno mettere loro a disposizione online.</p>
<p>Con Best2Best.it, l&apos;azienda potr&agrave; individuare il partner commerciale che meglio si addice alle caratteristiche ricercate e condividere la propria esperienza con la propria community.</p>
<p>&nbsp;</p>
<p>Recensioni ed opinioni possono essere indicate con diverso livello di profondit&agrave;, garantendo cos&igrave; a chi ricerca un fornitore la migliore esperienza possibile. <a href="about">...continua</a> oppure <a href="registrati">...registrati!</a></p></div></div><hr />'; // Output Content

*/

include_once('it_IT.php');
include_once('en_EG.php');

$lang = array();
	if (true) 
		$lang=$lang_IT;
	else
		$lang=$lang_EG;


get_header();

?>

<style type="text/css">
	.fadein { position:relative; width:696px; height:270px; }
	.fadein img { position:absolute; left:0; top:0; }
</style>

	

<div id="content">

		<div class="padder">
			
<?php 
		
			if ( is_user_logged_in() ) : 
				locate_template( array( 'activity/post-form.php'), true );
			?><script>jQuery(document).ready(function(){jQuery("#aw-whats-new-submit").click(function(){setTimeout('location.href="attivita"; return true;',2000);});});</script>
			<?php 
			endif; 
			

	
	
?>
<div class="page" id="blog-page" role="main">
<?php 
			if ( is_user_logged_in() ) :
				do_action( 'bp_before_blog_page' );
				global $user_ID;
				global $bp;
				$attivo = get_userdata($user_ID);
				

?>
	
				<!--
					<h2 class="pagetitle"><?php the_title(); ?></h2>
				-->
				
				<div class="mh_struttura">
					<div class="mh_contenitore">
						<a href="attivita" class="mh_link button"><span class="mh_attivita_big mh_big"><?php echo $lang[0]; ?></span></a>
						<a href="<?php echo bp_loggedin_user_domain() ?>messages" class="mh_link button"><span class="mh_messaggi_big mh_big"><?php echo $lang[1]; ?></span></a>
				 	</div>				
					<div class="mh_separatore"></div>
					<div class="mh_contenitore">
						<a href="reviews" class="mh_link button"><span class="mh_review_big mh_big"><?php echo $lang[2]; ?></span></a>
						<a href="#" onclick="alert_offerte(); this.blur(); return false;" class="mh_link button"><span class="mh_offerte_big mh_big"><?php echo $lang[3]; ?></span></a>
					</div>	
				</div>
		<script>function alert_offerte(){window.alert("<?php echo $lang[4]; ?>")};</script>
<?php 


				if (have_posts()) : while (have_posts()) : the_post(); ?>

					<!--
						<h2 class="pagetitle"><?php the_title(); ?></h2>
					-->

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry">
	
							<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							
							<?php //edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

						</div>

					</div>
<?php comments_template(); 
					?>
					

				<?php endwhile; endif; 
		
			endif;
//======================================================================
?>

<div class="fadein">
  <img src="<?php echo get_stylesheet_directory_uri() ?>/images/Best2Best1.png" width="696"/>
  <img src="<?php echo get_stylesheet_directory_uri() ?>/images/Best2Best2.png" width="696"/>
  <img src="<?php echo get_stylesheet_directory_uri() ?>/images/Best2Best3.png" width="696"/>
</div>
<script>jQuery(document).ready(function(){
	
	    jQuery('.fadein img:gt(0)').hide();
	    setInterval(function(){
	      jQuery('.fadein :first-child').fadeOut()
	         .next('img').fadeIn()
	         .end().appendTo('.fadein');}, 
	      5000);
});</script>
<?php 		
			if (!is_user_logged_in() ) : 
?>

 				<div style="width:100%; margin:10px auto; clear: both;display: inline-block;">
					<div>
						<div>
							<h2 style="border-bottom: 2px solid #057022;"><?php echo $lang[5]; ?></h2>
						</div>
						<?php echo $lang[6]; ?>
					</div>
				</div>
<?php 
			endif; 
?>				



		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php 
		get_sidebar() 
	?>

<?php get_footer(); ?>
