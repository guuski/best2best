<?php

/*QUESTA FUNZIONE GENERA UNA MATRICE AVENTE COME PRIMO INDICE LE 
 * MACROCATEGORIE E COME SECONDO LE SOTTOCATEGORIE*/
function ms_insert(){
	
	$ms_insert=array( 
	'Investimenti Edili'=> array(
			"Imprese Edili.Scavi",
			"Imprese Edili.Edilizia",
			"Arredo.Mobili",
			"Arredo.Materassi" ,
			"Arredo.Tendaggi" ,
			"Idraulico / Sanitari" ,
			"Falegname" ,
			"Cartongesso / Pittore" ,
			"Ascensore incl. servizio assistenza" ,
			"Elettricista" ,
			"Impianti ventilazione" ,
			"Grandi Cucine" ,
			"Impianti refrigerazione" ,
			"Banchi Bar" ,
			"Prodotti per sicurezza.Casseforti" ,
			"Prodotti per sicurezza.Controllo accesso" ,
			"Prodotti per sicurezza.Videosorveglianza" ,
			"Piscine / Saune.Piscine" ,
			"Piscine / Saune.Saune" ,
			"Piscine / Saune.Purificazione acqua piscina" ,
			"Porte" ,
			"Finestre" ,
			"Compertura terrazzi" 
			),
	'Materiale di consumo' => array(
			"Cartografie" ,
			"Decorazione" ,
			"Fiori / Piante" ,
			"Set Cortesia" ,
			"Prodotti igene (carta igenica, saponi ecc.)" ,
			"Articoli regalo (gadget)" 
			),
	'Materiale elettrico' => array(
			"Lampadine" ,
			"TV / Musica / Pay TV" ,
			"Sistemi diffusione TV Audio" 
			),
	'Food' => array(
			"Carne" ,
			"Pesce" ,
			"Prodotti diabetici" ,
			"Spezie" ,
			"Surgelati" ,
			"Latticini" ,
			"Cereali" ,
			"Gelato" ,
			"Alcolici, Vino" ,
			"Alcolici, Superalcolici, grappe, distillati" ,
			"Analcolici" =>"",
			"Prodotti dietetici, senza Glutine"=>""
			),
	'Consulenza' => array(
			"Web.Pagine Web" ,
			"Web.Gestione Portali" ,
			"Web.Web Marketing" ,
			"Commercialista" ,
			"Design" ,
			"Architetto" ,
			"Ingenieria" ,
			"Consulenza buste paga" ,
			"Contabilit&#225;" ,
			"Motivazione formazione collaboratori" ,
			"Sicurezza sul lavoro, certificazionni ecc." ,
			"Consulenza Sviluppo aziendale.Revenue"
			"Consulenza Sviluppo aziendale.Controllo Costi"
			),
	'Parco macchina' => array(
			"Parco macchina"
			),
	'Indumenti di lavoro' => array(
			"Indumenti di lavoro"
			),
	'Servizi vari' => array(
			"Pulizia.Stanze e Locali",
			"Pulizia.Vetri",
			"Sicurezza",
			"Corriere (pacchi)",
			"Inserzioni su media stampati (giornali, riviste...)",
			"Servizio affitto biancheria e asciugamani",
			"Ricerca Personale / Headhunter",
			"Servizi igene generali (deratizzazione, colombe).Purificazione aria",
			"Telecomunicazioni.Telefonia fissa",
			"Telecomunicazioni.Telefonia mobile",
			"Telecomunicazioni.Dati (ADSL)",
			"Assicurazioni",
			"Rifiuti / Smaltimento",
			"Spazzacamino"
			),
	'Materiali arredo' => array(
			"Posateria",
			"Vetro / Bicchieri",
			"Mobili Giardino",
			"Tendaggi",
			"Ombrelloni"
			),
	'Prodotti mobili industriali'=> array(
			"Macchinari per pulizia.Aspirapolveri, vaporelle ...",
			"Macchina Caff&#233;",
			"Stufe",
			"Caminetti",
			"Casse sicurezza",
			"Macchine Lavanderia",
			"Attrezzature fitness",
			"Attrezzature Wellness e spa.",
			""
			),
	'Energia' => array(
			"Elettricit&#225;" ,
			"Gas" ,
			"Gasolio" ,
			"Benzina" ,
			"Energia alternativa" 
			),
	'Agenzie viaggio']= array(
			"Agenzie viaggio" 
			),
	'Altro']= array(
			"Altro" 
			)
	);
	return $ms_insert;
}




/*INSERIMENTI=========================================================*/

function ms_generateQuery(	$id_start,
							$group_id=3,
							$tabella='wp_bp_xprofile_fields',
							$type='multiselectboxrag',
							$description='ms Categorie Acquisti',
							$is_required=0,
							$is_default_option=0,
							$field_order=0,
							$option_order=0,
							$order_by='id',
							$can_delete=1) {
							

	//$ins = "INSERT INTO $tabella (id , group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ";

	//$inserimento[106]=$ins ." (".($id_start+106).",$group_id,".($id_start+1).",'$type','Macchinari per pulizia',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
}


?>
