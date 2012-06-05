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





//add_action( 'bp_members_directory_member_types', 'cM_adesioni_Type');

//add_action( 'bp_members_directory_member_sub_types', 'cM_adesioni_SubType');
add_filter ('bp_core_get_users', 'cM_adesioni_SubType', 99, 2);

//add_action( 'bp_directory_members_content', 'cM_adesioni_Cont');

//add_action( 'bp_members_directory_order_options', 'cM_adesioni_Cont');


//add_filter ('bp_core_get_users', 'bps_search', 99, 2);
/*
function bps_search ($results, $params)
{
	global $bp;
	global $wpdb;
	global $bps_list;
	global $bps_options;
	
	//[...]
	
}
*/
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
		
		$query = "SELECT f.name, f.id FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag' ORDER BY name ASC";
			
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
		cM_getScript();
		$cM_array = cM_getALLfield();
		foreach ($cM_array as $k => $v){
			
			//CONTO LE OCCORRENZE=======================================
			$cM_cont=0;
			$cM_array2 = cM_getIDfield();
			foreach ($cM_array2 as $key_c_utente => $c_utente){
				$field_selected=explode(", ",$c_utente->value);
				if (in_array($v->name, $field_selected))
				{
					$cM_cont++;
				}
			}
		
			//==========================================================
?>

	<span class='cM_box'
		onmouseover='cM_labelon(this)' 
		onmouseout='cM_labeloff(this)'
		onclick='cM_open("cM_labelhidden<?php echo $v->id;?>","cM_labelprev<?php echo $v->id;?>")'>
							
			<label id='cM_labelprev<?php echo $v->id;?>' class='cM_labelprev'>
<?php
			//======================================
										
			echo ($v->name ."<span>($cM_cont)</span><br />"); 
			
			//======================================
										
?>
			</label>
							
			<label id='cM_labelhidden<?php echo $v->id;?>' class='cM_labelhidden'  style='display:none;'>
				<?php echo $v->name ?><br/>

<?php
			
			//======================================
			
		echo "<div style='position:relative; height:60px; width:90%;'>";
			$cM_array2 = cM_getIDfield();
			foreach ($cM_array2 as $key_c_utente => $c_utente){
				$field_selected=explode(", ",$c_utente->value);
				if (in_array($v->name, $field_selected))
				{
					$attivo = get_userdata($c_utente->user_id);
					echo  "
					
						<a style='float:left;' href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >
							".get_avatar($c_utente->user_id,42)."
						</a>
						";
						
					
					
				}
			}
		echo "</div>";
			
			//======================================
			
			?>
			</label>
	</span>
						
<?php
			
		}
	}

	function cM_getScript()
	{ 
		
//======================================================================
?>
	
			<script language='JavaScript' type='text/javascript'>
			<!--

				function cM_open(labelhidden, labelprev)
				{
					jQuery('label#'+labelhidden).fadeToggle("fast");
					jQuery('label#'+labelprev).toggle();
					
				}
				
				function cM_labelon(t)
				{	
					t.style.color = '#87badd';
					t.style.background ='transparent' ;
					t.style.cursor = 'pointer';
				}

				function cM_labeloff(t)
				{
					t.style.color = '#000';
					t.style.background ='transparent' ;
					t.style.cursor = 'default';
				}	
			//-->
			</script>

			<style type='text/css'>
				.cM_box{
					
					background-color:transparent;
					
					font-weight:bold;
					color:#000;
					
					
				}
			
				.cM_labelhidden{
					color:#787878;
				}
				
				.cM_labelprev{
					color:#787878;
					
				}
				
				.cM_label{
					
				}
			</style>
<?php
//======================================================================
	
	}
	

?>
