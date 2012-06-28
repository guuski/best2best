<?php
//--------------------------------------------------------- SCREEN 3 - Referrals richieste da me-----------------------------------------------------------------------------------------
?>

<!-- HEADER -->
<?php get_header() ?>

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
	
	<!--------------------------------------------------- LISTA 3  Le  Referrals richieste da me (pending)------------------------------------------------------------------------------->		
	
	<!-- MESSAGGIO  -->
	<h4><?php //_e( ' Le  Referrals richieste da me (stato PENDING: il destinatario deve ancora decidere se ACCETTARLE O RIFIUTARLE)', 'referrals' ) ?></h4>
		
	<?php		
	
		$query_args = array
		(
				'post_status'		=> 'pending'																				//----------PENDING
			,	'post_type'			=> 'referral'										//'referral'
			,   'author'			=> bp_displayed_user_id()											
		);
		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);			
	
	?>
		
	<!-- IF -->	
	<?php if ( $loop->have_posts() ) : ?>	
		
		<!-- WHILE -->
		<?php while($loop->have_posts()): $loop->the_post();?>			
					
	
			<?php $recipient_referral_id	 = get_post_meta( $post->ID, 'bp_referral_recipient_id', true ); ?>							
			<?php $recipient_referral_name 	 = xprofile_get_field_data( "Nome" , $recipient_referral_id); ?>													
			<?php $authorlogin 				 = get_the_author_meta('user_login', $recipient_referral_id);?>										
								
			<small style = "float: right;">
				<strong>
					<?php  //_e('Richiesta REFERRAL inviata a: ');?> 						
					<?php  _e('inviata a: ');?> 						
					
					<a href="
						<?php echo bp_core_get_user_domain($authorlogin).$authorlogin?>">							
						<?php echo $recipient_referral_name; ?>							
					</a>
					
				</strong>
			</small>
			
			<br />
			
			<div class="title">						
				<?php  _e('Richiesta REFERRAL');?> 						
				<?php //the_title();?>						
			</div>				
				
			<br />
									
			<hr />
			
		<?php endwhile; ?>
		
	<?php else: ?>		
		
		<!-- MESSAGGIO -->
		<h6><?php //_e( ' no referral ', 'referrals' ) ?></h6>												
		
	<?php endif; ?>
	
	<!-- IMPORTANTE -->
	<?php wp_reset_postdata() ?>		
	
	<!-- ---------------------------------------------------------------------------------------------------------------------------------------------->

</div><!-- #item-body -->
</div><!-- .padder -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

</div><!-- #content -->
</div>
</div>

<!-- FOOTER -->	
<?php get_footer() ?>