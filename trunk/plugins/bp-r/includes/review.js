//-------------------------------------------------------------------------------------------------------
// #datepicker
//-------------------------------------------------------------------------------------------------------

jQuery("input[name='datepicker']").datepicker(
{ 
	dateFormat: 'dd/mm/yy', 
	changeMonth: true, 
	changeYear: true, 
	numberOfMonths: 1, 
	showButtonPanel: true
});


//-------------------------------------------------------------------------------------------------------
/*
jQuery(function() 
{
	jQuery("#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });		//yy o yyyy?
});
*/

//-------------------------------------------------------------------------------------------------------

(function(jQuery) 
{	
	jQuery.datepicker.regional['it'] = 
	{
		monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
		'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
		monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
		'Lug','Ago','Set','Ott','Nov','Dic'],
		dayNames: ['Domenica','Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato'],
		dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
		dateFormat: 'dd/mm/yyyy', firstDay: 1,
		renderer: jQuery.datepicker.defaultRenderer,
		prevText: '&#x3c;Prec', prevStatus: 'Mese precedente',
		prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: 'Mostra l\'anno precedente',
		nextText: 'Succ&#x3e;', nextStatus: 'Mese successivo',
		nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: 'Mostra l\'anno successivo',
		currentText: 'Oggi', currentStatus: 'Mese corrente',
		todayText: 'Oggi', todayStatus: 'Mese corrente',
		clearText: 'Svuota', clearStatus: 'Annulla',
		closeText: 'Chiudi', closeStatus: 'Chiudere senza modificare',
		yearStatus: 'Seleziona un altro anno', monthStatus: 'Seleziona un altro mese',
		weekText: 'Sm', weekStatus: 'Settimana dell\'anno',
		dayStatus: '\'Seleziona\' D, M d', defaultStatus: 'Scegliere una data',
		isRTL: false
	};
	
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['it']);
	
})(jQuery);

//-------------------------------------------------------------------------------------------------------
// #review-content
//-------------------------------------------------------------------------------------------------------

jQuery(document).ready(function(){
  
	var jq=jQuery;
	
	jq('#review-content').focus( function()
	{
		jq('#review-content').animate({height:'200px'});
	});

});

//-------------------------------------------------------------------------------------------------------
// rating
//-------------------------------------------------------------------------------------------------------

function vote(point, field) 
{
	var fieldname= jQuery(field).parent().parent()[0].id; 
	
	jQuery("input[name="+fieldname+"]").val(point);
	jQuery("#"+fieldname+" li.current-rating").css({'width':(point*25)+'px'});
	
	return false;					
}

//------------------------------------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------------------------------------

//$('#submit_button').click(function() {									//ALT
jQuery(document).ready(function()
{
	
	var jq = jQuery;
		
	var form = jq("#review-form");		
	
	var nameInfo 			 = jq("#nameInfo");		//usata in validateTitolo() ...<span id="message"> <?php _e( 'Inserisci un titolo...', 'reviews' ); ?>  </span>
	var titolo 	 			 = jq("#review-title");		
	var testo 	 			 = jq("#review-content");		//testo / contenuto 
	var tipologia_rapporto   = jq("#tipologia_rapporto");
	var consigliato   		 = jq("#consigliato");		
	var data_rapporto   	 = jq("#datepicker");
	var giudizio_review 	 = jq("#giudizio_review");
	var tipo_review_negativa = jq("#tipo_review_negativa");	
	
	//blur
	titolo.blur(validateTitolo);
		
	//key press	
	titolo.keyup(validateTitolo);	
	
	//$('#submit_button').click(function() {								//ALT
	form.submit(function()		
	{
		//alert('entra FORM!');
		
		if
		(		!validateTitolo() 
			||  !validateTesto() 					//testo/contenuto review			
			||	!validateTipologiaRapporto()			
			||  !validateConsigliato()									
			||  !validateData_Rapporto()			//vuota-staccata!
			||	!validateGiudizio_Review() 
			||	!validateTipoReviewNegativa() 
			||  !validateDisclaimer()

		) 
		{						
			if(!validateTitolo() ) 
			{
				alert('Titolo mancante!');
				return false;			
			}

			if(!validateTesto()) 
			{
				alert('Manca il testo della Review!');
				return false;		
			}
		
			if(!validateTipologiaRapporto()) 
			{
				alert('Manca la Tipologia Rapporto commerciale!');
				return false;			
			}								
			
			if(!validateConsigliato()) 
			{
				alert('Manca il campo "Lo Raccomdanderesti?"');
				return false;			
			}	
			
			//voti,ratings ---> vd		
				
			if(!validateData_Rapporto()) 
			{
				alert('Manca la Data inizio Rapporto commerciale!');
				return false;			
			}
			
			if(!validateGiudizio_Review()) 
			{
				alert('Manca Giudizio sulla Review!');
				return false;			
			}			
			
			if(!validateTipoReviewNegativa() ) 
			{
				alert('Specifica la tipologia di review negativa');
				return false;			
			}				
			else 
			{
				//alert('tipologia di review negativa settata');
				//return true;
			}		
			
			if(!validateDisclaimer()) 
			{
				alert('Devi accettare il Disclaimer');
				return false;		
			}			

		}
		
		//RETURN
		return true;	
});

//------------------------------------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------------------------------------

function validateTitolo()
{
	if(titolo.val().length < 3)
	{		
		nameInfo.text("troppo corto!");		
		return false;
	}		
	else
	{		
		nameInfo.text("OK");	
		return true;
	}
}

function validateTesto()
{	
	if(testo.val().length < 3)
	{		
		return false;
	}		
	else
	{			
		return true;
	}
}

function validateTipologiaRapporto()
{			
	if(!jQuery('input[name=tipologia_rapporto]').is(':checked'))		//ALT - //if(!tipologia_rapporto.is(':checked'))
	{		
		return false;
	}		
	else
	{			
		return true;
	}
}
	
function validateConsigliato()
{	
	if(!jQuery('input[name=consigliato]').is(':checked'))
	{		
		return false;
	}		
	else
	{		
		return true;
	}
}


function validateData_Rapporto () 				
{
	if(!jQuery('input[name="data_rapporto"]').length == 0)
	{		
		return false;
	}		
	else
	{			
		return true;
	}

}	
		
function validateGiudizio_Review()
{		
	if(!jQuery('input[name=giudizio_review]').is(':checked'))
	{		
		return false;
	}		
	else
	{			
		return true;																	
	}
}	

function validateTipoReviewNegativa()
{		
	var valore_giudizio_review = jQuery('input[name=giudizio_review]:checked').val();					//:checked
	var valore_tipo_review_negativa = jQuery('input[name=tipo_review_negativa]:checked').val();			//:checked
	
	if(		!jQuery('input[name=tipo_review_negativa]').is(':checked')	
		&&  jQuery('input[name=giudizio_review]').is(':checked')
		&&  valore_giudizio_review == "negativo"
	)
	{		
			
		//console.log("giuzizio review:  " + valore_giudizio_review);
		//console.log("tipo_review_negativa:  " + valore_tipo_review_negativa);
		
		return false;
	}		
	else
	{	
	
		//console.log("giuzizio review:  " + valore_giudizio_review);
		//console.log("tipo_review_negativa:  " + valore_tipo_review_negativa);
		return true;
	}
}		

function validateDisclaimer()
{		
	if (jQuery('input[name="disclaimer"]:checked').length == 0) 
	{
		return false;
	}		
	else
	{	
		return true;
	}
}
	
});
