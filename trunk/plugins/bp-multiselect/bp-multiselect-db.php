<?php

include_once('bp-multiselect-db.php');
include_once('bp-multiselect-query.php');
include_once('bp-multiselect.php');

//include_once(ABSPATH .'wp-config.php');
include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');

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



/*Questa funzione controlla se la tabella wp_bp_xprofile_fields è stata
 * aggiornata dopo l'installazione del plugin. In caso contrario effettua
 * un update del database*/
function ms_updateDBfield(){
	global $bp;
	global $wpdb;
	
	if (!ms_installed()) {
		$group_id=3;
		//$tabella='wp_bp_xprofile_fields';
		$type='multiselectboxrag';
		$description='ms Categorie Acquisti';
		$is_required=0;
		$is_default_option=0;
		$field_order=0;
		$option_order=0;
		$order_by='id';
		$can_delete=1;
								
		$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	
		$ms_1 = "INSERT INTO wp_bp_xprofile_fields ( group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ";
		$ms_3 = " ,	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	
		foreach($ms_insert as $macro => $subs) {
			$ms_2 = "($group_id,0,'$type','$macro'";
			$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));	
			$current_id= $wpdb->insert_id;//ID MACROCATEGORIA
			foreach($subs as $sub) {
				$ms_2 = "($group_id,$current_id,'$type','$sub'";
				$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));
				}//end-foreach
		}//end-foreach		
	}//end-if
		
	}
ms_updateDBfield();

//METODO DA TESTARE
/*Questa funzione recupera le categorie associate all'utente presenti in 
 *buddypress.wp_bp_xprofile_data*/
function ms_getCategorieUTENTE(){
	//global $user_ID;
	global $bp;
	global $wpdb;
	global $user_ID;
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE d.user_id=$user_ID AND f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));

	$field_selected=explode(",",$ms_output[0]->value);
	
	//echo $field_selected[0] ;
	return $field_selected;
	
}
function ms_isinarray($ms_string,$ms_array){
	foreach ($ms_array as $key => $value){
		if ($value==$ms_string) return true;
		}
		return false;
	}

/*genero l'HTML da visualizzare lato front-end*/
function ms_getHTMLfrontend(){
	$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	            
	echo "<span class='label'>".bp_get_the_profile_field_name()."</span>";
	$HTML= "
	<script language='JavaScript' type='text/javascript'>
	<!--
		function ms_loopSelected()
		{
			var txtSelectedValuesObj = document.getElementById('".bp_get_the_profile_field_input_name()."');
			var selectedArray = new Array();
			var selObj = document.getElementById('ms_select');
			var i;
			var count = 0;
			for (i=0; i<selObj.options.length; i++) {
				if (selObj.options[i].selected) {
				selectedArray[count] = selObj.options[i].value;
				count++;
			}
			}
			txtSelectedValuesObj.value = selectedArray;
		}
	//-->
	</script>
	";
	
	$HTML.= "<select id='ms_select' multiple='multiple' size='30' onchange='ms_loopSelected();'>";
	foreach($ms_insert as $macro => $subs) {
			$HTML.="<optgroup label='$macro'>";
			foreach($subs as $sub) {
				if (ms_isinarray($sub,$ms_mycategorie))
					$HTML.="<option SELECTED value='$sub'>$sub</option>";
				else
					$HTML.="<option value='$sub'>$sub</option>";
				}//end-foreach
			$HTML.="</optgroup>";
		}//end-foreach		
	$HTML.= "</select>";
	$HTML.= "<input type='hidden' name='".bp_get_the_profile_field_input_name()."' id='".bp_get_the_profile_field_input_name()."' value=''/>";
	$HTML.= "<p class='description'>Tenendo premuto CTRL selezionare tutte le sotto categorie associate all'attività</p>";
	
	
	//valori importanti
	//echo "<br />name=".bp_get_the_profile_field_input_name();
	//echo "<br />type=".bp_get_the_profile_field_type();
	//echo "<br />isrequired=".bp_get_the_profile_field_is_required();
	//echo "<br />edit value=".bp_get_the_profile_field_edit_value();
	
	echo $HTML;
	}
			
/*genero l'HTML da visualizzare lato back-end*/	
function ms_getHTMLbackend(){
	$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	
	$HTML= "<select multiple='multiple' size='30'>";
	foreach($ms_insert as $macro => $subs) {
			$HTML.="<optgroup label='$macro'>";
			foreach($subs as $sub) {
				if (ms_isinarray($sub,$ms_mycategorie))
					$HTML.="<option SELECTED value='$sub'>$sub</option>";
				else
					$HTML.="<option value='$sub'>$sub</option>";
				}//end-foreach
			$HTML.="</optgroup>";
		}//end-foreach		
	$HTML.= "</select>";
	$HTML.= "<p class='description'>Tenendo premuto CTRL selezionare tutte le sotto categorie associate all'attività</p>";
	echo $HTML;
	}
?>