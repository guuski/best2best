<?php

include_once('bp-multiselect-db.php');
include_once('bp-multiselect-query.php');
include_once('bp-multiselect.php');

include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');

load_plugin_textdomain( 'multiselect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


$ms_nome ='box selezione multipla raggruppata';

$group_id=3;
$type='multiselectboxrag';
$description='ms Categorie Acquisti';
$is_required=0;
$is_default_option=0;
$field_order=0;
$option_order=0;
$order_by='id';
$can_delete=1;
$cnt= 0;
							
$ms_1 = "INSERT INTO wp_bp_xprofile_fields ( group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ($group_id,";
$ms_3 = " ,	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";

/*Questa funzione controlla se nella tabella wp_bp_xprofile_fields è 
 * presente un record con descriptio = ms Categorie Acquisti. in questo 
 * caso ho gia provveduto all'aggiornamento del database*/
function ms_installed(){
	global $bp;
	global $wpdb;
	
	$query = "SELECT * FROM wp_bp_xprofile_fields f";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$ms_output as $field ){
		if ($field->description=='ms Categorie Acquisti') 
			return true;
		}//end-foreach
	return false;
}


/*Questo metodo mi ritorna l'id del nuovo field creato dall'utente oppure ritorna
 * -1 in caso di assenza del field*/
function ms_newfieldisset(){
	global $bp;
	global $wpdb;
	global $ms_nome;
	$query = "SELECT * FROM wp_bp_xprofile_fields f";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$ms_output as $field ){
		if ($field->type==$ms_nome) 
			return $field->id;
		}//end-foreach
	return -1;
}

//METODO DA TESTARE
/*Questa funzione recupera le categorie associate all'utente presenti in 
 *buddypress.wp_bp_xprofile_data*/
function ms_getCategorieUTENTE(){
	global $bp;
	global $wpdb;
	global $user_ID;
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE d.user_id=$user_ID AND f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	
	if (isset($ms_output[0])) {
		$field_selected=explode(", ",$ms_output[0]->value);
		return $field_selected;
	}
	return array();
	
}


function ms_updateDBfield(){
	global $bp;
	global $wpdb;
	$ms_parent=ms_newfieldisset();
	if (!ms_installed() && ($ms_parent!=-1)) {
		$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
		ms_generaDB_ric($ms_insert,$ms_parent);
	}//end-if
		
	}

		
	function ms_generaDB_ric($ms_mat, $ms_par){
		global $ms_1,$ms_3;
		global $type;
		global $bp;
		global $wpdb;
		
		if (is_array($ms_mat)){
		
			foreach ($ms_mat as $k => $v){
				if (is_array($v)){
					$ms_2 = "$ms_par,'$type','$k'";
					$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));	
					
					$current_id= $wpdb->insert_id;//ID MACROCATEGORIA
					ms_generaDB_ric($v,$current_id);
				}//end-if
				else{
					$ms_2 = "$ms_par,'$type','$v'";
					$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));	
					
				}//end-else
			}//end-foreach
		}//end-if
	}//end-function


/*Questo metodo leggendo da DB restituisce una matrice contenente tutte
 * le categorie e le sottocategorie*/
function ms_caricaCategorie(){
	global $bp;
	global $wpdb;
	global $user_ID;
	$ms_parent=ms_newfieldisset();
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT f.id, f.parent_id, f.name FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag'";
	$ms_array=array();
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	 
	return  ms_caricaCT_ric($ms_output,$ms_parent);
}
function ms_caricaCT_ric($ms_db,$ms_par){
	
		if (is_array($ms_db)){
			$ms_mat='';
			foreach ($ms_db as $k => $v){
				if ($v->parent_id==$ms_par){
						$ms_mat["$v->name"]= ms_caricaCT_ric($ms_db,$v->id);
				}//end-if
			}//end-foreach
			return $ms_mat;
		}//end-if
	return '';
	}
