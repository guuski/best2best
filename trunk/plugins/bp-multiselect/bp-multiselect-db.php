<?php

include_once('bp-multiselect-db.php');
include_once('bp-multiselect-query.php');
include_once('bp-multiselect.php');

//include_once(ABSPATH .'wp-config.php');
include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');

$ms_nome ='box selezione multipla raggruppata';

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
	//global $user_ID;
	global $bp;
	global $wpdb;
	global $user_ID;
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE d.user_id=$user_ID AND f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	
	if (isset($ms_output[0])) {
		$field_selected=explode(",",$ms_output[0]->value);
		return $field_selected;
	}
	//echo $field_selected[0] ;
	return array();
	
}


/*Questa funzione controlla se la tabella wp_bp_xprofile_fields è stata
 * aggiornata dopo l'installazione del plugin. In caso contrario effettua
 * un update del database*/
function ms_updateDBfield(){
	global $bp;
	global $wpdb;
	$ms_parent=ms_newfieldisset();
	if (!ms_installed() && ($ms_parent!=-1)) {
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
			$ms_2 = "($group_id,$ms_parent,'$type','$macro'";
			$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));	
			$current_id= $wpdb->insert_id;//ID MACROCATEGORIA
			foreach($subs as $sub) {
				$ms_2 = "($group_id,$current_id,'$type','$sub'";
				$wpdb->get_results( $wpdb->prepare($ms_1."".$ms_2."".$ms_3));
				}//end-foreach
		}//end-foreach		
	}//end-if
		
	}



/*Questo metodo mi dice se una stringa è presente o meno detro un array*/
function ms_isinarray($ms_string,$ms_array){
	foreach ($ms_array as $key => $value){
		if ($value==$ms_string) return true;
		}
		return false;
	}
/*Questo metodo leggendo da DB restituisce una matrice contenente tutte
 * le categorie e le sottocategorie*/
function ms_caricaCategorie(){
	//global $user_ID;
	global $bp;
	global $wpdb;
	global $user_ID;
	$ms_parent=ms_newfieldisset();
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT f.id, f.parent_id, f.name FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag'";
	$ms_array=array();
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach ($ms_output as $key =>$macrocat){
		if ($macrocat->parent_id==$ms_parent){ //sto considerando solo le macrocategorie
			$ms_array["$macrocat->name"]=array();
			
			foreach ($ms_output as $k =>$subcat){
				if ($macrocat->id==$subcat->parent_id){
					$ms_array["$macrocat->name"]["$subcat->name"]=$subcat->name;
				}
			}
		}
	}
	return $ms_array;
}
/*genero l'HTML da visualizzare lato front-end*/
function ms_getHTMLfrontend(){
	//$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	$ms_insert = ms_caricaCategorie();
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	            
	echo "<span class='label'>".bp_get_the_profile_field_name()."</span>";
	$HTML= ms_getScript();
	$HTML.="<div class='ms_divfrontend'>";
	foreach($ms_insert as $macro => $subs) {
			$HTML.="<h5>$macro</h5>";
			foreach($subs as $sub) {
				if (ms_isinarray($sub,$ms_mycategorie)){
					$HTML.="
					<p>
						<input name=\"checkgroup\" checked=\"checked\" type=\"checkbox\" value=\"$sub\" onclick='ms_disable(this.form,this.checked,\"ms_$sub\")'>
						<option id=\"ms_$sub\" disabled value='$sub'>$sub</option>
					</p>
						";
				}
				else{
					$HTML.="
					<p>
						<input name=\"checkgroup\" type=\"checkbox\" value=\"$sub\" onclick='ms_disable(this.form,this.checked,\"ms_$sub\")'>
						<option id=\"ms_$sub\" value='$sub'>$sub</option>
					</p>
						";
				}
			}//end-foreach
			//$HTML.="<br />";
		}//end-foreach		
	$HTML.= "
			</div>
			<br />
			<input type='text' readonly='readonly' name='".bp_get_the_profile_field_input_name()."' id='".bp_get_the_profile_field_input_name()."' value=''/>
			<script>ms_loop(this.form);</script>
			";
	$HTML.= "<p class='description'>Tenendo premuto CTRL selezionare tutte le sotto categorie associate all'attività</p>";
	
	//valori importanti
	//echo "<br />name=".bp_get_the_profile_field_input_name();
	//echo "<br />type=".bp_get_the_profile_field_type();
	//echo "<br />isrequired=".bp_get_the_profile_field_is_required();
	//echo "<br />edit value=".bp_get_the_profile_field_edit_value();
	echo $HTML;
	ms_caricaCategorie();
	}
			
/*genero l'HTML da visualizzare lato back-end*/	
function ms_getHTMLbackend(){
	$ms_insert = ms_caricaCategorie(); //matrice di tutte le categorie e macrocategorie
	$ms_mycategorie = ms_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	
	$HTML="<h1>QUESTA SEZIONE NON È AGGIORNATA...</h1>";
	
	echo $HTML;
	}
	
function ms_getScript(){ ?>
	
	<script language='JavaScript' type='text/javascript'>
	<!--
		function ms_loop(form)
		{
			var selected="";
			jQuery.each(jQuery('input[name=checkgroup]'), function(box) {
				if (box.is(':checked')){
					selected.= jQuery(box).val();
					}
			});
			jQuery("#<?php echo(bp_get_the_profile_field_input_name())?>").val(selected);
		}
		
		function ms_disable(form,disableIt,id)
		{
			document.getElementById(id).disabled = disableIt;
			ms_loop(form);
		}
	//-->
	</script>
	
	<style type='text/css'>
		.ms_divfrontend {
			height: 500px; 
			overflow: auto; 
			border: 1px solid rgb(238, 238, 238); 
			padding:5px 0px 0px 0px; 
			margin: 0px;
			width: 450px;
			}
		.ms_divfrontend *{
			margin: 0px;
			padding:0px;
			}
		.ms_divfrontend input {
				float:left; 
				margin-right:5px;
				}
		.ms_divfrontend p {
			margin-left:10px;
			}
		.ms_divfrontend h5{
			margin:0px 0px 5px 0px;
			padding:0px;
			}
		.ms_divfrontend option{
				
			}
	</style>

	<?php
	
	}
?>
