<?php
/*
 * Profile functions
 * @todo delete field hook NO HOOK
 */

/**
 * Init hook.
 */
function bpml_profiles_init() 
{

	//VUOTO!

}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//------- XPROFILE FIELD Values  (utente/user, hotel/albergo, webmaster....)	[TABELLA wp_bp_xprofile_data] ---------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Profilae field value filter.
 * 
 * @global  $sitepress
 * @global  $bpml
 * @global boolean $bpml_profiles_field_value_suppress_filter
 * @param <type> $value
 * @param <type> $type
 * @param <type> $field_id
 * @return <type>
 */
function bpml_profiles_bp_get_the_profile_field_value_filter($value, $type, $field_id) 
{
    global $sitepress, $bpml, $bpml_profiles_field_value_suppress_filter;
	
/*	
    if (!empty($bpml_profiles_field_value_suppress_filter)) 	//?
	{
        return $value;
    }
*/	
	
    if (!isset($bpml['profiles']['fields'][$field_id])) 		// vede l' OPZIONE per il field specifico
	{
        return $value;
    }
	
    $lang = $sitepress->get_current_language();	
    
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$value = bpml_profiles_get_field_translation($field_id, $lang, $value);		//F call
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    return $value;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
// [TABELLA wp_bp_xprofile_data_bpml]
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Fetches field translation.
 *
 * @global $wpdb $wpdb
 * @global  $bpml
 * @global  $sitepress
 * @global boolean $bpml_profiles_field_value_suppress_filter
 * @param <type> $field_id
 * @param <type> $lang
 * @param <type> $value
 * @return <type>
 */
function bpml_profiles_get_field_translation($field_id, $lang, $value = '') 
{
    global $wpdb, $bpml, $sitepress, $bpml_profiles_field_value_suppress_filter;
	
    if ($sitepress->get_default_language() == $lang) 
	{
        return $value;
    }
    
	//
	$translation 
		= apply_filters('bp_get_the_profile_field_edit_value', $wpdb->get_var($wpdb->prepare("SELECT value FROM {$wpdb->prefix}bp_xprofile_data_bpml WHERE field_id=%d and lang=%s", $field_id, $lang)));
    
	if (empty($translation)) 
	{
		//debug MSG!
        bpml_debug('Missing tranlsation for field: ' . $field_id);
		
        if ($bpml['profiles']['translation']['user-missing'] && empty($bpml_profiles_field_value_suppress_filter)) 
		{
			//GOOGLE TRANSLATE!!!
            require_once dirname(__FILE__) . '/google-translate.php';
            $value = bpml_google_translate(apply_filters('bp_get_the_profile_field_edit_value', $value), $sitepress->get_default_language(), $lang);
            
			//debug MSG!
			bpml_debug('Fetching Google translation for field: ' . $field_id);
        }
		
        return $value;
    } 
	else 
	{
        return $translation;
    }
}



//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//          add_action('bp_after_profile_edit_content'	, 'bpml_profiles_bp_after_profile_edit_content_hook');
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Adds translation options on 'Edit Profile' page.
 * 
 * @global <type> $bp
 * @global <type> $bpml
 * @global <type> $sitepress
 * @global boolean $bpml_profiles_field_value_suppress_filter
 * @return <type>
 */
function bpml_profiles_bp_after_profile_edit_content_hook() 
{
    global $bp, $bpml, $sitepress, $bpml_profiles_field_value_suppress_filter;

    $bpml_profiles_field_value_suppress_filter = TRUE;									//suppress FILTER!

    require_once dirname(__FILE__) . '/google-translate.php';			//GOOGLE TRANSLATE!

    $default_language = $sitepress->get_default_language();
    $langs = $sitepress->get_active_languages();
    $group = BP_XProfile_Group::get(array(
                'fetch_fields' => true,
                'profile_group_id' => $bp->action_variables[1],
                'fetch_field_data' => true
            ));
    echo '<a name="bpml-translate-fields">&nbsp;</a><br /><h4>Translate fields</h4>';
	
    foreach ($group[0]->fields as $field) 
	{
        if (!isset($bpml['profiles']['fields'][$field->id]) || empty($field->data->value)) {
            continue;
        }
        echo '<div><a href="javascript:void(0);" onclick="jQuery(this).next(\'div\').toggle();">' . $field->name . '</a><div style="display:none;">';
        
		foreach ($langs as $lang) 
		{
            if ($lang['code'] == $default_language) {
                continue;
            }
			
            echo '<input class="bpml-profiles-field-toggle-button" type="button" onclick="jQuery(\'#bpml-profiles-form-field-' . $field->id . '-' . $lang['code'] . '\').toggle();" value="' . $lang['english_name'] . '" />';
            echo '<form style="display:none;margin:0;" id="bpml-profiles-form-field-' . $field->id . '-' . $lang['code'] . '" class="bpml-form-ajax standard-form" method="post" action="' . admin_url('admin-ajax.php') . '">';
            echo $field->type == 'textarea' ? '<textarea class="bpml-profiles-field-content" name="content" cols="40" rows="5">' . apply_filters('bp_get_the_profile_field_edit_value', bpml_profiles_get_field_translation($field->id, $lang['code'], $field->data->value)) . '</textarea>' : '<input type="text" class="bpml-profiles-field-content" name="content" value="' . apply_filters('bp_get_the_profile_field_edit_value', bpml_profiles_get_field_translation($field->id, $lang['code'], $field->data->value)) . '" />';
            echo '
                 <br />
                    <input type="hidden" value="' . $field->id . '" name="bpml_profiles_translate_field" />
                    <input type="hidden" value="' . $lang['code'] . '" name="bpml_profiles_translate_field_lang" />
            
					<input type="hidden" name="dummy" class="bpml_profiles_translate_field_google_translated" value="' . bpml_google_translate(apply_filters('bp_get_the_profile_field_edit_value', $field->data->value), $default_language, $lang['code']) . '" />
                    <input type="submit" value="Save translation" name="bpml_profiles_translate_field" />

                    <input type="submit" value="Get translation from Google" name="bpml_profiles_translate_with_google" class="bpml_profiles_translate_with_google" />
                
				<div class="bmp-ajax-update"></div>
				
				</form><br />';
        }
        echo '</div></div>';
    }
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
// add_action('bpml_ajax'						, 'bpml_profiles_ajax');
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Processes AJAX call for updating field translation.
 * 
 * @global <type> $current_user
 */
function bpml_profiles_ajax() 
{
    if (isset($_POST['bpml_profiles_translate_field']) && is_user_logged_in()) 
	{
        global $current_user;
    
		$field_id = $_POST['bpml_profiles_translate_field'];
        $lang	  = $_POST['bpml_profiles_translate_field_lang'];
        
		bpml_profile_update_translation($current_user->ID, $field_id, $lang, $_POST['content']);
        
		echo json_encode(array('output' => 'Done'));
    }
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Updates field translation.
 * 
 * @global $wpdb $wpdb
 * @param <type> $user_id
 * @param <type> $field_id
 * @param <type> $lang
 * @param <type> $content
 */
function bpml_profile_update_translation($user_id, $field_id, $lang, $content) 
{
    global $wpdb;

    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}bp_xprofile_data_bpml WHERE field_id=%d AND user_id=%d AND lang=%s", $field_id, $user_id, $lang));

    if (empty($exists)) 
	{
        $wpdb->insert($wpdb->prefix . 'bp_xprofile_data_bpml', array(
            'field_id' => $field_id,
            'user_id' => $user_id,
            'lang' => $lang,
            'value' => $content,
                ), array('%d', '%d', '%s', '%s'));
    } 
	else {
        $wpdb->update($wpdb->prefix . 'bp_xprofile_data_bpml', array(
            'value' => $_POST['content']
                ), array(
            'user_id' => $user_id,
            'field_id' => $field_id,
            'lang' => $lang),
                array('%s'),
                array('%d', '%d', '%s'));
    }
}



//------------------------------------------------------------------------------------------------------------------
//	aggiunto in 'sitepress-bp.php' 
// 	---> add_action('xprofile_data_before_save'		, 'bpml_xprofile_data_before_save_hook');
//------------------------------------------------------------------------------------------------------------------

/**
 * Notices user about changed fields.
 * 
 * @param <type> $field
 */
function bpml_xprofile_data_before_save_hook($field) 
{
    bpml_store_frontend_notice('profile-field-updated', '<a href="#bpml-translate-fields">Check if fields need translation updated.</a>');
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// 2 - TO DO
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Translates strings.....	
 *
 * @staticvar string $client
 * @param <type> $item
 * @param <type> $current_language
 * @return <type>
 */
function bpml_fields_names_translate($content, $from_language, $to_language) 
{
	// 2 - TO DO
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------TRANSLATE FIELD TITLES (o NAMES)-------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Translates field names.														---titles!!!
 * 
 * @global  $sitepress
 * @global <type> $field
 * @staticvar array $cache
 * @param <type> $name
 * @return array
 */
function bpml_bp_get_the_profile_field_name_filter( $name ) 
{
	//
    global $sitepress, $field;
	
	//controllo
    if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) 
	{
        return $name;
    }	
/*
	
//----------CACHE 1/2 --------------
/*
    static $cache = NULL;

    if (is_null($cache)) 
	{
        $cache = get_option('bpml_profile_fileds_names', array());				//bpml_profile_fileds_names			---fileds-----fields?
    }	
	
		
    if (isset($cache[$field->id][ICL_LANGUAGE_CODE])) 
	{
        return $cache[$field->id][ICL_LANGUAGE_CODE];
    }
*/	
		
	// 1 - OLD --- [ function bpml_google_translate($content, $from_language, $to_language)  ]----		
	//
	//require_once dirname(__FILE__) . '/google-translate.php';
	//$name = bpml_google_translate($name, $sitepress->get_default_language(), ICL_LANGUAGE_CODE);		
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//
	// 2 - TO DO
	//$name = bpml_fields_names_translate ($name, $sitepress->get_default_language(), ICL_LANGUAGE_CODE);	
	//
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	//
	// 3 - '_ext'
	//require_once dirname(__FILE__) . '/best2best-translate.php';
	//$name = bpml_fields_names_translate_ext($field, $name, $sitepress->get_default_language(), ICL_LANGUAGE_CODE); 			//aggiungto campo '$field'	
		
/*
Nome						1	x
Tipo profilo				2	x
Telefono					5	x
Indirizzo					6	x
Altri contatti				7	x
Lista aree di copertura		8	x
Descrizione attività		25  X
Numero letti / coperti		73  X
Numero stelle				76  X
*/	
		
		
										//	--- N.B--- metti lo SWITCH o INCLUDE ext file 

	//NOME- id: 1
	if( $field->id == 1) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Name";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Name (DE)";
		}		
	}
	
	//TIPO PROFILO- id: 2
	if( $field->id == 2) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{
			$name = "Profile Type";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{			
			$name = "Profile Type (DE)";
		}	
	}
	
	//TELEFONO - id: 5
	if( $field->id == 5) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Phone Number";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Phone Number(DE)";
		}
	}
			
	//Indirizzo	- id: 6
	if( $field->id == 6) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Address";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Address (DE)";
		}		
	}	

	//Altri contatti - id: 7
	if( $field->id == 7) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Other 	";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Other 	(DE)";
		}
		
	}

	//Lista aree di copertura - id: 8
	if( $field->id == 8) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Area";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Area(DE)";
		}
	}

	//Descrizione attivita - id: 25
	if( $field->id == 25) 	
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Work Field Description";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Work Field Description (DE)";
		}
				
	}
		
	//Numero letti / coperti - id: 73
	if( $field->id == 73) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Bedrooms Number";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Bedrooms Number(DE)";
		}
		
	}	

	//Numero stelle	- id: 76
	if( $field->id == 76) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Stars";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Stars (DE)";
		}
		
	}
	
	//----------CACHE 2/2-----------
	/*
    $cache[$field->id][ICL_LANGUAGE_CODE] = $name;    		
	update_option('bpml_profile_fileds_names', $cache);				//bpml_profile_fileds_names			---fileds-----fields?    
	*/		
	
	//RETURN
	return $name;	
}


//-------------------------------------------------------------------------------------------------------------------
//------- XFIELD GROUP names  (base, contatti, area fornitura)	[TABELLA wp_bp_xprofile_groups] ---------------------
//-------------------------------------------------------------------------------------------------------------------

/**
 * Translates XFIELD GROUP names.														
 * 
 * @global  $sitepress
 * @global <type> $group
 * @param <type> $name
 * @return array
 *
 */
function bpml_bp_get_the_profile_group_name_filter( $name ) 
{
	//
    global $sitepress, $group;
	
	//controllo
    if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) 
	{
        return $name;
    }

	//NOME- id: 1
	if( $group->id == 1) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Base";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Profil verwalten";
		}		
	}
	
	//NOME- id: 2
	if( $group->id == 2) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Contacts";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Kontakte";
		}		
	}
	
	//NOME- id: 3
	if( $group->id == 3) 
	{
		if (ICL_LANGUAGE_CODE=="en") 
		{			
			$name = "Supplying Area";
		}		
		else if (ICL_LANGUAGE_CODE=="de") 
		{
			$name = "Liefer/Service Einzugsgebiet";
		}		
	}

	//RETURN
	return $name;	
}