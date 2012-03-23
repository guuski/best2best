<?php

//Gestione db
include_once(ABSPATH .'wp-config.php');
include_once(ABSPATH .'wp-load.php');
include_once(ABSPATH .'wp-includes/wp-db.php');

include_once('bp-multiselect-query.php');

ms_updateDBfield();//effettua se nescessario l'update del database													//============================================


function ms_getIDmax(){
	global $bp;
	global $wpdb;
	global $visualizza;													//============================================
	
	$ms_query = "SELECT * FROM {$bp->profile->table_name_fields} f";
	$ms_output= $wpdb->get_results( $wpdb->prepare( $ms_query));
	$ms_dati= array();
	$max=200;
	foreach( (array)$ms_output as $field){
		if ($max<$field->id) {
			$max=$field->id;
			}
	}
	$visualizza=$visualizza."<br /> max=".$max;							//============================================
	return $max;
}
	
/*questa funzione controlla se nella tabella wp_bp_xprofile_fields è presente
un record con descriptio = ms Categorie Acquisti. in questo caso ho gia provveduto
all'aggiornamento del database*/
function ms_installed(){
	global $bp;
	global $wpdb;
	
	$query = "SELECT * FROM {$bp->profile->table_name_fields} f";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$ms_output as $field ){
		if ($field->description=='ms Categorie Acquisti') 
			return true;
		}
	return false;
}

