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
		//echo "<script>jQuery(document).ready(function() {jQuery('#wp-admin-bar-root-default li:first-child').hide();});</script>";
		echo "<style>#wp-admin-bar-wp-logo{display:none;}</style>";
}

add_action("wp_footer", "removeWlogo");
add_filter('admin_head','removeWlogo');

add_action( 'bp_before_directory_activity_page', 'showAboutPage' );
//imposto il contenuto da mostrare agli utenti non loggati
function showAboutPage() {
 if ( !is_user_logged_in() ){

$page_id = 27; // 123 should be replaced with a specific Page's id from your site, which you can find by mousing over the link to edit that Page on the Manage Pages admin page. The id will be embedded in the query string of the URL, e.g. page.php?action=edit&post=123.
$page_data = get_page( $page_id ); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error. 

$content = apply_filters('the_content', $page_data->post_content); // Get Content and retain Wordpress filters such as paragraph tags. Origin from: http://wordpress.org/support/topic/get_pagepost-and-no-paragraphs-problem
$title = $page_data->post_title; // Get title
echo '<div style="width:960px; margin:0 auto;"><div>
<div>
<h2 style="border-bottom: 2px solid #057022;">Che cosa siamo?</h2>
</div>
<p><img class="alignleft size-full wp-image-56" title="Best 2 Best Network" src="wp-content/uploads/2012/01/network.jpg" alt="Best 2 Best Network" width="225" height="224">Best2best &egrave; la novit&agrave; nel mondo del B2B per il settore turismo.</p>
<p>Grazie a Best2best infatti, le aziende dell&amp;Alto Adige avranno a disposizione uno strumento valido per incrementare le efficienze delle proprie relazioni d&apos;affari ed approfittare delle occasioni che le aziende vorranno mettere loro a disposizione online.</p>
<p>Con Best2Best.it, l&apos;azienda potr&agrave; individuare il partner commerciale che meglio si addice alle caratteristiche ricercate e condividere la propria esperienza con la propria community.</p>
<p>&nbsp;</p>
<p>Recensioni ed opinioni possono essere indicate con diverso livello di profondit&agrave;, garantendo cos&igrave; a chi ricerca un fornitore la migliore esperienza possibile. <a href="about">...continua</a> oppure <a href="registrati">...registrati!</a></p></div></div><hr />'; // Output Content
 }
}

//modifica del logo nella pagina di login
function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url(/wp-content/uploads/2011/06/Logo_web_insolaria_ok.png) !important; width: 400px  !important;}
	#login { width: 390px !important; }
    </style>';
}

//add_action('login_head', 'my_custom_login_logo');

add_filter( 'login_headerurl', 'my_custom_login_url' );
function my_custom_login_url($url) {
	return 'http://www.best2best.it';
}
add_filter( 'login_headertitle', 'my_custom_login_title' );
function my_custom_login_title($title) {
	return 'Best2Best Network';
}

?>
