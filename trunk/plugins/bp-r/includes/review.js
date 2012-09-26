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

jQuery(document).ready(function(){
  
	var jq=jQuery;
	
	jq('#review-content').focus( function()
	{
		jq('#review-content').animate({height:'200px'});
	});

});


function vote(point, field) 
{
	var fieldname= jQuery(field).parent().parent()[0].id; 
	
	jQuery("input[name="+fieldname+"]").val(point);
	jQuery("#"+fieldname+" li.current-rating").css({'width':(point*25)+'px'});
	
	return false;					
}

jQuery(function() 
{
	jQuery("#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });		//yy o yyyy?
});


//------------------------------------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------------------------------------

jQuery(document).ready(function()
{
	//alert('dentro...');
	
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
	
	form.submit(function()		
	{
		//alert('entra FORM!');
		
		if
		(		!validateTitolo() 
			||  !validateTesto() 		//testo/contenuto review			
			||	!validateTipologiaRapporto()			
			||  !validateConsigliato()									
			||  !validateData_Rapporto()			//vuota
			||	!validateGiudizio_Review() 
			||	!validateTipoReviewNegativa() 
			||  !validateDisclaimer()

		) 
		{
		
		
//---------------------------							
	if(!validateTipoReviewNegativa()) 
	{
		alert('Specifica la tipologia di review negativa');
		return false;			
	}				

//---------------------------							
	
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
	
//---------------------------				
if(!validateData_Rapporto()) 
{
	alert('Manca la Data inizio Rapporto commerciale!');
	return false;			
}
//---------------------------		
			if(!validateGiudizio_Review()) 
			{
				alert('Manca Giudizio sulla Review!');
				return false;			
			}
//---------------------------		

//---------------------------							

			
			if(!validateDisclaimer()) 
			{
				alert('Devi accettare il Disclaimer');
				return false;		
			}			

		}
		
		//
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
	//if(!tipologia_rapporto.is(':checked'))
	if(!jQuery('input[name=tipologia_rapporto]').is(':checked'))
	{
		//alert('Indica tipologia rapporto commerciale');			
		return false;
	}		
	else
	{	
		//alert('tipologia Settata');					
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

//VOTI - RATING

function validateData_Rapporto () 
{

						return true;

//<input type="text" name ="datepicker" maxlength="12" size="12" style="width:auto;"> 

	//if(data_rapporto.val().length < 10)
	//if(data_rapporto.length < 10)	
	
	//if(!jQuery('input[name="data_rapporto"]:checked').length == 0)
	
/*	
	if(!jQuery('input[name=data_rapporto]').is(':checked'))		
	{		
		return false;
	}		
	else
	{			
		return true;
	}
*/
}	
		
function validateGiudizio_Review()
{		
	if(!jQuery('input[name=giudizio_review]').is(':checked'))
	{
		//alert('GIUDIZIO REVIEW non checked');			
		return false;
	}		
	else
	{	
		//alert('GIUDIZIO REVIEW Settata');					
		return true;
	}
}	

function validateTipoReviewNegativa()
{		
/*
	if( 	!jQuery('input[name=giudizio_review]').is(':checked')
		&&  !jQuery('input[name=tipo_review_negativa]').is(':checked'))
*/		
	if(	
			!jQuery('input[name=giudizio_review]').val() != "negativo"
		&& 
			!jQuery('input[name=giudizio_review]').is(':checked')
		&& 
			!jQuery('input[name=tipo_review_negativa]').is(':checked')
		//|| !jQuery('input[name=tipo_review_negativa]').val() == " "
	)
	{		
		return false;
	}		
	else
	{			
		return true;
	}
}		

function validateDisclaimer()
{		
	//if(!jQuery('input:disclaimer').is(':checked'))
	//if(!jQuery('input[name=disclaimer]').is(':checked'))
	if (jQuery('input[name="disclaimer"]:checked').length == 0) 
	{
		//alert('disclaimer non checked');			
		return false;
	}		
	else
	{	
		//alert('disclaimer Settata');					
		return true;
	}
}
	
});
