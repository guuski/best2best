<?php

require_once 'bp-custom-config-env.php';

//Gestisce i campi profilo in fase di modifica profilo  
function manageOptionalFieldsJS() {
	global $bp;
	
	$dati=BP_XProfile_ProfileData::get_value_byfieldname("Tipo profilo");

	if($dati!="Fornitore") {
		echo "<script>jQuery('.button-nav li:last-child').hide();</script>";
	}
	echo "<script>function correggiLetti(field) {
	      if(jQuery(field).val()=='Fornitore') {	
	        jQuery('div.field_numero-letti-coperti').hide(); 
	        jQuery('div.field_numero-stelle').hide();
		jQuery('div.field_macro-categoria-attivita').show();
		jQuery('.button-nav li:last-child').show();
	      } 
	      else {
		jQuery('div.field_numero-letti-coperti').show(); 
		jQuery('div.field_numero-stelle').show();
		jQuery('div.field_macro-categoria-attivita').hide();
		jQuery('.button-nav li:last-child').hide();
	      }
	      }".
		"jQuery('#field_2').click(function(){correggiLetti(this);}); ".
		"jQuery(document).ready(function() {correggiLetti(jQuery('#field_2'));});</script>";
}

//Gestisce i campi profilo in fase di registrazione profilo  
function manageOptionalFieldsRegistrationJS() {
global $numLettiName;
global $numStelleName;
global $macroSettoreName;
		echo 
		"<style>#tos{float:left}</style>\n<div style='width:800px; white-space:nowrap; float:left;'>". __( 'Fields marked * are required', 'buddypress' ) ."</div>\n\n".
		"<script>function correggiLetti(field) {
			if(jQuery(field).val()=='Fornitore') {
			  jQuery('".$numLettiName."').parent().hide();
			  jQuery('".$numStelleName."').parent().hide();
			  jQuery('".$macroSettoreName."').parent().show();
			} 
			else {
			  jQuery('".$numLettiName."').parent().show();
			  jQuery('".$numStelleName."').parent().show();
			  jQuery('".$macroSettoreName."').parent().hide();
			}}".
		"jQuery('#field_2').click(function(){correggiLetti(this);}); ".
		"jQuery(document).ready(function() {correggiLetti(jQuery('#field_2'));});</script>";
}
add_action("xprofile_template_loop_end", "manageOptionalFieldsJS");
add_action('bp_after_register_page', 'manageOptionalFieldsRegistrationJS' );

//rimuove il logo dalla buddybar
function removeWlogo() {
		echo "<script>jQuery(document).ready(function() {jQuery('#wp-admin-bar-root-default li:first-child').hide();});</script>";
}

add_action("wp_footer", "removeWlogo");
?>
