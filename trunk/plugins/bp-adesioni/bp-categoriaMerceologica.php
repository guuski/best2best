<?php
/*
	Plugin Name: bp_adesioni
	Plugin URI: http://wordpress.org/extend/plugins/
	Description: Agginge un nuovo tipo campo, nel campo profilo, chiamato 'box selezione multipla raggruppata'
	Author: Giovanni Giannone
	Version: 1.0
	Author URI: http://
*/
	
/*
	pagina: http://www.best2best.it/adesioni/ [^]

	nel drill down dovrebbe poter essere possibile selezionare anche "categoria merceologica", 
	dove il risultato della query sono le aziende ordinate alfabeticamente per categoria merceologica
	quindi: categorie dalla a alla z, all'interno delle categorie le aziende ordinate dalla a alla z
*/

load_plugin_textdomain( 'cM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

add_action( 'bp_members_directory_member_types', 'cM_adesioni_Type');

add_action( 'bp_members_directory_member_sub_types', 'cM_adesioni_SubType');

add_action( 'bp_directory_members_content', 'cM_adesioni_Cont');

add_action( 'bp_members_directory_order_options', 'cM_adesioni_Cont');


/*
 * do_action( 'bp_before_directory_members_page' );
 * do_action( 'bp_before_directory_members' ); 
 * do_action( 'bp_before_directory_members_content' ); 
 * do_action( 'bp_members_directory_member_types' ); 
 * do_action( 'bp_members_directory_member_sub_types' ); 
 * do_action( 'bp_directory_members_content' );
 * do_action( 'bp_members_directory_order_options' );
 * do_action( 'bp_after_directory_members_content' ); 
 * do_action( 'bp_after_directory_members' ); 
 * do_action( 'bp_after_directory_members_page' ); 
 * 
 * */

function cM_adesioni_Type(){
	if ( is_user_logged_in() ) :
	?>
		<li id="categorie-merceologiche">
			<a href="<?php echo ABSPATH . 'wp-content/plugins/bp-adesioni/catMER.php' ?>">
				<?php printf( __( 'Categorie Merceologiche', 'buddypress' ) ); ?>
			</a>
		</li>
	<?php
	endif;
	}
	
function cM_adesioni_SubType(){
	if ( is_user_logged_in() ) :
	?>
		
		
	<?php
	
		cM_Main();
			
		
	endif;
	}
	
function cM_adesioni_Cont(){
	if ( is_user_logged_in() ) :
	?>
		
	<?php
	endif;
	}


/*====================================================================*/
/*====================================================================*/




/*====================================================================*/
/*====================================================================*/	
function cM_Main(){
		//cM_visualizzaFIELDutente();
		cM_visualizzaALLfield();
	}
	
/*====================================================================*/
/*====================================================================*/

/*====================================================================*/
/*====================================================================*/

	function cM_getIDfield(){
		global $bp;
		global $wpdb;
		
		/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
		user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
		$query = "SELECT d.user_id, d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	
		$cM_output= $wpdb->get_results( $wpdb->prepare($query) );
		/*
		if (isset($ms_output[0])) {
			$field_selected=explode(", ",$ms_output[0]->value);
			return $field_selected;
		}
		*/
		return $cM_output;
	
	}
	function cM_getALLfield(){
		global $bp;
		global $wpdb;
		
		$query = "SELECT f.name FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag' ORDER BY name ASC";
			
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	 
		return  $ms_output;
	}
	
	function cM_visualizzaFIELDutente(){
		$cM_array = cM_getIDfield();
		foreach ($cM_array as $key_c_utente => $c_utente){
		
			$attivo = get_userdata($c_utente->user_id);
			echo  "<div style='width:50px; height:50px;'> 
						<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >
							".get_avatar($c_utente->user_id,42)."
						</a>
					</div>";
			
			$field_selected=explode(", ",$c_utente->value);
			foreach ($field_selected as $k => $v)
				echo $v . "<br />";
			echo "<hr />";
		}
	}
	
	function cM_visualizzaALLfield(){
		$cM_array = cM_getALLfield();
		foreach ($cM_array as $k => $v){
			echo "<h6>$v->name</h6><br /><div style='position:reletive;height:50px;'>";
			
			$cM_array2 = cM_getIDfield();
			foreach ($cM_array2 as $key_c_utente => $c_utente){
				$field_selected=explode(", ",$c_utente->value);
				if (in_array($v->name, $field_selected))
				{
					$attivo = get_userdata($c_utente->user_id);
					echo  "
					<a style='position:relative; ' href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >
						".get_avatar($c_utente->user_id,42)."
					</a>";
				}
			}
			echo "</div>";
		}
	}
	
	

?>