/*Questa funzione controlla se la tabella wp_bp_xprofile_fields è stata
 * aggiornata dopo l'installazione del plugin. In caso contrario effettua
 * un update del database*/
 function ms_generaInsert(){
	 
	$group_id=3;
	$tabella='wp_bp_xprofile_fields';
	$type='multiselectboxrag';
	$description='ms Categorie Acquisti';
	$is_required=0;
	$is_default_option=0;
	$field_order=0;
	$option_order=0;
	$order_by='id';
	$can_delete=1;
							
	$ms_query =array(); //questo array conterrà tutte le query
	$ms_insert = ms_insert(); //matrice di tutte le categorie e macrocategorie
	
	$ms_1 = "INSERT INTO $tabella (id , group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ";
	
	
	foreach($insert as $macro => $subs) {
        do_query(insert into tabella values(macro->nome);
        id= last_insert_id;
        foreach($subs as $sub) {
                do_query(insert into tabella values($sub->nome, $id)
        }
        
    }
        
	$ms_2 = "(".($id_start+106).",$group_id,".($id_start+1).",'$type','Macchinari per pulizia'";
	
	$ms_3 = " ,	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	
	
	//$inserimento[106]=$ins ." (".($id_start+106).",$group_id,".($id_start+1).",'$type','Macchinari per pulizia',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	global $bp;
	global $wpdb;
	$ms_query=ms_generateQuery(ms_getIDmax());
	
	if (!ms_installed()) {		
		foreach ($ms_query as $key => $value){
			$wpdb->get_results( $wpdb->prepare($ms_query[$key]));	
			
		}
	}
}
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

function ms_havechildren($ms_array,$ms_id){
	/*NOTA: $ms_array è una matrice con la seguente forma:
	 * $ms_array[id]['parent']
	 * il metodo riceve l'id per il quale voglio sapere se ha dei figli
	 * ha dei figli se un qualche altroid ha come parent id
	 * $ms_array[altroid][id]
	 */
	foreach ((array)$ms_array as $k => $v){
		if ($ms_array[$k]['parent']==$ms_id) return true;
	}
	return false;
}
/*genero l'HTML della multiselect leggendo dal DB*/

function ms_getMultiselect($ms_dati,$macrocategorie)
{
	/*
	$output="<div id='GEG_contenitore'> <div class='GEG_affianca'>";
	foreach( (array)$ms_dati as $key => $val){
		//se il dato corrente è un parent
		if (isset($macrocategorie[$key])){
			$output=$output."<select multiple=\"multiple\" size='10'>";
				$output=$output."<optgroup label=\"".$macrocategorie[$key]."\">";
				
			foreach( (array)$ms_dati as $keyint => $valint){
				if ($ms_dati[$keyint]['parent']==$key){
					$output=$output."<option value=\"".$ms_dati[$keyint]['name']."\">".$ms_dati[$keyint]['name']."</option>";
				}
			}
			
				$output=$output."</optgroup>";
			$output=$output."</select>";
		}
	}
	$output=$output."</div></div>";
	echo $output;
	*/
	
	$ms_macro=ms_getMacrogategorie();
	$output="";
	foreach( (array)$ms_macro as $key => $val){
			
			$output=$output."ciao<select multiple=\"multiple\" size='10'>";
			/*
			foreach( (array)$ms_macro as $key => $nam){
					//$output=$output."<option value=\"".$nam."\">".$nam."</option>";
				}
			}
			* */
			
			
			$output=$output."</select>";
	}
	return $output;
}	

//questa funzione mi ritorna la lista delle macrocategorie
function ms_getMacrogategorie(){
	global $bp;
	global $wpdb;
	//recupero id del parent delle macrocategorie
	$query = "SELECT * FROM {$bp->profile->table_name_fields} WHERE description='ms Categorie Acquisti'";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	$ms_macrocategorie=array();
	foreach( (array)$ms_output as $field ){
		if ($field->parent_id==0) {
			$ms_macrocategorie[$field->id]=$field->name;
		}
	}
	return $ms_macrocategorie;
}
function ms_generaMultiSelect(){
	
}
	
function ms_getHTML(){
	
	//echo "max id = ".ms_getIDmax() ."<br />";
	//echo "istallato = ".ms_installed() ."<br />";
	//echo "istallato = ".ms_installed() ."<br />";
	
	//ms_havechildren($ms_array,$ms_id);
	//ms_getMultiselect($ms_dati,$macrocategorie);
	//echo "macrocategorie = ".ms_getMacrogategorie() ."<br />";
	$ms_macro=ms_getMacrogategorie();
	foreach ((array)$ms_macro as $id => $nome){
		
			echo "id=".$id . "| nome=".$nome . "<br />";
		}
	echo "<hr />";
	$ms_macro=ms_getMacrogategorieOLD();
	foreach ((array)$ms_macro as $id => $nome){	
			echo "id=".$id . "| nome=".$nome . "<br />";
	}
	/*
	global $bp;
	global $wpdb;
	global $visualizza;													//============================================
	
	$ms_query = "SELECT * FROM {$bp->profile->table_name_fields} f WHERE description='ms Categorie Acquisti' ORDER BY id ASC";
	$ms_output= $wpdb->get_results( $wpdb->prepare( $ms_query));
	$ms_dati= array();
	
	foreach( (array)$ms_output as $field){
		$ms_i=$field->id;
		$ms_n=$field->name;
		$ms_p=$field->parent_id;
		
		$ms_dati[$ms_i]['parent']=$ms_p;
		$ms_dati[$ms_i]['name']=$ms_n;
	}
	$macrocategorie=array();
	echo "<hr />";
	foreach( (array)$ms_dati as $key => $val){
		if (ms_havechildren($ms_dati,$key)){ 
				$macrocategorie[$key]=$ms_dati[$key]['name'];
				//echo "parent ".$val['parent']." | ".$key . " ha figli. il suo name è ".$macrocategorie[$key]."<br />";
			}
		else{
			//echo "parent ".$val['parent']." | ".$key . " non ha figli <br />";
		} 
	}
	
	ms_stampaMultiselect($ms_dati,$macrocategorie);
	*/
}


//questa funzione mi ritorna la lista delle macrocategorie
function ms_getMacrogategorieOLD(){
	global $bp;
	global $wpdb;
	//recupero id del parent delle macrocategorie
	$query = "SELECT * FROM {$bp->profile->table_name_fields} WHERE name='Macro categoria attività'";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	$idPadre=0;
	foreach( (array)$ms_output as $field ){
		if ($field->name=='Macro categoria attività') {
			$idPadre = $field->id;
		}
	}
		
	//creo una lista delle macrocategorie
	$query = "SELECT * FROM {$bp->profile->table_name_fields} ORDER BY id ASC";
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
	$ms_macrocategorie=array();
	foreach( (array)$ms_output as $field ){
		if ($field->parent_id==$idPadre) {
			$ms_macrocategorie[$field->id]=$field->name;
		}
	}
	return $ms_macrocategorie;
}
?>
