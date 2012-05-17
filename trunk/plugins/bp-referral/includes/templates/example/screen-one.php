<?php
//---------------------------------------------------------- SCREEN 1  - Richieste REFERRAL ricevute da me e ACCETTATE (pubblicate)  -----------------------------------------------------------------------------------------
?><?php get_header() ?>

	<!-- CONTENT -->
	<div id="content">
	
		<!-- PADDER -->
		<div class="padder" style="background: #EAEAEA;  padding: 19px 19px 0;">

			<div id="item-header">
				<!-- buddypress MEMBER HEADER -->
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>		<!-- locate_template () -->
			</div>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<!-- -->
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>
			
<!------------------------------------------>			
<div id="sidebar-squeeze">										<!-- pezza per FRISCO -->
	<div id="main-column">
<!------------------------------------------>				
	
<div id="item-body">											

	<?php do_action( 'bp_before_member_body' ); ?>
	
	<div class="item-list-tabs no-ajax" id="subnav">
		<ul>
			<!-- -->
			<?php bp_get_options_nav() ?>
		</ul>
	</div>

	
<!----------------------------------------------------------- LISTA 1 - Richieste REFERRAL ricevute da me e ACCETTATE (pubblicate)  -------------------------------------------------------->	

	
		
	<!-- MESSAGGIO  -->
	<h5><?php _e( 'Richieste REFERRAL ricevute e ACCETTATE (pubblicate) ', 'referrals' ) ?></h5>	
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'publish'																		//---PUBISH
				
				
			,	'post_type'			=> 'referral'				//'referral'
			,	'meta_query'		=> array()				//META_QUERY!
		);

		$query_args['meta_query'][] = array					//META_QUERY!
		(
				'key'	  => 'bp_referral_recipient_id',
				'value'	  => (array)bp_displayed_user_id(),
				'compare' => 'IN' 							// Allows $recipient_id to be an array 
		);		
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);	
	?>
				
	<!-- IF -->					
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			

			<br/> 
			
			<div class="title">					
				<?php $authorlogin= get_the_author_meta('user_login')?>										
				<h4><?php  
					the_title('<a href="' . 	get_permalink() . '" title="'    .	the_title_attribute('echo=0')    .	'"rel="bookmark">','</a>');
				?></h4>				
			</div>	
			
			<small style = "float: right;"><strong><?php _e('Autore richiesta REF (ovvero Dest. REF): ');?> <a href="<?php echo bp_core_get_user_domain($authorlogin).$authorlogin?>"><?php the_author_meta('user_nicename');?></a></strong></small>
			
			<br/> 
					
			<hr />
			
		<?php endwhile; ?>
		
	<?php else: ?>		
		
		<h5><?php _e( 'no referrals!', 'referrals' ) ?></h5>																	
	
	<?php endif; ?>
	
	<!-- RESET -->
	<?php wp_reset_postdata() ?>		

</div><!-- #item-body -->
</div><!-- .padder -->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						
</div><!-- #content -->
</div>
<!-- SIDEBAR --->
</div>
<!-- FOOTER -->	
<?php get_footer() ?>