<?php 

load_plugin_textdomain( 'multiselect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


$bps_nome ='box selezione multipla raggruppata';

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
							
$bps_1 = "INSERT INTO wp_bp_xprofile_fields ( group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ($group_id,";
$bps_3 = " ,	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";

/*Questa funzione controlla se nella tabella wp_bp_xprofile_fields è 
 * presente un record con descriptio = ms Categorie Acquisti. in questo 
 * caso ho gia provveduto all'aggiornamento del database*/
function bps_installed(){
	global $bp;
	global $wpdb;
	
	$query = "SELECT * FROM wp_bp_xprofile_fields f";
	$bps_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$bps_output as $field ){
		if ($field->description=='ms Categorie Acquisti') 
			return true;
		}//end-foreach
	return false;
}


/*Questo metodo mi ritorna l'id del nuovo field creato dall'utente oppure ritorna
 * -1 in caso di assenza del field*/
function bps_newfieldisset(){
	global $bp;
	global $wpdb;
	global $bps_nome;
	$query = "SELECT * FROM wp_bp_xprofile_fields f";
	$bps_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$bps_output as $field ){
		if ($field->type==$bps_nome) 
			return $field->id;
		}//end-foreach
	return -1;
}

//METODO DA TESTARE
/*Questa funzione recupera le categorie associate all'utente presenti in 
 *buddypress.wp_bp_xprofile_data*/
function bps_getCategorieUTENTE(){
	global $bp;
	global $wpdb;
	global $user_ID;
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE d.user_id=$user_ID AND f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	
	$bps_output= $wpdb->get_results( $wpdb->prepare($query));
	
	if (isset($bps_output[0])) {
		$field_selected=explode(", ",$bps_output[0]->value);
		return $field_selected;
	}
	return array();
	
}


function bps_updateDBfield(){
	global $bp;
	global $wpdb;
	$bps_parent=bps_newfieldisset();
	if (!bps_installed() && ($bps_parent!=-1)) {
		$bps_insert = bps_insert(); //matrice di tutte le categorie e macrocategorie
		bps_generaDB_ric($bps_insert,$bps_parent);
	}//end-if
		
	}

		
	function bps_generaDB_ric($bps_mat, $bps_par){
		global $bps_1,$bps_3;
		global $type;
		global $bp;
		global $wpdb;
		
		if (is_array($bps_mat)){
		
			foreach ($bps_mat as $k => $v){
				if (is_array($v)){
					$bps_2 = "$bps_par,'$type','$k'";
					$wpdb->get_results( $wpdb->prepare($bps_1."".$bps_2."".$bps_3));	
					
					$current_id= $wpdb->insert_id;//ID MACROCATEGORIA
					bps_generaDB_ric($v,$current_id);
				}//end-if
				else{
					$bps_2 = "$bps_par,'$type','$v'";
					$wpdb->get_results( $wpdb->prepare($bps_1."".$bps_2."".$bps_3));	
					
				}//end-else
			}//end-foreach
		}//end-if
	}//end-function


/*Questo metodo leggendo da DB restituisce una matrice contenente tutte
 * le categorie e le sottocategorie*/
function bps_caricaCategorie(){
	global $bp;
	global $wpdb;
	global $user_ID;
	$bps_parent=bps_newfieldisset();
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT f.id, f.parent_id, f.name FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag'";
	$bps_array=array();
	$bps_output= $wpdb->get_results( $wpdb->prepare($query));
	 
	return  bps_caricaCT_ric($bps_output,$bps_parent);
}
function bps_caricaCT_ric($bps_db,$bps_par){
	
		if (is_array($bps_db)){
			$bps_mat='';
			foreach ($bps_db as $k => $v){
				if ($v->parent_id==$bps_par){
						$bps_mat["$v->name"]= bps_caricaCT_ric($bps_db,$v->id);
				}//end-if
			}//end-foreach
			return $bps_mat;
		}//end-if
	return '';
	}
