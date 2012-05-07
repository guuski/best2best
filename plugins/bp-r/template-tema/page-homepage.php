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

get_header();

?>

	<style type="text/css">
		.mh_struttura{
			position:relative;
			height:300px;
			weight:550px;
			margin-top:50px;
		}
		
		.mh_contenitore{
			position:relative;
			height:100px;
			weight:100%;
			
			
		}
		.mh_link{
			float:left;
			width:200px;
			height:100px;
			margin-left:100px;
			
			text-align: center;
			vertical-align: middle;
			font-size:30px;
		}
		.mh_separatore{
			position:relative;
			height:50px;
			weight:100%;
			
			}
	</style>

	
	<div id="content">

		<div class="padder">
			<div class="page" id="blog-page" role="main">
<?php 
		
//======================================================================		
			if ( !is_user_logged_in() ) : 

?>

				<div style="width:100%; margin:0 auto;">
					<div>
						<div>
							<h2 style="border-bottom: 2px solid #057022;">Che cosa siamo?</h2>
						</div>
						<p>
							<img class="alignleft size-full wp-image-56" title="Best 2 Best Network" src="wp-content/uploads/2012/01/network.jpg" alt="Best 2 Best Network" width="225" height="224">Best2best &egrave; la novit&agrave; nel mondo del B2B per il settore turismo.
						</p>
						<p>
							Grazie a Best2best infatti, le aziende dell&apos;Alto Adige avranno a disposizione uno strumento valido per incrementare le efficienze delle proprie relazioni d&apos;affari ed approfittare delle occasioni che le aziende vorranno mettere loro a disposizione online.
						</p>
						<p>
							Con Best2Best.it, l&apos;azienda potr&agrave; individuare il partner commerciale che meglio si addice alle caratteristiche ricercate e condividere la propria esperienza con la propria community.
						</p>
						<p>&nbsp;</p>
						<p>
							Recensioni ed opinioni possono essere indicate con diverso livello di profondit&agrave;, garantendo cos&igrave; a chi ricerca un fornitore la migliore esperienza possibile. 
							<a href="about">...continua </a> oppure <a href="registrati">...registrati! </a>
						</p>
					</div>
				</div>
<?php
			endif; 
			
//======================================================================			
			if ( is_user_logged_in() ) : 
			
				locate_template( array( 'activity/post-form.php'), true ); 

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
						<a href="attivita" class="mh_link button">Attivita'</a>
						<a href="<?php echo bp_core_get_user_domain($attivo->user_login).$attivo->user_login.DS ?>messages" class="mh_link button">Messaggi</a>
					</div>
					
					<div class="mh_separatore"></div>
					
					<div class="mh_contenitore">
						<a href="<?php echo bp_core_get_user_domain($attivo->user_login).$attivo->user_login.DS ?>review" class="mh_link button">Recensioni</a>
						<a href="#" class="mh_link button">Offerte</a>
					</div>	
				</div>
	
<?php 


				if (have_posts()) : while (have_posts()) : the_post(); ?>

					<!--
						<h2 class="pagetitle"><?php the_title(); ?></h2>
					-->

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry">
	
							<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							
							<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

						</div>

					</div>

					<?php comments_template(); ?>

				<?php endwhile; endif; 
		
			endif;
//======================================================================
?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php 
		get_sidebar() 
	?>

<?php get_footer(); ?>
