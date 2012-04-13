<?php

/*QUESTA FUNZIONE GENERA UNA MATRICE AVENTE COME PRIMO INDICE LE 
 * MACROCATEGORIE E COME SECONDO LE SOTTOCATEGORIE
 * 
 * Per inserire nuove categorie o sottocategorie non utilizzare nel testo
 * la virgola ma lo / */
 
 
function ms_insert(){
	
	$ms_insert=array( 
	'Investimenti Edili'=> array(
			'Imprese Edili'=> array(
				"Scavi", 
				"Edilizia"
				),
			'Arredo'=> array(
				"Mobili", 
				"Materassi", 
				"Tendaggi"
				),
			"Idraulico/Sanitari" ,
			"Falegname" ,
			"Cartongesso/Pittore" ,
			"Ascensore incl. servizio assistenza" ,
			"Elettricista" ,
			"Impianti di ventilazione" ,
			"Grandi Cucine" ,
			"Impianti di refrigerazione" ,
			"Banchi Bar" ,
			'Prodotti per la sicurezza'=> array(
				"Casseforti" , 
				"Controllo accesso/ chiavi" , 
				"Videosorveglianza" 
				),
			'Piscine-Saune'=> array(
				"Piscine" ,
				"Saune" ,
				"Purificazione acqua piscina" 
				),
			"Porte" ,
			"Finestre" ,
			"Copertura terrazzi" 
			),
	'Materiale di consumo' => array(
			"Cartografie" ,
			"Decorazione" ,
			"Fiori/Piante" ,
			"Set Cortesia" ,
			"Prodotti igiene(carta igienica-saponi ecc.)" ,
			"Articoli regalo (gadget)" 
			),
	'Materiale elettrico' => array(
			"Lampadine" ,
			"TV-Musica-Pay TV" ,
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
			'Alcolici' => array(
				"Vino" ,
				"Superalcolici/Grappe/Distillati" 
				),
			"Analcolici",
			'Prodotti dietetici'=> array(
				"Senza Glutine"
				)
			),
	'Consulenze' => array(
			'Web' => array(	
				"Pagine Web" ,
				"Gestione Portali" ,	
				"Web Marketing" ),
			"Commercialista" ,
			"Design" ,
			"Architetto" ,
			"Ingegneria" ,
			"Consulenza buste paga" ,
			"Contabilità" ,
			"Motivazione formazione collaboratori" ,
			"Sicurezza sul lavoro/certificazioni ecc." ,
			'Consulenza Sviluppo aziendale' => array(
				"Revenue",
				"Controllo Costi"
				)
			),
	'Parco macchina' => array(
			"Parco macchina"
			),
	'Indumenti di lavoro' => array(
			"Indumenti di lavoro"
			),
	'Servizi vari' => array(
			'Pulizia' => array(	
				"Stanze e Locali", 
				"Vetri"
				),
			"Sicurezza",
			"Corriere (pacchi)",
			"Inserzioni su media stampati (giornali/riviste...)",
			"Servizio affitto biancheria e asciugamani",
			"Ricerca Personale/Headhunter",
			'Servizi igiene generali' => array(
				"derattizzazione/colombe",
				"Purificazione aria"
				),
			'Telecomunicazioni' => array(
				"Telefonia fissa", 
				"Telefonia mobile", 
				"Dati (ADSL)"
				),
			"Assicurazioni",
			"Rifiuti/Smaltimento",
			"Spazzacamino"
			),
	'Materiali arredo' => array(
			"Posateria",
			"Vetro/Bicchieri",
			"Mobili Giardino",
			"Tendaggi",
			"Ombrelloni"
			),
	'Prodotti mobili industriali'=> array(
			'Macchinari per pulizia'=> array(
				"Aspirapolveri/vaporelle ..."
				),
			"Macchina Caffè",
			"Stufe",
			"Caminetti",
			"Casse sicurezza",
			"Macchine Lavanderia",
			'Attrezzature'=> array(
				"Fitness", 
				"Wellness e spa."
				)
			),
	'Energia' => array(
			"Elettricità" ,
			"Gas" ,
			"Gasolio" ,
			"Benzina" ,
			"Energia alternativa" 
			),
	'Agenzie viaggio' => array(
			"Agenzie viaggio" 
			),
	'Altro' => array(
			"Altro" 
			)
	);
	return $ms_insert;
}
?>
