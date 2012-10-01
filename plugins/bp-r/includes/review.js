//-------------------------------------------------------------------------------------------------------
// #datepicker
//-------------------------------------------------------------------------------------------------------

jQuery('.DateTextBox.NoYear').datepicker(
{
	beforeShow: function (input, inst) 
	{
		inst.dpDiv.addClass('NoYearDatePicker');
	},

	onClose: function(dateText, inst){
		inst.dpDiv.removeClass('NoYearDatePicker');
	}

});


/*
jQuery('.DateTextBox.NoMonth').datepicker(
{
	beforeShow: function (input, inst) 
	{
		inst.dpDiv.addClass('NoMonthDatePicker');
	},
	
	onClose: function(dateText, inst){
		inst.dpDiv.removeClass('NoMonthDatePicker');
	}
});
*/

/*
jQuery('.DateTextBox.NoCalendar').datepicker(
{
	beforeShow: function (input, inst) 
	{
		inst.dpDiv.addClass('NoCalendarDatePicker');
	},

//	onClose: function(dateText, inst){
//		inst.dpDiv.removeClass('NoCalendarDatePicker');
//	}
	
});
*/

/*
jQuery('#datepicker_2').datepicker( 
{ 
    changeYear: true, 
    dateFormat: 'yy', }
);
*/


jQuery("input[name='datepicker']").datepicker(
{ 
//    dateFormat: 		'dd/mm/yy'
	  dateFormat: 'yy' 

    , changeYear: 		true
//	, changeMonth: 		false									//	
//	, changeDay: 		false									//
//	, numberOfMonths: 	1
	, showButtonPanel: 	true
    
	, minDate: new Date("1980-09-01")

//	, maxDate: "+5Y",	
	, maxDate:			new Date								//non puoi inserire una data futura!
	
	, yearRange: "1950:2030"
	
	, yearSuffix: " anno"
	
//	, constrainInput: true 

//	, setDate:			new Date								//
//	, setDate:			'25/06/12'								//

	,   onClose: function(dateText, inst) 
		{ 
            //var month 	= jQuery("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year	= jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
			
            //jQuery(this).datepicker('setDate', new Date(year, month, 1));
			jQuery(this).datepicker('setDate', new Date(year, 1, 1));
        }

	,	onSelect: function(datepicker) 
		{
			var date = jQuery(this).datepicker('getDate');
			//date.setDate(date.getDate() + 1);
			//console.log("date  :  " + date);
		}
		
	,	onChangeMonthYear: function(year, month, inst) 
		{
			var year	= jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
			jQuery(this).datepicker('setDate', new Date(year, 1, 1));
		}
});

//-------------------------------------------------------------------------------------------------------

//jQuery("input[name='datepicker']").datepicker().datepicker('setDate', '25/06/12');

//-------------------------------------------------------------------------------------------------------

/*
jQuery(function() 
{
	jQuery("#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });							//yy o yyyy?
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
// ANIMATE #review-content
//-------------------------------------------------------------------------------------------------------

jQuery(document).ready(function()
{  
	jQuery('#review-content').focus( function()
	{
		jQuery('#review-content').animate(
		{
			height:'200px'
		});
	});
});

//-------------------------------------------------------------------------------------------------------
// rating
//-------------------------------------------------------------------------------------------------------

function vote(point, field) 
{
	var fieldname = jQuery(field).parent().parent()[0].id; 
	
	jQuery("input[name="+fieldname+"]").val(point);
	jQuery("#"+fieldname+" li.current-rating").css(
	{
		'width':(point*25)+'px'
	});
	
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
	
	//---------------------------------------------
	var prezzo   		 	 = jq("#prezzo");		
	//var servizio   		 	 = jq("#servizio");				//va lo stesso		
	//---------------------------------------------
	
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
			
			//---------------------------------------------
			//||  !validateVotoPrezzo()							
			//||  !validateVotoServizio()							
			
			||  !validateVoti()				
			//---------------------------------------------			
			
			||  !validateData_Rapporto()			
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


			//-----------------------------------------------------
			//voti,ratings 
			//-----------------------------------------------------

			if(!validateVoti()) 
			{
				alert('Assegna tutti i voti');
				return false;			
			}		
			
/*			
			if(!validateVotoPrezzo()) 
			{
				alert('Voto Prezzo mancante');
				return false;			
			}				
			
			if(!validateVotoServizio()) 
			{
				alert('Voto Servizio mancante');
				return false;			
			}					
*/			
			//-----------------------------------------------------
			
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


