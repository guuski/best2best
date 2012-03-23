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

include_once(ABSPATH .'wp-config.php');
include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');


//Variabili
$ms_nome='box selezione multipla raggruppata';

/*Questo metodo aggiunge la mia nuova field_type a quelle esistenti 
 * presenti nell'array $field_types*/
function bpd_add_new_xprofile_field_type($field_types){
	global $ms_nome;
    $ms_field_type = array($ms_nome);
    $field_types = array_merge($field_types, $ms_field_type);
    return $field_types;
}
add_filter( 'xprofile_field_types', 'bpd_add_new_xprofile_field_type' );


/*Questo metodo mi consente di interagire con il lato backend durante la 
 * visualizzazione dei campi profilo  * */
function bpd_admin_render_new_xprofile_field_type($field, $echo = true){
	
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
	

	
function bpd_load_js() {
     wp_enqueue_script( 'bpd-js', get_bloginfo('url') . '/wp-content/plugins/bp-MSelect/js/xprofile-multiselect.js',
							array( 'jquery' ), '1.0' );
}

function bpd_load_css() {
     wp_enqueue_style( 'bpd-css', get_bloginfo('url') . '/wp-content/plugins/bp-MSelect/css/xprofile-multiselect.css');
}

add_action( 'wp_print_scripts', 'bpd_load_js' );
add_action( 'admin_init', 'bpd_load_css' );
add_action( 'init', 'bpd_load_css' );
?>
