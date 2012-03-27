<?php 
	/*
	Plugin Name: bp_MSelect
	Plugin URI: http://wordpress.org/extend/plugins/
	Description: Agginge un nuovo tipo campo, nel campo profilo, chiamato 'box selezione multipla raggruppata'
	Author: Giovanni Giannone
	Version: 1.0
	Author URI: http://
	*/
include_once('bp-multiselect-db.php');
include_once('bp-multiselect-query.php');

//include_once(ABSPATH .'wp-config.php');
include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');



//Variabili
$ms_nome='box selezione multipla raggruppata';

/*Questo metodo aggiunge la mia nuova field_type a quelle esistenti 
 * presenti nell'array $field_types*/
function bpd_add_new_xprofile_field_type($field_types){
		//echo "bpd_add_new_xprofile_field_type<br />";
		global $ms_nome;
		$ms_field_type = array($ms_nome);
		$field_types = array_merge($field_types, $ms_field_type);
		return $field_types;
    
}
add_filter( 'xprofile_field_types', 'bpd_add_new_xprofile_field_type' );


/*Questo metodo mi consente di interagire con il lato back-end durante la 
 * visualizzazione dei campi profilo  * */
function bpd_admin_render_new_xprofile_field_type($field, $echo = true){
		//echo "bpd_admin_render_new_xprofile_field_type<br />";
		global $ms_nome;
		ob_start();
	        switch ( $field->type ) {
	            case $ms_nome:
	            
					ms_getHTMLbackend();
					
	                ?>

	                <?php
						/*
	                     * <input type="file" name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>" value="" />
	                     * */
	                break;   
	            default :
	                ?>
	                    <p>Field type unrecognized</p>
	                <?php
	        }
	 
	        $output = ob_get_contents();
	    ob_end_clean();
	 
	    if($echo){
	        echo $output;
	        return;
	    }
	    else{
	        return $output;
	    }
	     
	}
	add_filter( 'xprofile_admin_field', 'bpd_admin_render_new_xprofile_field_type' );
	
	
/*Questo metodo mi consente di interagire con il lato front-end durante la 
 * modifica dei campi profilo  * */	
function bpd_edit_render_new_xprofile_field($echo = true){
		//echo "bpd_edit_render_new_xprofile_field<br />";
		global $ms_nome;
	    if(empty ($echo)){
	        $echo = true;
	    }
	    
	    ob_start();
	        if ( bp_get_the_profile_field_type() == $ms_nome ){
	            //$imageFieldInputName = bp_get_the_profile_field_input_name();
	            //$image = WP_CONTENT_URL . bp_get_the_profile_field_edit_value();
	            
				 ms_getHTMLfrontend();
				
	        ?>
				
	        <?php	        
	        /*
	         *  <label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
	            <input type="file" name="<?php echo $imageFieldInputName; ?>" id="<?php echo $imageFieldInputName; ?>" value="" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
	            <img src="<?php echo $image; ?>" alt="<?php bp_the_profile_field_name(); ?>" />
	         * */
	             
	        }
	 
	        $output = ob_get_contents();
	    ob_end_clean();
	 
	    if($echo){
	        echo $output;
	        return;
	    }
	    else{
	        return $output;
	    }
	 
	}
add_action( 'bp_custom_profile_edit_fields', 'bpd_edit_render_new_xprofile_field' );
	
	
/*Questo metodo riscrive l'action hook di default allo scopo di poter 
 * supportare il nuovo tipo profilo*/
function bpd_override_xprofile_screen_edit_profile(){
		//echo "bpd_override_xprofile_screen_edit_profile<br />";
	    $screen_edit_profile_priority = has_filter('bp_screens', 'xprofile_screen_edit_profile');
	 
	    if($screen_edit_profile_priority !== false){
	        //Remove the default profile_edit handler
	        remove_action( 'bp_screens', 'xprofile_screen_edit_profile', $screen_edit_profile_priority );
	 
	        //Install replalcement hook
	        add_action( 'bp_screens', 'bpd_screen_edit_profile', $screen_edit_profile_priority );
	    }
	}
add_action( 'bp_actions', 'bpd_override_xprofile_screen_edit_profile', 10 );
	
//Create profile_edit handler
function bpd_screen_edit_profile(){
		//echo "bpd_screen_edit_profile<br />";
	    if ( isset( $_POST['field_ids'] ) ) {
			//echo $_POST['field_ids'];
	        if(wp_verify_nonce( $_POST['_wpnonce'], 'bp_xprofile_edit' )){
	 
	            $posted_field_ids = explode( ',', $_POST['field_ids'] );
	 
	            $post_action_found = false;
	            $post_action = '';
	            if (isset($_POST['action'])){
	                $post_action_found = true;
	                $post_action = $_POST['action'];
	 
	            }
	 
	            foreach ( (array)$posted_field_ids as $field_id ) {
	                $field_name = 'field_' . $field_id;
	 
/*
	                if ( isset( $_FILES[$field_name] ) ) {
	                    require_once( ABSPATH . '/wp-admin/includes/file.php' );
	                    $uploaded_file = $_FILES[$field_name]['tmp_name'];
	 
	                    // Filter the upload location
	                    add_filter( 'upload_dir', 'bpd_profile_upload_dir', 10, 1 );
	 
	                    //ensure WP accepts the upload job
	                    $_POST['action'] = 'wp_handle_upload';
	 
	                    $uploaded_file = wp_handle_upload( $_FILES[$field_name] );
	 
	                    $uploaded_file = str_replace(WP_CONTENT_URL, '', $uploaded_file['url']) ;
	 
	                    $_POST[$field_name] = $uploaded_file;
	 
	                }
*/
	            }
	 
	            if($post_action_found){
	                $_POST['action'] = $post_action;
	            }
	            else{
	                unset($_POST['action']);
	            }
	 
			}
	    }
	 
	    if(!defined('DOING_AJAX')){
	        if(function_exists('xprofile_screen_edit_profile')){
	            xprofile_screen_edit_profile();
	        }
	    }
	}
/*	
			if ( !xprofile_set_field_data( $field_id, $bp->displayed_user->id, $value, $is_required[$field_id] ) )
					$errors = true;
				else
					do_action( 'xprofile_profile_field_data_updated', $field_id, $value );
			}

			do_action( 'xprofile_updated_profile', $bp->displayed_user->id, $posted_field_ids, $errors );
*/
	

/*
function bpd_profile_upload_dir( $upload_dir ) {
		//echo "bpd_profile_upload_dir<br />";
		global $bp;
	 
	    $user_id = $bp->displayed_user->id;
	    $profile_subdir = '/profiles/' . $user_id;
	 
	    $upload_dir['path'] = $upload_dir['basedir'] . $profile_subdir;
	    $upload_dir['url'] = $upload_dir['baseurl'] . $profile_subdir;
	    $upload_dir['subdir'] = $profile_subdir;
	 
		return $upload_dir;
	}
*/
	
function bpd_load_js() {
		//echo "bpd_load_js<br />";
		wp_enqueue_script( 'bpd-js', get_bloginfo('url') . '/wp-content/plugins/bp-multiselect/js/xprofile-multiselect.js',
							array( 'jquery' ), '1.0' );
}

function bpd_load_css() {
		//echo "bpd_load_css<br />";
		wp_enqueue_style( 'bpd-css', get_bloginfo('url') . '/wp-content/plugins/bp-multiselect/css/xprofile-multiselect.css');
}

add_action( 'wp_print_scripts', 'bpd_load_js' );
add_action( 'admin_init', 'bpd_load_css' );
add_action( 'init', 'bpd_load_css' );
?>
