<?php

//Gestione db
include_once('wp-config.php');
include_once('wp-load.php');
include_once('wp-includes/wp-db.php');

include_once('bp-multiselect-query.php');

ms_updateDBfield();//effettua se nescessario l'update del database

function ms_getIDmax(){
	global $bp;
	global $wpdb;
	$query = "SELECT * FROM {$bp->profile->table_name_fields} f";
	$fields = $wpdb->get_results( $wpdb->prepare( $query));
	$max=0;
	foreach( (array)$fields as $field )
		if ($max<$field->id) $max=$field->id;
	return $max;
	}
	
/*questa funzione controlla se nella tabella wp_bp_xprofile_fields è presente
un record con descriptio = ms Categorie Acquisti. in questo caso ho gia provveduto
all'aggiornamento del database*/
function ms_installed(){
	global $bp;
	global $wpdb;
	global $description;
	
	$query = "SELECT * FROM {$bp->profile->table_name_fields} f";
	$fields = $wpdb->get_results( $wpdb->prepare( $query));
	foreach( (array)$fields as $field ){
		if ($field->description=='ms Categorie Acquisti') 
			return true;
		}
	return false;
}

/*Questa funzione controlla se la tabella wp_bp_xprofile_fields è stata
 * aggiornata dopo l'installazione del plugin. In caso contrario effettua
 * un update del database*/
function ms_updateDBfield(){
	global $bp;
	global $wpdb;
	$ms_query=ms_generateQuery(ms_getIDmax());
	
	if (!ms_installed()) {		
		foreach ($ms_query as $key => $value){
			$wpdb->get_results( $wpdb->prepare($ms_query[$key]));	
		}
	}
		
}

/*genero l'HTML della multiselect leggendo dal DB*/
function ms_getHTML(){
	global $bp;
	global $wpdb;
	
	$ms_query = "SELECT * FROM {$bp->profile->table_name_fields} f WHERE description='ms Categorie Acquisti' ORDER BY id ASC";
	$ms_output= $wpdb->get_results( $wpdb->prepare( $ms_query));
	
	foreach( (array)$ms_output as $field )
		echo $field->name."<br />";
	
	$output="
	<div id='GEG_contenitore'>
		  <p>Quarta soluzione</p>
		  <form action='#' method='POST'>
			<div class='GEG_affianca'>
				<select multiple=\"multiple\" size='10'>
					<optgroup label=\"Arredi\">
						<option value=\"Arredi scolastici\">Arredi scolastici</option>
						<option value=\"Arredi per ufficio\">Arredi per ufficio</option>
						<option value=\"Arredi per seggi elettorali\">Arredi per seggi elettorali</option>
						<option value=\"Tende, veneziane, tappezzerie e articoli affini\">Tende, veneziane, tappezzerie e articoli affini</option>
						<option value=\"Porte, finestre, scale e articoli affini\">Porte, finestre, scale e articoli affini</option>
						<option value=\"Arredi vari\">Arredi vari</option>
					</optgroup>
				</select>
			</div>
			
			<div class='GEG_affianca'>
				<select multiple=\"multiple\" size='10'>
					<optgroup label=\"Ristorazione\">
						<option value=\"Buoni pasto\">Buoni pasto</option>
						<option value=\"Ristorazione\">Ristorazione</option>
						<option value=\"Varie\">Varie</option>
					</optgroup>
				</select>
			</div>
			
			<div class='GEG_affianca'>
				<select  multiple=\"multiple\" size='10'>
					<optgroup label=\"Ufficio e Cancelleria\">
						<option value=\"Carta per tipografia\">Carta per tipografia</option>
						<option value=\"Carta per fotocopiatrici\">Carta per fotocopiatrici</option>
						<option value=\"Cancelleria\">Cancelleria</option>
						<option value=\"Modulistica\">Modulistica</option>
						<option value=\"Materiali di consumo\">Materiali di consumo</option>
						<option value=\"Macchine e attrezzature d'ufficio\">Macchine e attrezzature d&#40ufficio</option>
						<option value=\"Computers e periferiche\">Computers e periferiche</option>
						<option value=\"Apparati di rete\">Apparati di rete</option>
						<option value=\"Software\">Software</option>
						<option value=\"Pubblicazioni\">Pubblicazioni</option>
					</optgroup>
				</select> 
			</div>		
			
		  </form>
		  <div id='output'></div>
	  </div>
	  ";
	 
}
?>
