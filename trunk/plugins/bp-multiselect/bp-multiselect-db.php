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
	$query = "SELECT f.name FROM wp_bp_xprofile_data d INNER JOIN wp_bp_xprofile_fields f WHERE d.field_id=f.id AND d.value='ms Categorie Acquisti' AND d.user_id='$user_ID'";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	$ms_data=array();
	foreach( (array)$ms_output as $field){
			$ms_data[$field->name];
		}//end-foreach
	return $ms_data;
	}



/*genero l'HTML da visualizzare lato front-end*/
function ms_getHTMLfrontend(){
	$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	            
	echo "<span class='label'>".bp_get_the_profile_field_input_name()."</span>";
	$HTML= "<select name='".bp_get_the_profile_field_input_name()."' multiple='multiple' size='30'>";
	foreach($ms_insert as $macro => $subs) {
			$HTML.="<optgroup label='$macro'>";
			foreach($subs as $sub) {
				if (isset($ms_mycategorie[$sub]))
					$HTML.="<option SELECTED value='$sub'>$sub</option>";
				else
					$HTML.="<option value='$sub'>$sub</option>";
				}//end-foreach
			$HTML.="</optgroup>";
		}//end-foreach		
	$HTML.= "</select>";
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
				if (isset($ms_mycategorie[$sub]))
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