/*genero l'HTML da visualizzare lato front-end*/
function ms_getHTMLfrontend(){
	$ms_insert = ms_caricaCategorie();
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	            
	echo "<span class='label'>".__(bp_get_the_profile_field_name(),'multiselect')."</span>";
	$HTML= ms_getScript();
	$HTML.="<div class='ms_divfrontend'>";
	
	$HTML.=ms_getHTML_ric($ms_insert,$ms_mycategorie);

	$HTML.= "
			</div>
			<br />
			<input type='text' readonly='readonly' 
				name='".bp_get_the_profile_field_input_name()."' 
				id='".bp_get_the_profile_field_input_name()."' 
				value=''
				style='width:500px;'/>
			<script>ms_check();</script>
			";
	
	echo $HTML;
	//ms_caricaCategorie();
	}
	
function ms_getHTML_ric($ms_ins,$ms_mycat){
	$HTML="";
	global $cnt;
	foreach($ms_ins as $k => $v) {
			if (is_array($v)){
					$cnt++;
					$HTML.="<label value='".__('chiuso','multiselect')."' 
								onclick='ms_open(\"ms_div$cnt\")' 
								onmouseover='ms_labelon(this)' 
								onmouseout='ms_labeloff(this)'>
							".__("$k",'multiselect')."</label>";
					$HTML.="<div id=\"ms_div$cnt\" 
								style='margin-left:20px; background-color:white; display:none;'>";
					$HTML.=ms_getHTML_ric($v,$ms_mycat);
					$HTML.="</div>";
				}
			else{
				$cnt++;
				if(in_array($k,$ms_mycat)){ $checked="checked=\"checked\""; } else {$checked="";}
				//if(ms_myinarray($k,$ms_mycat)){ $checked="checked=\"checked\""; } else {$checked="";}
				$HTML.="<label 
							onmouseover='ms_labelon(this)' 
							onmouseout='ms_labeloff(this)' style='font-weight:normal;'>" .
						"<input class=\"multicheck\" name=\"ms_$cnt\" ".$checked."  type=\"checkbox\" value=\"".__("$k",'multiselect')."\" 
								onclick=\"ms_check()\" />" .
						"".__("$k",'multiselect')."</label>"; 
			}
			
		}//end-foreach	
	return $HTML;
}
/*genero l'HTML da visualizzare lato back-end*/	
function ms_getHTMLbackend(){
	$ms_insert = ms_caricaCategorie(); //matrice di tutte le categorie e macrocategorie
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	
	$HTML="<h1>".__("QUESTA SEZIONE NON È AGGIORNATA",'multiselect')."</h1>";
	
	echo $HTML;
	}
	
function ms_getScript(){ ?>
	
	<script type='text/javascript'>
	<!--
		function ms_check()
		{

			var selected="";
			jQuery.each(jQuery('input.multicheck'), function(key,box) {
				if (jQuery(box).is(':checked')){
					if (selected=="")
						selected=jQuery(box).val();
					else
						selected= selected + ", " + jQuery(box).val();
					}
			});

			jQuery("#<?php echo(bp_get_the_profile_field_input_name())?>").val(selected);
		}
		function ms_open(divname)
		{
			if (jQuery('div#'+divname).val()=="aperto") {
				jQuery('div#'+divname).hide("slow");	
				jQuery('div#'+divname).val("chiuso");
			}
			else{
				jQuery('div#'+divname).val("aperto");
				jQuery('div#'+divname).show("slow");
			}
		}
	//-->
	</script>
	
	<script>
	<!--
		function ms_labelon(t)
		{	
			t.style.color = '#ffffff';
			t.style.background ='#888888' ;
			t.style.cursor = 'pointer';
		}

		function ms_labeloff(t)
		{
			t.style.color = 'rgb(68,68,68)';
			t.style.background ='#ffffff' ;
			t.style.cursor = 'default';
		}	
	//-->	
	</script>
	
	<style type='text/css'>
		.ms_divfrontend {
			height: auto; 
			overflow: auto; 
			//border: 1px solid rgb(238, 238, 238); 
			padding:5px 0px 0px 0px; 
			margin: 0px;
			width: 500px;
			}
		.ms_divfrontend *{
			margin: 0px;
			padding:0px;
			}
		.ms_divfrontend input {
				 
				margin-right:5px;
				vertical-align: middle;
				}
		.ms_divfrontend p {
			margin-left:10px;
			}
		.ms_divfrontend h5{
			margin: 6px 0px 2px 0px;
			padding:0px;
			font-size: 14px;
			}
		.ms_divfrontend label{
			margin: 0;
			line-height: 16px;
			}
	</style>

	<?php
	
	}
?>
