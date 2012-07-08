<?php

class BP_better_friendship {
	var $get_params = array();
	var $filterable_fields = array();
	
	/**
	 * PHP 4 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */
	function BP_better_friendship() {
		$this->__construct();
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */	
	function __construct() {
		define( 'BPBF_INSTALL_DIR', trailingslashit( dirname(__FILE__) ) );
		define( 'BPBF_INSTALL_URL', plugins_url() . '/bp-better-frienship/' );
		
		
		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'wp_footer', array( $this, 'friendship_panel' ) );
		add_action( 'bp_directory_members_item', array( $this, 'show_member_type' ) );
		
		add_action( 'wp_ajax_addremove_friend',array( $this, 'bpbf_friends_friendship_requested') );
		
		add_action( 'wp_ajax_accept_friendship', array( $this,'bpbf_dtheme_ajax_accept_friendship' ));
		
		
// 		add_action( 'wp_ajax_members_filter', array( $this, 'filter_ajax_requests' ), 1 );

	}
	
	function my_add_frontend_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-button');
	}
	
	
	function setup() {
		global $bp;
		$version = '20120110';
		wp_dequeue_script( 'dtheme-ajax-js');
		// Enqueue the global JS - Ajax will not work without it
		wp_enqueue_script( 'dtheme-ajax-js', get_stylesheet_directory_uri() . '/_inc/global.js', array( 'jquery' ), $version );
		// Temporary backpat for 1.2
		if ( function_exists( 'bp_is_current_component' ) ) {
			$is_members = bp_is_current_component( 'members' );
		} else {
			$is_members = $bp->members->slug == bp_current_component();
		}
		
		if ( $is_members && !bp_is_single_item() ) {			
		}
	}
	
	function friendship_panel() {
		?>
		<style>.ui-button-text-only .ui-button-text {padding: 0px 6px !important; }
	div.item-list-tabs ul.bpbd-search-terms li a,ul.bpbd-search-terms  div.item-list-tabs ul.bpbd-search-terms li span {display: inline !important; }
	div.item-list-tabs ul li:first-child {margin-left: 5px !important;}
</style>
		<span id="bfriendship" 
		style="text-align:left; display:none; font-size:12px; position: absolute;width: 280px;border: 1px solid #ccc; z-index:100; background: #FFFFFF; padding: 5px">
			<h5 class="bfriendtitle" style="text-align: left; border-bottom: 1px dashed grey; width: 95%; margin-bottom:5px; padding-bottom:5px">
				<?php _e("Aggiungi al tuo Network come:")?></h5>
			<span class="buttonset">
				<input class="bfriendradio" value="collega" name="ftype" id="collega" type="radio" style="margin: 4px 0;vertical-align: middle;"><label for="collega"><?php _e('Collega', 'bpbf'); ?></label> 
				<input class="bfriendradio" value="fornitore" name="ftype" id="fornitore" type="radio" style="margin: 4px 0;vertical-align: middle;"><label for="fornitore"><?php _e('Fornitore', 'bpbf'); ?></label>
				<input class="bfriendradio" value="cliente" name="ftype" id="cliente" type="radio" style="margin: 4px 0;vertical-align: middle;"><label for="cliente"><?php _e('Cliente', 'bpbf'); ?></label>
				<input class="bfriendradio" value="amico" name="ftype" checked="checked" id="amico" type="radio" style="margin: 4px 0;vertical-align: middle;"><label for="amico"><?php _e('Amico', 'bpbf'); ?></label>
			</span>
			<input type="button" value="<?php _e('Conferma', 'buddypress'); ?>" style="height: 20px;line-height: 10px;margin: 5px 0 0;" class="bfriendsend"/>
			<span class="bfriendabort" style="position: absolute;right: 5px;bottom: 0px;display: inline-block;text-decoration: underline;cursor: pointer;">
				<?php _e('Annulla', 'buddypress'); ?></span> 
		</span>
		<?php 
	}
	
	function show_member_type() {
		//echo '<span class="membertype">'.bp_get_member_profile_data( 'field=Tipo profilo' ).'</span>';
	}

	function bpbf_friends_friendship_requested() {
		global $bp;
				// Bail if not a POST action
		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
			return;
	
		if ( 'not_friends' == BP_Friends_Friendship::check_is_friend( $bp->loggedin_user->id, $_POST['fid'] ) ) {
			$now=array();
			$author=$bp->loggedin_user->id;
			
			$now= unserialize(get_user_meta($author, "friendship_level",true));
			$ftype=$_POST['ftype'];
				
			$dest=$_POST['fid'];
			$now[$dest]=$ftype;
			
// 			error_log("gbp.".print_r($now,true));
				
			check_ajax_referer('friends_add_friend');
			if(!update_user_meta($author, "friendship_level", serialize($now))) {
				error_log("errore durante aggiornamento usermeta");
			}
		}

		return true;
	}
	
	/* AJAX accept a user as a friend when clicking the "accept" button */
	function bpbf_dtheme_ajax_accept_friendship() {
		global $bp;
		// Bail if not a POST action
		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
			return;
	
		$now=array();
		$author=$bp->loggedin_user->id;
		
		$now= unserialize(get_user_meta($author, "friendship_level",true));
		$ftype=$_POST['ftype'];
			
		$dest=$_POST['id'];
		$now[$dest]=$ftype;
		
 		error_log("gbp.".print_r($now,true));
		if(!update_user_meta($author, "friendship_level", serialize($now))) {
			error_log("errore durante aggiornamento usermeta");
		}
		return true;
	}
	
	
}

?>