//-----------------------------------------------------//-----------------------------------------------------//-----------------------------------------------------
function validateVoti() 				
{
	var v1 = jQuery('input[name=prezzo]').val();
	var v2 = jQuery('input[name=servizio]').val();
	var v3 = jQuery('input[name=qualita]').val();
	var v4 = jQuery('input[name=affidabilita]').val();
	var v5 = jQuery('input[name=puntualita]').val();

	if ( v1 < 1 || v2 < 1 || v3 < 1 || v4 < 1 || v5 < 1 ) 
	{
		//alert('servizio non settato');
		//console.log("servizio(non settato)  :  " + s);
		return false;	
	}
	else
	{
		//alert('servizio OK');	
		//console.log("servizio(OK)  :  " + s);
		return true;
	}	
	
}

//-----------------------------------------------------

//-----------------------------------------------------
// SERVIZIO
//-----------------------------------------------------

function validateVotoServizio() 				
{
	var s = jQuery('input[name=servizio]').val();
	
	if (s < 1) 
	{
		//alert('servizio non settato');
		//console.log("servizio(non settato)  :  " + s);
		return false;	
	}
	else
	{
		//alert('servizio OK');	
		//console.log("servizio(OK)  :  " + s);
		return true;
	}
	
}

//-----------------------------------------------------
// PREZZO
//-----------------------------------------------------
function validateVotoPrezzo() 				
{
	var p = jQuery('input[name=prezzo]').val();
	
	if (p < 1) 
	{
		//alert('prezzo non settato');
		//console.log("prezzo(non settato)  :  " + p);
		return false;	
	}
	else
	{
		//alert('prezzo OK');	
		//console.log("prezzo(OK)  :  " + p);
		return true;
	}
	
/*	
	//if(!jQuery('input[name=prezzo]').is(':empty'))	
	if(!(jQuery('input[name="prezzo"]').length == 0))
	{		
		//alert('prezzo non settato');
		console.log("prezzo(non settato)  :  " + p);
		return false;
	}		
	else
	{	
		//alert('prezzo OK');	
		console.log("prezzo(OK)  :  " + p);
		return true;
	}
*/	
}


//-----------------------------------------------------//-----------------------------------------------------

function validateData_Rapporto () 				
{
	
	var data   = jQuery('input[name=datepicker]').val();
	var data_3 = jQuery('input[name=datepicker]').datepicker( "getDate" );
		//var data_3 = "niente";

/*		
	if(data != null) 
		alert('data:  '.data); //datA

	//if(date != null) 		alert('date:  '.date);  //datE

	
	if(data_3!= null) 
		alert('data3:  '.data_3);  //data_3
*/	
	
	

	//if(!jQuery('input[name="data_rapporto"]').length == 0)
	//if(!jQuery('input[name="data_rapporto"]').is(':empty'))
	
	//if(!jQuery('input[name="datepicker"]').length == 0)
	
		
	//if( !("#datepicker").is(':empty') )
	
	if(data_3 == null)
	//if(!jQuery('input[name="datepicker"]').is(':empty'))
	{		
		//console.log("datA  :  " + data);
			//console.log("datE  :  " + date);
		//console.log("datA 3  :  " + data_3);		
		
		return false;
	}		
	else
	{			
		//console.log("datA  :  " + data);
			//console.log("datE  :  " + date);
		//console.log("datA 3  :  " + data_3);
		
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
	var valore_giudizio_review 		= jQuery('input[name=giudizio_review]:checked').val();					//:checked
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
	if (jQuery('input[name="disclaimer"]:checked').length == 0) //checked
	{
		return false;
	}		
	else
	{	
		return true;
	}
}
	
});
