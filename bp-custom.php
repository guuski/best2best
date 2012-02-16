<?php
//Gestisce i campi profilo in fase di modifica profilo  
function manageOptionalFieldsJS() {
	global $bp;
	
	$dati=BP_XProfile_ProfileData::get_value_byfieldname("Tipo profilo");

	if($dati!="Fornitore") {
		echo "<script>jQuery('.button-nav li:last-child').hide();</script>";
	}
	echo "<script>function correggiLetti(field) {if(jQuery(field).val()=='Fornitore') {jQuery('div.field_numero-letti-coperti').hide();} else {jQuery('div.field_numero-letti-coperti').show();}}".
		"jQuery('#field_2').click(function(){correggiLetti(this);}); ".
		"jQuery(document).ready(function() {correggiLetti(jQuery('#field_2'));});</script>";
}
//Gestisce i campi profilo in fase di registrazione profilo  
function manageOptionalFieldsRegistrationJS() {
		echo 
		"<script>function correggiLetti(field) {
			if(jQuery(field).val()=='Fornitore') {
				jQuery('#field_89').parent().hide();} else {jQuery('#field_89').parent().show();
			}}".
		"jQuery('#field_2').click(function(){correggiLetti(this);}); ".
		"jQuery(document).ready(function() {correggiLetti(jQuery('#field_2'));});</script>";
}
add_action("xprofile_template_loop_end", "manageOptionalFieldsJS");
add_action('bp_after_signup_profile_fields', 'manageOptionalFieldsRegistrationJS' );

//rimuove il logo dalla buddybar
function removeWlogo() {
		echo "<script>jQuery(document).ready(function() {jQuery('#wp-admin-bar-root-default li:first-child').hide();});</script>";
}

add_action("wp_footer", "removeWlogo");
?>
