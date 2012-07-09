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
	      if(field=='Fornitore') {	
	        jQuery('div.field_numero-letti-coperti').hide(); 
	        jQuery('div.field_numero-stelle').hide();
		jQuery('div.field_categoria-attivita').show();
		jQuery('.button-nav li:last-child').show();
	      } 
	      else if(field=='Utente'){
		jQuery('div.field_numero-letti-coperti').hide(); 
		jQuery('div.field_numero-stelle').hide();
		jQuery('div.field_categoria-attivita').hide();
		jQuery('.button-nav li:last-child').hide();
	      } else {
		jQuery('div.field_numero-letti-coperti').show(); 
		jQuery('div.field_numero-stelle').show();
		jQuery('div.field_categoria-attivita').hide();
		jQuery('.button-nav li:last-child').hide();
	      }
	      }".
		"jQuery('#field_2').click(function(){correggiLetti(jQuery(this).val());}); ".
		"jQuery(document).ready(function() {correggiLetti('".$dati."');});</script>";
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
			  jQuery('".$numLettiName."').parents('div.editfield').hide();
			  jQuery('".$numStelleName."').parents('div.editfield').hide();
			  jQuery('".$macroSettoreName."').parents('div.editfield').show();
			} else if(jQuery(field).val()=='Utente') {
			  jQuery('".$numLettiName."').parents('div.editfield').hide();
			  jQuery('".$numStelleName."').parents('div.editfield').hide();
			  jQuery('".$macroSettoreName."').parents('div.editfield').hide();
			} else {
			  jQuery('".$numLettiName."').parents('div.editfield').show();
			  jQuery('".$numStelleName."').parents('div.editfield').show();
			  jQuery('".$macroSettoreName."').parents('div.editfield').hide();
			}}".
		"jQuery('#field_2').click(function(){correggiLetti(this);}); ".
		"jQuery(document).ready(function() {correggiLetti(jQuery('#field_2'));});</script>";
}
add_action("bp_after_profile_edit_content", "manageOptionalFieldsJS");
add_action('bp_after_register_page', 'manageOptionalFieldsRegistrationJS' );

//rimuove il logo dalla buddybar
function removeWlogo() {
		//echo "<script>jQuery(document).ready(function() {jQuery('#wp-admin-bar-root-default li:first-child').hide();});</script>";
		echo "<style>#wp-admin-bar-wp-logo{display:none;} a.logout{display:none;} div#sidebar div#sidebar-me {margin-bottom: 0px;}</style>";
		if ( !is_user_logged_in() ){
			echo "<style>#search-form{display: none;} </style>";
		
		}
}

add_action("wp_footer", "removeWlogo");
add_filter('admin_head','removeWlogo');

//add_action( 'wp-header', 'showAboutPage' );
//imposto il contenuto da mostrare agli utenti non loggati
function showAboutPage() {
 if ( !is_user_logged_in() ){

// 	$page_id = 27; // 123 should be replaced with a specific Page's id from your site, which you can find by mousing over the link to edit that Page on the Manage Pages admin page. The id will be embedded in the query string of the URL, e.g. page.php?action=edit&post=123.
// 	$page_data = get_page( $page_id ); // You must pass in a variable to the get_page function. If you pass in a value (e.g. get_page ( 123 ); ), WordPress will generate an error. 

// 	$content = apply_filters('the_content', $page_data->post_content); // Get Content and retain Wordpress filters such as paragraph tags. Origin from: http://wordpress.org/support/topic/get_pagepost-and-no-paragraphs-problem
// 	$title = $page_data->post_title; // Get title

	//nasconde la searchbar  e la nav bar
	echo "<style>#search-form{display: none;} </style>";

 }
}

//modifica del logo e dei link nella pagina di login
function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url(/wp-content/themes/frisco-for-buddypress/images/Best2Best2H.png) !important; 
        	width: 400px  !important; margin-bottom: 20px !important; padding-bottom: 35px !important; }
        body.login {background: #FFFFFF;}
		#login { width: 390px !important; }
    </style>';
}

add_action('login_head', 'my_custom_login_logo');

add_filter( 'login_headerurl', 'my_custom_login_url' );
function my_custom_login_url($url) {
	return 'http://www.best2best.it';
}
add_filter( 'login_headertitle', 'my_custom_login_title' );
function my_custom_login_title($title) {
	return 'Best2Best Network';
}

require_once("bp-custom-jcrop.php");


function nuovo_user_meta($user) {
	?>
    <h3>Campi aggiuntivi</h3>
        <table class="form-table">
		   <tbody><tr>
				<th scope="row">Ghost Account?</th>
				<td><label for="user_is_ghost"><input name="user_is_ghost" type="checkbox" id="user_is_ghost" value="true" <?php echo esc_attr( get_the_author_meta( 'user_is_ghost', $user->ID ) )=='true'?"checked=checked":"" ; ?> /> <?php _e('Ghost Account')?></label></td>
			</tr>
			</tbody>
		</table>
<?php }

// Aggiungiamo la nostra funzione all'amministrazione di Wordpress
// in questo "semplice" caso, la funzione "mostra" (show_user_profile)
// e quella di "modifica" (edit_user_profile) coincidono, ma in casi
// più articolati potrebbero essere differenti
add_action( 'show_user_profile', 'nuovo_user_meta' );
add_action( 'edit_user_profile', 'nuovo_user_meta' );
// Memorizza, per l'utente $user_id, un nuovo campo identificato come
// 'nuovo_user_meta'
function nuovo_user_meta_update( $user_id ) {
	// solo chi ha i permessi di editing
	if ( !current_user_can( 'edit_user', $user_id ) ) return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'user_is_ghost', $_POST['user_is_ghost'] );
}
// Anche in questo caso ci avvaliamo della add_action() per aggiungere
// il nostro pezzo di codice. Notate che permettiamo l'aggiornamento
// sia all'utente che visualizza il proprio profilo (personal_options_update)
// che a qualsiasi utente amministratore o che ha i permessi di edit (edit_user_profile_update)
add_action( 'personal_options_update', 'nuovo_user_meta_update' );
add_action( 'edit_user_profile_update', 'nuovo_user_meta_update' );
add_action( 'bp_before_member_header_meta'	, 'show_ghost_info',1);

