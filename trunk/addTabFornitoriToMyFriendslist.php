<?php

//aggiunge il filtro sui fornitori alla pagina delle adesioni
function addTabFornitoriAdesioni() {
  ?>
      <li><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/?s=Fornitore' ?>">
	<?php printf( __( 'My Suppliers', 'buddypress') ); ?></a>
      </li>
  <?php
    
}
//aggiunge il filtro sui fornitori alla pagina del profilo
function addTabFornitori() {
// Add the subnav items to the friends nav item
		$sub_nav[] = array(
			'name'            => __( 'My Suppliers', 'buddypress' ),
			'slug'            => 'my-friends',
			'parent_url'      => bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/?s=Fornitore',
			'parent_slug'     => bp_get_friends_slug(),
			'screen_function' => 'friends_screen_my_friends',
			'position'        => 10,
			'item_css_id'     => 'friends-my-friends'
		);
bp_core_new_subnav_item($sub_nav);

}


add_action("bp_members_directory_member_sub_types", "addTabFornitoriAdesioni");

add_action("bp_member_header_actions", "addTabFornitori");

// Add site credits by filtering exising text in footer.php from bp-default.
add_filter('gettext', 'frisco_msitecredits', 20, 3);

function frisco_msitecredits( $translated_text, $untranslated_text, $domain ) {
   $custom_field_text = 'Proudly powered by <a href="%1$s">WordPress</a> and <a href="%2$s">BuddyPress</a>.';
    if ( $untranslated_text === $custom_field_text ) {
        return '-';
    }
    return $translated_text;
}
?>