/*genero l'HTML da visualizzare lato front-end*/
function bps_getHTMLfrontend(){
	$bps_insert = bps_caricaCategorie();
	//$bps_mycategorie = bps_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	$bps_mycategorie = array(); //vettore che contiene le categorie dell'utente	            
	            
	echo "<span class='label'>".__(bp_get_the_profile_field_name(),'multiselect')."</span>";
	$HTML= bps_getScript();
	$HTML.="<div class='bps_divfrontend'>";
	
	$HTML.=bps_getHTML_ric($bps_insert,$bps_mycategorie);

	$HTML.= "
			</div>
			<br />
			<input type='text' readonly='readonly' 
				name='bps_total' 
				id='bps_total' 
				value=''
				style='width:500px;'/>
			<script>bps_check();</script>
			";
	
	echo $HTML;
	//bps_caricaCategorie();
	}
	
function bps_getHTML_ric($bps_ins,$bps_mycat){
	$HTML="";
	global $cnt;
	foreach($bps_ins as $k => $v) {
			if (is_array($v)){
					$cnt++;
					$HTML.="<label value='".__('chiuso','multiselect')."' 
								onclick='bps_open(\"bps_div$cnt\")' 
								onmouseover='bps_labelon(this)' 
								onmouseout='bps_labeloff(this)'>
							".__("$k",'multiselect')."</label>";
					$HTML.="<div id=\"bps_div$cnt\" 
								style='margin-left:20px; background-color:white; display:none;'>";
					$HTML.=bps_getHTML_ric($v,$bps_mycat);
					$HTML.="</div>";
				}
			else{
				$cnt++;
				if(in_array($k,$bps_mycat)){ $checked="checked=\"checked\""; } else {$checked="";}
				//if(bps_myinarray($k,$bps_mycat)){ $checked="checked=\"checked\""; } else {$checked="";}
				$HTML.="<label 
							onmouseover='bps_labelon(this)' 
							onmouseout='bps_labeloff(this)' style='font-weight:normal;'>" .
						"<input class=\"multicheck\" name=\"bps_$cnt\" ".$checked."  type=\"checkbox\" value=\"".__("$k",'multiselect')."\" 
								onclick=\"bps_check()\" />" .
						"".__("$k",'multiselect')."</label>"; 
			}
			
		}//end-foreach	
	return $HTML;
}
/*genero l'HTML da visualizzare lato back-end*/	
function bps_getHTMLbackend(){
	$bps_insert = bps_caricaCategorie(); //matrice di tutte le categorie e macrocategorie
	$bps_mycategorie = bps_getCategorieUTENTE(); //vettore che contiene le categorie dell'utente
	
	$HTML="<h1>".__("QUESTA SEZIONE NON È AGGIORNATA",'multiselect')."</h1>";
	
	echo $HTML;
	}
	
function bps_getScript(){ ?>
	
	<script type='text/javascript'>
	<!--
		function bps_check()
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

			jQuery("#<?php echo("bps_total")?>").val(selected);
		}
		function bps_open(divname)
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
		function bps_labelon(t)
		{	
			t.style.color = '#ffffff';
			t.style.background ='#888888' ;
			t.style.cursor = 'pointer';
		}

		function bps_labeloff(t)
		{
			t.style.color = 'rgb(68,68,68)';
			t.style.background ='#ffffff' ;
			t.style.cursor = 'default';
		}	
	//-->	
	</script>
	
	<style type='text/css'>
		.bps_divfrontend {
			height: auto; 
			overflow: auto; 
			//border: 1px solid rgb(238, 238, 238); 
			padding:5px 0px 0px 0px; 
			margin: 0px;
			width: 500px;
			}
		.bps_divfrontend *{
			margin: 0px;
			padding:0px;
			}
		.bps_divfrontend input {
				 
				margin-right:5px;
				vertical-align: middle;
				}
		.bps_divfrontend p {
			margin-left:10px;
			}
		.bps_divfrontend h5{
			margin: 6px 0px 2px 0px;
			padding:0px;
			font-size: 14px;
			}
		.bps_divfrontend label{
			margin: 0;
			line-height: 16px;
			}
	</style>

	<?php
	
	}

?>
