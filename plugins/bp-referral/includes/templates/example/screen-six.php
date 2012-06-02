<?php
//--------------------------------------------------- LISTA 6 - CHIEDI REFERRAL 1/2 - DISabilitato ------------------------------------------------------------------------------->		
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
	
		<!-- MESSAGGIO  -->		
		<h5><?php _e( 'Puoi richiedere un REFERRAL ad un utente una volta sola', 'referrals' ) ?></h5>
		<br/><br/>
		
		<h6><?php _e( '2 possibilit&agrave: TRASH o PENDING', 'referrals' ) ?></h6>		
		<br/><br/>
		<h6><?php _e( '- PENDING: la tua richiesta Referral si trova in moderazione e attende di essere confermata', 'referrals' ) ?></h6>		
		<br/>
		<h6><?php _e( '- TRASH: la tua richiesta Referral &egrave stata rifiutata/cestinata dall utente a cui l\'hai chiesta', 'referrals' ) ?></h6>
		
</div><!-- #item-body -->
</div><!-- .padder -->

<!-- SIDEBAR --->
<?php locate_template( array( 'sidebar.php' ), true ) ?>						<!-- locate_template () -->

</div><!-- #content -->
</div>
</div>

<!-- FOOTER -->	
<?php get_footer() ?>