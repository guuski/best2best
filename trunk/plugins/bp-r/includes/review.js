jQuery(document).ready(function(){
  
	var jq=jQuery;
	
	jq('#review-content').focus( function(){
		//jq('#new-review-options').animate({height:'40px'});
		jq('#review-content').animate({height:'200px'});
		//jq('#review-submit').prop('disabled', false);
	});

});


function vote(point, field) 
{
	var fieldname= jQuery(field).parent().parent()[0].id; 
	
	jQuery("input[name="+fieldname+"]").val(point);
	jQuery("#"+fieldname+" li.current-rating").css({'width':(point*25)+'px'});
	
	return false;					
}

/*
jQuery(document).ready(function()
 { 
	jQuery( "#datepicker" ).datepicker(); 
});                     
*/

jQuery(function() {
	jQuery("#datepicker" ).datepicker();
});

//------------------------------

jQuery(document).ready(function()
{
	//alert('dentro...');
	
	var jq = jQuery;
		
	var form = jq("#review-form");		
	

	var giudizio_review = jq("#giudizio_review");
	var title 			= jq("#review-title");	
	var nameInfo		= jq("#nameInfo");
	var message 		= jq("#review-content");
	var data_rapporto   = jq("#datepicker");

		var tipologia   = jq("#tipologia");
	
	//blur
	title.blur(validateTitle);
		
	//key press	
	title.keyup(validateTitle);
	//message.keyup(validateMessage);
	

		//	function form_submit()			
	form.submit(function()		
	{
		//alert('entra FORM!');
		
		if
		(
				!validateGiudizio_Review() 
			||  !validateTitle() 
			||  !validateMessage() 
			||  !validateTipologia() 
			||	!validateData_Rapporto()   
			||  !validateConsigliato()
			||  !validateDisclaimer()
		
		) 
		{
				
			if(!validateGiudizio_Review()) 
			{
				alert('Manca Giudizio sulla Review!');
				return false;			
			}
			
			if(!validateTitle() ) 
			{
				alert('Titolo Review mancante!');
				return false;			
			}

			if(!validateMessage()) 
			{
				alert('Manca il Contenuto della Review!');
				return false;		
			}
				
			if(!validateTipologia()) 
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
			
			if(!validateDisclaimer()) 
			{
				alert('Devi accettare il Disclaimer');
				return false;		
			}
		}		
		//
		return true;
		
		
		
//		if(validateTitle() & validateMessage()) 
//		{
			//alert('OK');
//			return true		
//		}
//		else 
//		{
//			alert('Compila tutti i campi!');
//			return false;
//		}
		
});
		
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
	
	function validateTipologia()
	{	
		if(!jQuery('input[name=tipologia]').is(':checked'))
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
	
	
	//$('input[name=foo]').attr('checked')
	
});

//------------------------------------------------------------------------------------------------------------------------------------------------

/*
<form method="post" id="customForm" action="">
			<div>
				<label for="name">Name</label>
				<input id="name" name="name" type="text" />
				<span id="nameInfo">What's your name?</span>
			</div>
			
			<div>
				<label for="message">Message</label>
				<textarea id="message" name="message" cols="" rows=""></textarea>
			</div>
			
			<div>
				<input id="send" name="send" type="submit" value="Send" />
			</div>
		
*/

/*
function form_submit()			
	{
		//alert('entra FORM!');
		
		if(!validateTitle() || !validateMessage() || !validateGiudizio_Review || !validateData_Rapporto) 
		{
			if(!validateTitle() ) 
			{
				alert('Titolo Review mancante!');
				return true		
			}

			if(!validateMessage()) 
			{
				alert('Manca il Contenuto della Review!');
				return true		
			}
			
			if(!validateGiudizio_Review()) 
			{
				alert('Manca Giudizio sulla Review!');
				return true		
			}
			
			if(!validateData_Rapporto()) 
			{
				alert('Manca la Data inizio Rapporto commerciale!');
				return true		
			}
		}		
		//
		return false;
		
		

}

*/
/*

	function validateTitle()
	{
	
	

		var t 	= jQuery("#review-title");				
		
		if(t.val().length < 3)
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
*/	