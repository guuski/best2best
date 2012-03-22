<?php

/*======================================================================
$drop_db="DROP DATABASE IF EXISTS $database";

$crea_db="CREATE DATABASE IF NOT EXISTS $database";

$crea_tabella = "CREATE TABLE IF NOT EXISTS $tabella (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  group_id bigint(20) unsigned NOT NULL,
  parent_id bigint(20) unsigned NOT NULL,
  type varchar(150) NOT NULL,
  name varchar(150) NOT NULL,
  description longtext NOT NULL,
  is_required tinyint(1) NOT NULL DEFAULT '0',
  is_default_option tinyint(1) NOT NULL DEFAULT '0',
  field_order bigint(20) NOT NULL DEFAULT '0',
  option_order bigint(20) NOT NULL DEFAULT '0',
  order_by varchar(15) NOT NULL DEFAULT '',
  can_delete tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  KEY group_id (group_id),
  KEY parent_id (parent_id),
  KEY field_order (field_order),
  KEY can_delete (can_delete),
  KEY is_required (is_required)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8";
======================================================================*/





/*INSERIMENTI=========================================================*/

function ms_generateQuery(	$id_start=0,
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
							

	$ins = "INSERT INTO $tabella (id , group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES ";

	//Categorie Acquisti
	
	//i primi 5 sono le macro categorie
	$inserimento[1] = $ins ." (".($id_start+1).",$group_id,0,'$type','Non Food',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	$inserimento[2] = $ins ." (".($id_start+2).",$group_id,0,'$type','Food',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[3] = $ins ." (".($id_start+3).",$group_id,0,'$type','Beverage',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[4] = $ins ." (".($id_start+4).",$group_id,0,'$type','Energia',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	$inserimento[5] = $ins ." (".($id_start+5).",$group_id,0,'$type','Agenzie Viaggi',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	
	//NON FOOD (DIRETTI DISCENDENTI)
	$inserimento[6] = $ins ." (".($id_start+6)."  ,$group_id,".($id_start+1).",'$type','Investimenti Edili',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[7] = $ins ." (".($id_start+7)."  ,$group_id,".($id_start+1).",'$type','Materiali consumo'	,			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	$inserimento[8] = $ins ." (".($id_start+8)."  ,$group_id,".($id_start+1).",'$type','Consulenze',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[9] = $ins ." (".($id_start+9)."  ,$group_id,".($id_start+1).",'$type','Parco Macchina',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[10]= $ins ." (".($id_start+10)." ,$group_id,".($id_start+1).",'$type','Indumenti di Lavoro',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[11]= $ins ." (".($id_start+11)." ,$group_id,".($id_start+1).",'$type','Servizi',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[12]= $ins ." (".($id_start+12)." ,$group_id,".($id_start+1).",'$type','Materiali Arredo',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[13]= $ins ." (".($id_start+13)." ,$group_id,".($id_start+1).",'$type','Prodotti Mobili industriali',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//FOOD (DIRETTI DISCENDENTI)
	$inserimento[14]= $ins ." (".($id_start+14)." ,$group_id,".($id_start+2).",'$type','Cane',							'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[15]= $ins ." (".($id_start+15)." ,$group_id,".($id_start+2).",'$type','Pesce'	,						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	$inserimento[16]= $ins ." (".($id_start+16)." ,$group_id,".($id_start+2).",'$type','Verdure',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[17]= $ins ." (".($id_start+17)." ,$group_id,".($id_start+2).",'$type','Pane',							'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[18]= $ins ." (".($id_start+18)." ,$group_id,".($id_start+2).",'$type','Prodotti diabetici',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[19]= $ins ." (".($id_start+19)." ,$group_id,".($id_start+2).",'$type','Spezie',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[20]= $ins ." (".($id_start+20)." ,$group_id,".($id_start+2).",'$type','Surgelati',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[21]= $ins ." (".($id_start+21)." ,$group_id,".($id_start+2).",'$type','Latticini',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[22]= $ins ." (".($id_start+22)." ,$group_id,".($id_start+2).",'$type','Cereali',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[23]= $ins ." (".($id_start+23)." ,$group_id,".($id_start+2).",'$type','Gelato',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//Beverage (DIRETTI DISCENDENTI)
	$inserimento[24]= $ins ." (".($id_start+24)." ,$group_id,".($id_start+3).",'$type','Alcolici',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[25]= $ins ." (".($id_start+25)." ,$group_id,".($id_start+3).",'$type','Analcolici'	,					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) ";
	$inserimento[26]= $ins ." (".($id_start+26)." ,$group_id,".($id_start+3).",'$type','Prodotti dietetici',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//Energia (DIRETTI DISCENDENTI)
	$inserimento[27]= $ins ." (".($id_start+27)." ,$group_id,".($id_start+4).",'$type','Elettricit&#225;',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[28]= $ins ." (".($id_start+28)." ,$group_id,".($id_start+4).",'$type','Gas',							'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[29]= $ins ." (".($id_start+29)." ,$group_id,".($id_start+4).",'$type','Gasolio',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[30]= $ins ." (".($id_start+30)." ,$group_id,".($id_start+4).",'$type','Benzina',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[31]= $ins ." (".($id_start+31)." ,$group_id,".($id_start+4).",'$type','Energia alternativa',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	//NON FOOD->Investimenti Edili (6)
	$inserimento[32]= $ins ." (".($id_start+32)." ,$group_id,".($id_start+6).",'$type','Imprese Edili',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[33]= $ins ." (".($id_start+33)." ,$group_id,".($id_start+6).",'$type','Arredo',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[34]= $ins ." (".($id_start+34)." ,$group_id,".($id_start+6).",'$type','Idraulico / Sanitari',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[35]= $ins ." (".($id_start+35)." ,$group_id,".($id_start+6).",'$type','Falegname',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[36]= $ins ." (".($id_start+36)." ,$group_id,".($id_start+6).",'$type','Cartongesso / Pittore',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[37]= $ins ." (".($id_start+37)." ,$group_id,".($id_start+6).",'$type','Ascensore incl. servizio assistenza',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[38]= $ins ." (".($id_start+38)." ,$group_id,".($id_start+6).",'$type','Elettricista',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[39]= $ins ." (".($id_start+39)." ,$group_id,".($id_start+6).",'$type','Impianti ventilazione',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[40]= $ins ." (".($id_start+40)." ,$group_id,".($id_start+6).",'$type','Grandi Cucine',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[41]= $ins ." (".($id_start+41)." ,$group_id,".($id_start+6).",'$type','Impianti refrigerazione',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[42]= $ins ." (".($id_start+42)." ,$group_id,".($id_start+6).",'$type','Banchi Bar',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[43]= $ins ." (".($id_start+43)." ,$group_id,".($id_start+6).",'$type','Prodotti per sicurezza',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[44]= $ins ." (".($id_start+44)." ,$group_id,".($id_start+6).",'$type','Piscine / Saune',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[45]= $ins ." (".($id_start+45)." ,$group_id,".($id_start+6).",'$type','Porte',							'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[46]= $ins ." (".($id_start+46)." ,$group_id,".($id_start+6).",'$type','Finestre',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[47]= $ins ." (".($id_start+47)." ,$group_id,".($id_start+6).",'$type','Compertura terrazzi',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Investimenti Edili->Imprese Edili (32)
	$inserimento[48]= $ins ." (".($id_start+48)." ,$group_id,".($id_start+32).",'$type','Scavi',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[49]= $ins ." (".($id_start+49)." ,$group_id,".($id_start+32).",'$type','Edilizia',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Investimenti Edili->Arredo (33)
	$inserimento[50]= $ins ." (".($id_start+50)." ,$group_id,".($id_start+33).",'$type','Mobili',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[51]= $ins ." (".($id_start+51)." ,$group_id,".($id_start+33).",'$type','Materassi',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[52]= $ins ." (".($id_start+52)." ,$group_id,".($id_start+33).",'$type','Tendaggi',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Investimenti Edili->Prodotti per sicurezza (43)
	$inserimento[53]= $ins ." (".($id_start+53)." ,$group_id,".($id_start+43).",'$type','Casseforti',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[54]= $ins ." (".($id_start+54)." ,$group_id,".($id_start+43).",'$type','Controllo accesso / chiavi',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[55]= $ins ." (".($id_start+55)." ,$group_id,".($id_start+43).",'$type','Videosorveglianza',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Investimenti Edili->Piscine / Saune(44)
	$inserimento[56]= $ins ." (".($id_start+56)." ,$group_id,".($id_start+44).",'$type','Piscine',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[57]= $ins ." (".($id_start+57)." ,$group_id,".($id_start+44).",'$type','Saune',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[58]= $ins ." (".($id_start+58)." ,$group_id,".($id_start+44).",'$type','Purificazione acqua piscina',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Materiali consumo (7)
	$inserimento[59]= $ins ." (".($id_start+59)." ,$group_id,".($id_start+7).",'$type','Cartografie',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[60]= $ins ." (".($id_start+60)." ,$group_id,".($id_start+7).",'$type','Decorazione',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[61]= $ins ." (".($id_start+61)." ,$group_id,".($id_start+7).",'$type','Fiori / Piante',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[62]= $ins ." (".($id_start+62)." ,$group_id,".($id_start+7).",'$type','Forniture materiali elettrici',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[63]= $ins ." (".($id_start+63)." ,$group_id,".($id_start+7).",'$type','Set Cortesia',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[64]= $ins ." (".($id_start+64)." ,$group_id,".($id_start+7).",'$type','Prodotti igene (carta igenica, saponi ecc.)',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[65]= $ins ." (".($id_start+65)." ,$group_id,".($id_start+7).",'$type','Articoli regalo (gadget)',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Materiali consumo->Forniture materiali elettrici (62)
	$inserimento[66]= $ins ." (".($id_start+66)." ,$group_id,".($id_start+62).",'$type','Lampadine',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[67]= $ins ." (".($id_start+67)." ,$group_id,".($id_start+62).",'$type','TV / Musica / Pay TV',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[68]= $ins ." (".($id_start+68)." ,$group_id,".($id_start+62).",'$type','Sistemi diffusione TV Audio',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Consulenze(8)
	$inserimento[69]= $ins ." (".($id_start+69)." ,$group_id,".($id_start+8).",'$type','Web',							'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[70]= $ins ." (".($id_start+70)." ,$group_id,".($id_start+8).",'$type','Commercialista',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[71]= $ins ." (".($id_start+71)." ,$group_id,".($id_start+8).",'$type','Design',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[72]= $ins ." (".($id_start+72)." ,$group_id,".($id_start+8).",'$type','Architetto',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[73]= $ins ." (".($id_start+73)." ,$group_id,".($id_start+8).",'$type','Ingenieria',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[74]= $ins ." (".($id_start+74)." ,$group_id,".($id_start+8).",'$type','Consulenza buste paga',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[75]= $ins ." (".($id_start+75)." ,$group_id,".($id_start+8).",'$type','Contabilit&#225;',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[76]= $ins ." (".($id_start+76)." ,$group_id,".($id_start+8).",'$type','Motivazione formazione collaboratori',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[77]= $ins ." (".($id_start+77)." ,$group_id,".($id_start+8).",'$type','Sicurezza sul lavoro, certificazionni ecc.',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[78]= $ins ." (".($id_start+78)." ,$group_id,".($id_start+8).",'$type','Consulenza Sviluppo aziendale',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Consulenze->Web (69)
	$inserimento[79]= $ins ." (".($id_start+79)." ,$group_id,".($id_start+69).",'$type','Pagine Web',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[80]= $ins ." (".($id_start+80)." ,$group_id,".($id_start+69).",'$type','Gestione Portali',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[81]= $ins ." (".($id_start+81)." ,$group_id,".($id_start+69).",'$type','Web Marketing',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Consulenze->Consulenza Sviluppo aziendale (78)
	$inserimento[82]= $ins ." (".($id_start+82)." ,$group_id,".($id_start+78).",'$type','Revenue',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[83]= $ins ." (".($id_start+83)." ,$group_id,".($id_start+78).",'$type','Controllo Costi',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Servizi (11)
	$inserimento[84]= $ins ." (".($id_start+84)." ,$group_id,".($id_start+11).",'$type','Pulizia',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[85]= $ins ." (".($id_start+85)." ,$group_id,".($id_start+11).",'$type','Sicurezza',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[86]= $ins ." (".($id_start+86)." ,$group_id,".($id_start+11).",'$type','Corriere (pacchi)',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[87]= $ins ." (".($id_start+87)." ,$group_id,".($id_start+11).",'$type','Inserzioni su media stampati (giornali, riviste...)',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[88]= $ins ." (".($id_start+88)." ,$group_id,".($id_start+11).",'$type','Servizio affitto biancheria e asciugamani',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[89]= $ins ." (".($id_start+89)." ,$group_id,".($id_start+11).",'$type','Ricerca Personale / Headhunter','$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[90]= $ins ." (".($id_start+90)." ,$group_id,".($id_start+11).",'$type','Servizi igene generali (deratizzazione, colombe)',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[91]= $ins ." (".($id_start+91)." ,$group_id,".($id_start+11).",'$type','Telecomunicazioni',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[92]= $ins ." (".($id_start+92)." ,$group_id,".($id_start+11).",'$type','Assicurazioni',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[93]= $ins ." (".($id_start+93)." ,$group_id,".($id_start+11).",'$type','Rifiuti / Smaltimento',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[94]= $ins ." (".($id_start+94)." ,$group_id,".($id_start+11).",'$type','Spazzacamino',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Servizi->Pulizia (84)
	$inserimento[95]= $ins ." (".($id_start+95)." ,$group_id,".($id_start+84).",'$type','Stanze e Locali',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[96]= $ins ." (".($id_start+96)." ,$group_id,".($id_start+84).",'$type','Vetri',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Servizi->Servizi igene generali (deratizzazione, colombe) (90)
	$inserimento[97]= $ins ." (".($id_start+97)." ,$group_id,".($id_start+90).",'$type','Purificazione aria',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Servizi->Telecomunicazioni (91)
	$inserimento[98]= $ins ." (".($id_start+98)." ,$group_id,".($id_start+91).",'$type','Telefonia fissa',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[99]= $ins ." (".($id_start+99)." ,$group_id,".($id_start+91).",'$type','Telefonia mobile',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[100]=$ins ." (".($id_start+100).",$group_id,".($id_start+91).",'$type','Dati (ADSL)',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Materiali Arredo (12)
	$inserimento[101]=$ins ." (".($id_start+101).",$group_id,".($id_start+12).",'$type','Posateria',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[102]=$ins ." (".($id_start+102).",$group_id,".($id_start+12).",'$type','Vetro / Bicchieri',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[103]=$ins ." (".($id_start+103).",$group_id,".($id_start+12).",'$type','Mobili Giardino',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[104]=$ins ." (".($id_start+104).",$group_id,".($id_start+12).",'$type','Tendaggi',						'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[105]=$ins ." (".($id_start+105).",$group_id,".($id_start+12).",'$type','Ombrelloni',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Prodotti Mobili industriali (13)
	$inserimento[106]=$ins ." (".($id_start+106).",$group_id,".($id_start+13).",'$type','Macchinari per pulizia',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[107]=$ins ." (".($id_start+107).",$group_id,".($id_start+13).",'$type','Macchina Caff&#233;',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[108]=$ins ." (".($id_start+108).",$group_id,".($id_start+13).",'$type','Stufe',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[109]=$ins ." (".($id_start+109).",$group_id,".($id_start+13).",'$type','Caminetti',				'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[110]=$ins ." (".($id_start+110).",$group_id,".($id_start+13).",'$type','Casse sicurezza',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[111]=$ins ." (".($id_start+111).",$group_id,".($id_start+13).",'$type','Macchine Lavanderia',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[112]=$ins ." (".($id_start+112).",$group_id,".($id_start+13).",'$type','Attrezzature fitness',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[113]=$ins ." (".($id_start+113).",$group_id,".($id_start+13).",'$type','Attrezzature Wellness e spa.',			'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//NON FOOD->Macchinari per pulizia (106)
	$inserimento[114]=$ins ." (".($id_start+114).",$group_id,".($id_start+106).",'$type','Aspirapolveri, vaporelle ...',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	
	//FOOD->Prodotti dietetici (18)
	$inserimento[115]=$ins ." (".($id_start+115).",$group_id,".($id_start+18).",'$type','Senza Glutine',		'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	
	//Beverage->Alcolici(24)
	$inserimento[116]=$ins ." (".($id_start+116).",$group_id,".($id_start+24).",'$type','Vino',					'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	$inserimento[117]=$ins ." (".($id_start+117).",$group_id,".($id_start+13).",'$type','Superalcolici, grappe, distillati',	'$description',$is_required,$is_default_option,$field_order,$option_order,$order_by,$can_delete) "; 
	return $inserimento;
}


?>