function show_ghost_info() {
	if(get_the_author_meta('user_is_ghost',bp_displayed_user_id())=='true') {?>
	<div id="message" class="updated" style ="display:inline-block; margin: 5px 0 -15px;"><p>
	<?php _e('Attenzione, questo account non è stato verificato. <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#105;&#110;&#102;&#111;&#64;&#98;&#101;&#115;&#116;&#50;&#98;&#101;&#115;&#116;&#46;&#105;&#116;">&#67;&#111;&#110;&#116;&#97;&#116;&#116;&#97;&#99;&#105;</a> se sei tu il proprietario','buddypress'); 
	?></p></div>
	<?php 
	}	
}


add_filter( 'avatar_defaults', 'newgravatar' );

function newgravatar ($avatar_defaults) {
	$myavatar = get_stylesheet_directory_uri() . '/images/Best2Best2H_gravatar.png';
	$avatar_defaults[$myavatar] = "Best2Best avatar";
	return $avatar_defaults;
}
// add_action('bp_activity_syndication_options', 'show_group_list');
function prova() {
	//echo "lista miei gruppi";
}
add_action('bp_after_activity_post_form', 'echo_commercial');
add_action ( 'bp_after_header', 'echo_commercial_2', 0 );
function echo_commercial() {
	echo "<div class='commercial'>".__('Il network utile per i tuoi contatti commerciali','custom')."</div>";
}
function echo_commercial_2() {
	if(!is_user_logged_in() && is_front_page() ) {
		echo "<div class='commercial'>".__('Il network utile per i tuoi contatti commerciali','custom')."</div>";
	}
}
add_action('wp_before_admin_bar_render', 'menu_fix'); 
function menu_fix() {
	global $wp_admin_bar;
	$dashboard= $wp_admin_bar->get_node('site-name');
	$dashboard->href="/about/";
	$wp_admin_bar->add_node($dashboard);
	return true;
}
add_action("wp_footer", "hide_menu_non_logged");
function hide_menu_non_logged(){
	if(is_user_logged_in()) {
		echo "<style>div.nav-wrap{display: none;}</style>";
	}	
}

//nuovo menu

function display_menu() {
	global $user_ID;
	global $bp;
	$attivo = get_userdata($user_ID);
	if(!is_front_page() && is_user_logged_in()) : ?>
<div class="mh_struttura">
	<div class="mh_contenitore">
		<a href="/attivita" class="mh_link button"><span class="mh_attivita_big mh_big">Attivit&agrave;</span></a>
		<a href="<?php echo bp_loggedin_user_domain() ?>messages" class="mh_link button"><span class="mh_messaggi_big mh_big">Messaggi</span></a>
		<a href="/reviews" class="mh_link button"><span class="mh_review_big mh_big">Recensioni</span></a>
		<a href="#" onclick="alert_offerte(); this.blur(); return false;" class="mh_link button"><span class="mh_offerte_big mh_big">Offerte</span></a>
	</div>	
</div>
<script>function alert_offerte(){window.alert("Manca poco, stiamo implementando una nuova funzionalita' che vi permettera' di realizzare una vetrina dei vostri prodotti e servizi. \n\nContinuate a sostenerci. \n - Lo staff.")};</script>
	<?php endif;
}

add_action ("bp_search_login_bar","display_menu");

function addUserNotFoundRequest() {
	global $bp;
	if(is_user_logged_in()) :?><hr />
	<strong><?php _e( "Non hai trovato il profilo che cercavi e vorresti crearlo? Invia un messaggio all'amministratore e ci penseremo noi per te.", 'buddypress' );?></strong>
	<form action="<?php echo bp_core_get_user_domain( $bp->loggedin_user->id )?>messages/compose/?r=admin" method="post">
		<input type="hidden" name="subject" value="<?php _e("Creazione nuovo profilo","buddypress")?>" >
		<input type="hidden" name="content" value="<?php echo sprintf( __('Vorrei creare il nuovo profilo per %s','buddypress'),isset($_GET['s'])?$_GET['s']:""); ?>" >
		<input type="hidden" name="pagefrom" value="usernotfound" />
		<input type="submit" value="<?php _e("Invia richiesta","buddypress")?>" />
	</form>
	<?php 
	endif;
	
}
add_action ("bp_after_members_loop","addUserNotFoundRequest");
add_action ("bp_after_messages_compose_content","addCorrectFocus");
function addCorrectFocus() {
	if(isset($_POST['pagefrom'])) {
		?>
	<script type="text/javascript">
		jQuery(document).ready(function(){document.getElementById("message_content").focus();});
	</script>
		<?php 
	}
}

//add_action ("bp_members_directory_member_sub_types","addMemberSubTypes");

function addMemberSubTypes() { ?> 
<li id="members-personal" class="current"><a onclick ="jQuery('#members_search').val('fornitore');" id="fornitori-all" href="http://localhost/adesioni/fornitori">Fornitori</a></li>
<?php
}

add_filter('bp_get_user_firstname', 'fullnameDisplay',10,2);
function fullnameDisplay($val, $fullname)
{
	return implode(" ",$fullname);
}

?>