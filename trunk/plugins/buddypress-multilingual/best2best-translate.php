<?php
/*
 * Custom best2best translation functions
 */

/**
 * Translates strings.....	- 3 - 
 *
 */
function bpml_fields_names_translate_ext($field,$content, $from_language, $to_language) 
{
/*
Nome	
Tipo profilo	
Descrizione attività	
Numero letti / coperti	
Numero stelle	
Telefono	
Indirizzo	
Altri contatti	
Lista aree di copertura	
*/


	//if( $field->name == "Tipo Profilo") 
	if( $field->id == 2) 		
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$content = "Profile Type";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$content = "Profile Type (DE)";
		}
		else 
		{
			
			/*
				
				// CONTROLLO
				if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) 
				{
					return $name;
				}
			*/
			
			// non ci va mai qui----grazie al CONTROLLO
			$content = 'tipo profiloooooooo';
		}				
	}
	
/*	
	if( $field->name == "Descrizione attivita") 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$content = "Field";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$content = "Field(DE)";
		}
		
	}
	
*/	
	
	
	
	//---------
	return $content;
}
?>