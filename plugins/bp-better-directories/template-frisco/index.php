<?php

/**
 * This template file is only temporary. There weren't any hook options on this particular index file so I've added a single line to the file, momentarily. See line 26. 
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_members_page' ); ?>

	<div id="content">
		<div class="padder">

						
		<form action="" method="post" id="members-directory-form" class="dir-form">

			<h3><?php _e( 'Members Directory', 'buddypress' ); ?></h3>
																							<!-- MOI-->
			<div id="members-dir-search" class="dir-search" role="search" >	<!-- style="display: none;">	-->

				<?php bp_directory_members_search_form(); ?>										
																
			</div><!-- #members-dir-search -->
					

			<!-- BP PROFILE SEARCH -->
			<!-- i 2 FORM -->		
			<?php //do_action ('bp_profile_search_form'); ?>											<!-- ORIGINAL -->
			<?php //do_action ('bp_profile_search_form_categorie'); ?>									<!-- CATEGORIE-->		
			
			<!-- BP BETTER DIRECTORIES -->
			<?php do_action ('bpbd_search_form'); ?>									
			
						
		</form><!-- this is the one line -->
		
		
		
		
			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_member_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

					<?php do_action( 'bp_members_directory_member_types' ); ?>

				</ul>
			</div><!-- .item-list-tabs -->
			
<div id="sidebar-squeeze">			
	<div id="main-column">

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

					<li id="members-order-select" class="last filter">

						<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
						<select id="members-order-by">
							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
								<!-- <option value="categorie"><?php _e( 'Categorie', 'buddypress' ); ?></option>-->
							<?php endif; ?>
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>
							
							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>

			<div id="members-dir-list" class="members dir-list">

				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			</div><!-- #members-dir-list -->

</div><!-- #main-column -->
	<?php get_sidebar( 'buddypress' ); ?>
</div><!-- #sidebar-squeeze -->

			<?php do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

		</form><!-- #members-directory-form -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php do_action( 'bp_after_directory_members_page' ); ?>


<?php get_footer( 'buddypress' ); ?>
