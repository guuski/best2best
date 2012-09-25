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
	
	var nameInfo			 = jq("#nameInfo");	//usata in validate_title() ...<span id="nameInfo"> <?php _e( 'Inserisci un titolo...', 'reviews' ); ?>  </span>
		
	var title 				 = jq("#review-title");		
	var message 		 	 = jq("#review-content");		//testo/contenuto review
	var tipologia_rapporto   = jq("#tipologia_rapporto");
	var data_rapporto   	 = jq("#datepicker");
	var giudizio_review 	 = jq("#giudizio_review");
	var tipo_review_negativa = jq("#tipo_review_negativa");

	
	//blur
	title.blur(validateTitle);
		
	//key press	
	title.keyup(validateTitle);	
	
	form.submit(function()		
	{
		//alert('entra FORM!');
		
		if
		(		!validateTitle() 
			||  !validateMessage() 		//testo/contenuto review
			||  !validateTipologia() 
			||  !validateConsigliato()
			||	!validateTipologiaRapporto()   
			||	!validateGiudizio_Review() 
			||	!validateTipoReviewNegativa() 
			||  !validateDisclaimer()
		
		) 
		{
	
			if(!validateTitle() ) 
			{
				alert('Titolo mancante!');
				return false;			
			}

			if(!validateMessage()) 
			{
				alert('Manca il Contenuto della Review!');
				return false;		
			}
				
			if(!validateTipologiaRapporto()) 
			{
				alert('Manca la Tipologia Rapporto commerciale!');
				return false;			
			}	
				
			if(!validateData_Rapporto()) 
			{
				alert('Manca la Data inizio Rapporto commerciale!');
				return false;			
			}
			
			if(!validateConsigliato()) 
			{
				alert('Manca il campo "Lo Raccomdanderesti?"');
				return false;			
			}
		
			if(!validateGiudizio_Review()) 
			{
				alert('Manca Giudizio sulla Review!');
				return false;			
			}
						
			if(!validateTipoReviewNegativa()) 
			{
				alert('Specifica la tipologia di review negativa');
				return false;			
			}				
			
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

function validateTitle()
{
	if(title.val().length < 3)
	{
		//title.addClass("error");
		nameInfo.text("troppo corto!");
		//nameInfo.addClass("error");
		return false;
	}		
	else
	{
		//title.removeClass("error");
		nameInfo.text("OK");
		//nameInfo.removeClass("error");
		return true;
	}
}

function validateMessage()
{	
	if(message.val().length < 3)
	{
		//message.addClass("error");
		return false;
	}		
	else
	{			
		//message.removeClass("error");
		return true;
	}
}

function validateTipologiaRapporto()
{	
	if(!jQuery('input[name=tipologia_rapporto]').is(':checked'))
	{
		//alert('tipologia non checked');			
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
		//alert('consigliato non checked');			
		return false;
	}		
	else
	{	
		//alert('consigliato Settata');					
		return true;
	}
}

//VOTI - RATING

function validateData_Rapporto () 
{
	if(data_rapporto.val().length < 10)
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
	if(!jQuery('input[name=tipo_review_negativa]').is(':checked'))
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
	if(!jQuery('input[name=disclaimer]').is(':checked'))
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



